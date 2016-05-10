<?php namespace EID\Http\Controllers;

class SCController extends Controller {	// Sickle Cell Controller
										// TO-DO: 	sickle cell lab does not keep date data

	public function cancel_scws($sc_worksheet_id)
	{
		return view('scd.cancel_scws', array('scws' => $sc_worksheet_id));
	}

	public function delete_scws($sc_worksheet_id)
	{
		$sql = "START TRANSACTION;\n";
		$sql .= "UPDATE 	dbs_samples 
					SET ready_for_SCD_test = 'YES' 
					WHERE 	id in (	SELECT 	sample_id 
									FROM 	sc_worksheet_index 
									WHERE 	worksheet_number = '$sc_worksheet_id'
								); ";
		$sql .= "DELETE FROM sc_worksheet_index WHERE worksheet_number = '$sc_worksheet_id';";
		$sql .= "DELETE FROM sc_worksheets WHERE id = '$sc_worksheet_id';";
		$sql .= "COMMIT;";


		\DB::unprepared($sql);
		return redirect()->route('wlist');
	}


	static function getTrayPosition($position)
	{

		if($position < 0 || $position > SC_SAMPLES_PER_WORKSHEET+NUMBER_OF_CONTROLS){
			return "XXX";// out of range
		}

		$matrix = \SCManager::getTrayLayout();

		return $matrix[$position]; 
	}

	public function show_results($scws_id)
	{
		return view('scd.results', array('ws' => $scws_id));
	}

	public function get_samples($arr)
	{		

		$q = trim($arr["q"]);// $q = "dbs_700893,dbs_700894,dbs_700895,"
		$sampleIDs = str_replace("dbs_", "", $q);// sampleIDs = "700893,700894,700895"
		$sampleIDs = str_replace('"', '', $sampleIDs);// sampleIDs = "700893,700894,700895"

		$sampleIDs = rtrim($sampleIDs, ",");

		return $sampleIDs;

	}

	public static function make_new_worksheet()
	{
		// create Worksheet
		$scws = new \SCWorksheet;
		$scws->CreatedBy = \Auth::user()->id;
		$scws->DateCreated = date('Y-m-d');		
		$scws->save();

		 return $scws->id;	
	}


	public function save_ws_data()	
	{
// 	test last: 
//		1) given insufficient samples, returns a -ve number [DONE]
//		2) given enough samples, returns a +ve number  [DONE]
//		3) given enough samples, returns a +ve number which exists in DB as worksheets.id [DONE]
//		4) any dbs_samples.id randomly chosen from $arr[] exists in DB & has ready_for_SCD_test=='TEST_ALREADY_DONE'	
//		5) any sc_worksheet_index.sample_id randomly chosen from $arr[] exists in DB


		$worksheet_number = $this->make_new_worksheet();
		$nSamplesNeeded = \SCManager::getNumSamplesPerWorksheet();

		$arr = \Request::all();
		$sampleIDs = $this->get_samples( $arr );


		$sql = "SELECT id, SCD_test_result FROM dbs_samples 
				WHERE id IN ( $sampleIDs )";// Get samples that need SC test only.
											// Samples that also need PCR test, go for PCR first.
								  			// The PCR module adds them to a SC worksheet after its done.

		$sc_samples = \DB::select($sql);
		$samples_available = count($sc_samples);

		if($samples_available < $nSamplesNeeded)	
			return (-1*$nSamplesNeeded); // not enough samples. stop.

		// create Worksheet's index
		$sql = "INSERT INTO sc_worksheet_index (worksheet_number, sample_id, position) VALUES ";

		$i = -1; // tracks tray positions
		$comma = ",";

		foreach ($sc_samples as $sample) {

			$i++;
			if(\SCManager::isControl($i)){
				$i++;
			}

			$sample_id = $sample->id;
			$sample_position = $this->getTrayPosition($i);

			$sql .= "\n ('$worksheet_number', '$sample_id', '$sample_position') " . $comma;
		}

		$sql = rtrim($sql, $comma);

		\DB::unprepared($sql);
		\DB::unprepared("UPDATE dbs_samples SET ready_for_SCD_test = 'TEST_ALREADY_DONE'  WHERE id IN ($sampleIDs)");		
		return $worksheet_number;				
	}

	public function enter_results()
	{
		return view('SCD_results');
	}


	public function store_results()
	{

		$results_data = \Request::except('_token', 'worksheet_number', 'result_number');
		$worksheet_number = \Request::get('worksheet_number');
		$result_number = \Request::get('result_number');
		$editing_completed_worksheet = false;

		if(\Request::has('editing') && \Request::has('editing') == $worksheet_number){
			$editing_completed_worksheet = true;			
		}

		if($result_number == 1  || $result_number == 2 || $result_number == 3){

			$all_results_ready = $this->update_index($worksheet_number, $result_number, $results_data);
			$this->update_worksheet($worksheet_number, $result_number, $all_results_ready);
			$samples_to_retain = $this->schedule_repeat_tests($worksheet_number, $results_data);// if any

			if($all_results_ready || $editing_completed_worksheet){
				$this->release_results($worksheet_number, $samples_to_retain, $results_data);

			// Business Logic:
			// If all results for the worksheet have been entered, then release batches for immediate print & dispatch.
			// if any sample is marked as blank or needs retest, the whole batch is retained
			//  with conclusive results
			//
			//	Note: Unless a worksheet is being edited, it must have $all_results_ready == true.

			}


			return view('scwsList');
		}
		die("Illegal route to the SCWS store: No such result");// rare. But issue a 404 instead of this
	}

	public function sql_implode($glue, $input_array, $default_value) {	
	/* 	
		Exactly like implode(), but with a default value that is returned if $input_array is empty.
		Useful when its output will be used by an IN() but caller is afraid $input_array may sometimes be empty
	*/
		
		if( empty($input_array) )
			return "$default_value";
		else
			return implode($glue, $input_array);
	}


	public function save_results( $results_data )
	{
		$rSQL = "";
		foreach ($results_data as $sample_id => $sc_status) {
			$rSQL .= "UPDATE dbs_samples 	
						SET 	SCD_test_result = '$sc_status', 
								SCD_results_ReleasedBy = '" . \Auth::user()->id . "',
								ready_for_SCD_test = 'TEST_ALREADY_DONE' 
						WHERE id = '$sample_id'; ";
		}
		return $rSQL;
	}


	public function release_results($worksheet_number, $samples_to_retain, $results_data)
	{
		/* 	Release results for all samples in the given worksheet, except batches that have $samples_to_retain.
			Released results immediately appear for printing 
		*/
		$batches_to_retain = $this->sql_implode(", ", $samples_to_retain->batches_to_retain, "-1");
		$samples_in_this_worksheet = "SELECT sample_id FROM sc_worksheet_index where worksheet_number  = '$worksheet_number'";
		$batches_in_this_worksheet = "SELECT DISTINCT batch_id FROM dbs_samples WHERE id IN ( $samples_in_this_worksheet ) ";
		$batches_to_release = $batches_in_this_worksheet . " AND batch_id NOT IN ($batches_to_retain)";

		$TODAY = date("Y-m-d");

		$release_sql = 	"UPDATE batches 
							SET SCD_results_released = 'YES',
								date_SCD_testing_completed = '$TODAY'
							WHERE batches.id IN ( $batches_to_release ); ";

		$update_dbs_sql = $this->save_results( $results_data );// updates dbs_samples

		/* 	Business Logic:
			A pleasant side effect of the SQL command below is that it allows lab to withdraw previously released results.
			All the lab technician has to do is 
				1) go to list of sickle cell worksheets
				2) click "change results" on a worksheet where results have been entered and tie break has been done.
				3) Mark desired result as "INVALID(TEST-AGAIN)"
			schedule_repeat_tests() will add selected samples to this function's 2nd param ($samples_to_retain)

		*/		
		$retain_sql = 	"UPDATE batches 
							SET SCD_results_released = 'NO',
								date_SCD_testing_completed = NULL
							WHERE batches.id IN ( $batches_to_retain ) ";

		$sql = $release_sql . $update_dbs_sql . $retain_sql;

		\DB::unprepared($sql);
	}



	public function getTieBreakResults($worksheet_number)
	{
		$sql = "SELECT sample_id, tie_break_result FROM sc_worksheet_index WHERE worksheet_number = '$worksheet_number'";
		$rows = \DB::select($sql); 
		$tbResults = [];

		foreach ($rows as $r) {
			$sample_id = $r->sample_id;
			$tbResults[ $sample_id ] = $r->tie_break_result;
		}

		return $tbResults;
	}

	public function getSampleIDs($results_data)
	{
		$IDs_as_array = array_keys($results_data);
		$IDs_as_string = implode(", ", $IDs_as_array);

		return $IDs_as_string;
	}

	public function schedule_repeat_tests($worksheet_number)
	{
		$rSQL = "";
		$samples_to_retest = [];
		$samples_without_results = [];
		$tie_break_result = $this->getTieBreakResults($worksheet_number);
		$sampleIDs = $this->getSampleIDs($tie_break_result);
		$batches_to_retain = [];

		$sql = "SELECT 		dbs_samples.id as id, 
							dbs_samples.batch_id as batch_id, 
							count(sample_id) AS nTestsDone 
					FROM 	dbs_samples, sc_worksheet_index 
					WHERE 	dbs_samples.id = sc_worksheet_index.sample_id 
					AND  	sample_id  in ( $sampleIDs ) 
					GROUP BY 	sample_id ";

		$rows = \DB::select($sql); 

		foreach ($rows as $sample) {

			$this_sample = $sample->id;
			$this_batch = $sample->batch_id;

			if($tie_break_result[ $this_sample ] == "LEFT_BLANK"){// do_not_repeat
				$samples_without_results[] = $this_sample;
				$batches_to_retain[ $this_batch ] = $this_batch;
				continue; // skip this sample, it has no results
			}

			$nTestsDone_on_this_sample = $sample->nTestsDone;
			$result_is_inconclusive = false;

			if ($tie_break_result[ $this_sample ] == "INVALID" || 				
			   ($tie_break_result[ $this_sample ] == "SICKLER.TEST_AGAIN") || 				
			   ($tie_break_result[ $this_sample ] == "SICKLER" && $nTestsDone_on_this_sample == 1)){
					
				$result_is_inconclusive = true;
				$batches_to_retain[ $this_batch ] = $this_batch;
			}

			if ($result_is_inconclusive){// test this sample again.

				$rSQL .= "UPDATE dbs_samples SET ready_for_SCD_test = 'YES', repeated_SC_test='YES'  WHERE id = '$this_sample'; ";
				$samples_to_retest[] = $this_sample;			
			}
		}


		if( count($samples_to_retest) > 0){
			\DB::unprepared( $rSQL );
		}

		$samples_to_retain = new \stdClass;
		$samples_to_retain->forRetesting = $samples_to_retest;
		$samples_to_retain->haveNoResults = $samples_without_results;
		$samples_to_retain->batches_to_retain = $batches_to_retain;

		return $samples_to_retain;
	}


	public function update_index($worksheet_number, $result_number, $rcvd_data){

		$sql = "";
		$result = 'result' . $result_number;

		if($result_number == 3){
			$result = 'tie_break_result';
		}


		$results_available= count($rcvd_data);

		$all_results_ready = true;

		foreach ($rcvd_data as $sample_id => $test_result) {

			if($test_result == 'LEFT_BLANK') {
				$all_results_ready = false;
			}

			$sql .= "UPDATE sc_worksheet_index SET $result = '$test_result' 
						WHERE worksheet_number = '$worksheet_number' AND sample_id = '$sample_id';";
		}

		\DB::unprepared($sql);

		return $all_results_ready;
	}

	public function scwsList()
	{
		return view('scwsList');
	}
	

	public function update_worksheet($worksheet_number, $result_number, $all_results_ready){

		if($result_number == 1 || $result_number == 2){
			$this->updateExaminerResults($worksheet_number, $result_number, $all_results_ready);
		}
		
		$this->updateTieBreakResults($worksheet_number, $result_number, $all_results_ready);
		return;
	}

	public function updateExaminerResults($worksheet_number, $result_number, $all_results_ready){
		// see SCManager->showResultsStatus()

		$examiners_results_ready = 'Examiner' . $result_number . '_ResultsReady';
		$yes_or_no = $all_results_ready ? "YES" : "NO";
		
		$sql = "UPDATE sc_worksheets SET $examiners_results_ready = '$yes_or_no' WHERE id = '$worksheet_number'";
		\DB::unprepared($sql);
	}


	public function updateTieBreakResults($worksheet_number, $result_number, $all_results_ready){
		// see SCManager->showResultsStatus()

		if($result_number == 1 || $result_number == 2){
			$request_TieBreaker = $this->update_TieBreaker_in_worksheetIndex($worksheet_number, $result_number);
		}
		else if($result_number == 3){
			$request_TieBreaker = ($all_results_ready == "YES") ? false /* no need for tie breaker */ : true;
		}

		$this->update_TieBreaker_in_worksheet($worksheet_number, $request_TieBreaker);
		return;
	}

	public function update_TieBreaker_in_worksheetIndex($worksheet_number, $result_number)
	{

		$request_TieBreak = $this->manual_tieBreak_needed($worksheet_number) ? true : false;

		if($result_number == 3){
			// should never be reached. Added just to protect the young and the tired coder.
			return $request_TieBreak;
		}

		$sql = "UPDATE sc_worksheet_index set tie_break_result = result2 
					WHERE worksheet_number = '$worksheet_number'
						AND result1 = result2 " ;

		\DB::unprepared($sql);

		return $request_TieBreak;
	}


	public function update_TieBreaker_in_worksheet($worksheet_number, $TieBreaker_Requested)
	{

		if($TieBreaker_Requested)// this means there are no tieBreak results
			$tieBreak_results_ready = "NO";
		else
			$tieBreak_results_ready = "YES";


		$sql = "UPDATE sc_worksheets SET TieBreaker_ResultsReady = '$tieBreak_results_ready' 
					WHERE id = '$worksheet_number'";
		\DB::unprepared($sql);
	}



	public function manual_tieBreak_needed($worksheet_number){ 
		// see SCManager->showResultsStatus()
		
		$both_results_ready = true;
		$both_results_match = true;
		$results_dont_match = !$both_results_match;

		$sql = "SELECT * FROM sc_worksheet_index WHERE worksheet_number = '$worksheet_number'";

		$samples = \DB::select($sql);

		foreach ($samples as $this_sample) {

			if(empty($this_sample->result1) || $this_sample->result1 == "LEFT_BLANK"){
				$both_results_ready = false;
				break;
			}
			
			if(empty($this_sample->result2) || $this_sample->result2 == "LEFT_BLANK"){
				$both_results_ready = false;
				break;
			}

			if($this_sample->result1 != $this_sample->result2){
				$results_dont_match = true;
				break;
			}
		}

		// tie break is only needed if both results are ready, but they don't match.
		$tie_break_needed = ($both_results_ready && $results_dont_match) ? true : false;
		return $tie_break_needed;
	}
}