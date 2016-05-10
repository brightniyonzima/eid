<?php namespace EID\Http\Controllers;
use EID\Models\Worksheet;
use EID\Lib\WorkSheetManager;
class LabController extends Controller {

	public function dummy_ws(){
		
		return view('lab.dummy_ws');
	}
	public function dummy_rs(){
		
		return view('lab.dummy_results');
	}

	public function display_worksheet($id = null, $edit = null, $is_dummy = false, $show_results = false){
		
		$worksheet_id = $id ? array('ws'=>$id) : [];
		$editAble = $edit ? array('edit' => "yes") : [];
		$is_dummy = $is_dummy ? array('dummy' => "yes") : []; 

		$sr = $show_results ? array('show_results' => "yes") : []; 

		return view('lab.worksheet_details', array_merge($worksheet_id, $editAble, $is_dummy, $sr) );
	}

	public function print_backlog()
	{
		return view('backlog');
	}

	protected function delete_worksheet(WorkSheetManager $wm){

		$err = $wm->deleteWorksheet();
		
		if($err) 
			return json_encode($err);
		else 
			return redirect()->route('wlist');
	}

	public function fileAlreadySaved($ws_id)
	{
		$sql = "SELECT count(*) AS file_saved FROM lab_worksheets WHERE id = '$ws_id' AND HasResults = 'YES'";
		$row = \DB::select($sql);

		return $row[0]->file_saved ? true : false;
	}

	public function store_worksheet(){ 
	/* This function only does sanity checks, then calls executeInstruction() to do the actual work*/

		$instruction = \Input::get('i', '');
		$worksheet_id = \Input::get('ws', '');

		$no_instruction = empty($instruction);
		$no_worksheet = empty($worksheet_id);

		if($no_worksheet && $no_instruction){ return "Error: Please select an action or Worksheet or both"; }
		if($no_worksheet && $instruction != "create"){ return "Can't do $instruction: No Worksheet selected"; }
		if($no_instruction) {
			\Flash::error("Y: Unknown Worksheet Action: '$instruction'");
			$instruction = "view"; /* default: we have a worksheet but no instruction */
		}
		if($instruction == "saveFile" && $this->fileAlreadySaved($worksheet_id)){
			return "ERROR: Results for this worksheet ($worksheet_id) have already been uploaded";
		}

		return $this->executeInstruction($instruction, $worksheet_id);
	}

	public function executeInstruction($instruction, $worksheet_id){

// dd($worksheet_id);
// dd($instruction);

		$ws = empty($worksheet_id) ? new Worksheet : Worksheet::findOrFail($worksheet_id);
		$wm = new WorkSheetManager($ws);
		

		if($instruction == "create" || $instruction == "update"){
			return $this->saveWorksheetData($wm);
		}
		elseif($instruction == "view" || $instruction == "del"){
			return $this->display_worksheet($worksheet_id);
		}
		elseif($instruction == "delete") {			
			return $this->delete_worksheet($wm);
		}
		elseif($instruction == "edit"){
			return $this->display_worksheet($worksheet_id, "allow_edits");
		}
		elseif($instruction == "toPDF"){
			return $this->WorksheetToPDF($worksheet_id);
		}
		elseif ($instruction == "uploadFile") {
			return view('lab.uploadResultsCSV', array('ws' => $ws) );
		}
		elseif ($instruction == "saveFile") {
			$r = $this->load_csv_data($wm);

			if(\Request::has('dummy'))
				return \Redirect::route('dummy_results', ['ws_id' => $worksheet_id, 'x'=>\Request::get('dummy')]);
			else {
				// return $this->display_worksheet($worksheet_id, null, false); // commented out by cX in favor of PK code below.
				\Flash::message("Results for Worksheet # $worksheet_id succesfully uploaded");
				$show_results = true;

				return  \Redirect::route('printer2', array('i'=>'view', 'sr'=>'1', 'ws' => $worksheet_id));

				// http://chai.live/ws?i=view&sr=1&ws=100101
				// return $this->display_worksheet($worksheet_id, null, null, $show_results);
			}
		}
		else{

			\Flash::error("X: Unknown Worksheet Action: '$instruction'");
			return redirect()->route('wlist');
		}
	}



	public function list_worksheets(){
		return view('lab.worksheet_list');
	}

	public function setState($t)
	{
		\Session::put('ws_type', $t);
		return "succesfully set state to: $t";
	}


	protected function saveWorksheetData(WorkSheetManager $wm){

		extract(\Input::only('Kit_LotNumber', 'Kit_Number', 'Kit_ExpiryDate'));
		$worksheet_id = $wm->storeWorksheet($Kit_LotNumber, $Kit_Number, $Kit_ExpiryDate);
		
		\Flash::message("Worksheet # $worksheet_id saved succesfully");
		return \Redirect::route('wlist', ["h"=>$worksheet_id]);// success
	}

	protected function WorksheetToPDF($worksheet_id){

		SessionsController::php_session_setBool("makePDF");/* housekeeping: see img#download_pdf in worksheet_details.blade.php */
		
		$view = \View::make('lab.worksheet_details', array('ws'=>$worksheet_id, 'hide_menu'=>"yes") );
		// $view = \View::make('lab.text', array('ws'=>$worksheet_id, 'hide_menu'=>"yes") );
		$contents = $view->renderSections()['content'];

		$pdf = \PDF::loadHTML($contents);

		return $pdf->setOrientation('landscape')->stream("$worksheet_id".".pdf");
	}

	public function load_csv_data(WorkSheetManager $wm){ /* handles CSV data POST-ed to it by upload form */

		// return "---energy---";// return an appropriate view

		$file_input_field = "csv";
		$upload_error = $this->checkUploadedFile( $file_input_field );
// 1

		if($upload_error){
			\Flash::error($upload_error);
			return redirect()->back();
		}

		$wm->uploadResultsCSV( $file_input_field );

	}

	public function  checkUploadedFile($file_field){

		if( ! \Request::hasFile($file_field) ){ return "Upload Error: No File found - please select a file to upload"; }
		
		if( ! \Request::file("csv")->isValid() ){ return "File upload failed"; }

		return null;
	}



	public function generateNewReleaseCode()
	{

	    $len = 4;
	    $num = rand(99, 1000);
	    $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ";
	    $str = substr( str_shuffle( $chars ), 0, $len );

	    return "$str $num";
	}

	public function releaseSCsamples($rc)
	{
		$nSamplesReleased = $this->setReleaseCode($rc);
		$newReleaseCode = $this->generateNewReleaseCode();

		return response()->json(compact("nSamplesReleased", "newReleaseCode"));
	}


	// public function undo_releaseSCsamples($rc)
	// {
	// 	$nSamplesReturned = $this->unsetReleaseCode($rc);
	// 	$newReleaseCode = $this->generateNewReleaseCode();

	// 	return response()->json(compact("nSamplesReturned", "newReleaseCode"));
	// }

	// public function setReleaseCode($rc)
	// {
	// 	$sql = $this->getReleaseSQL("'$rc'", "NULL");
	// 	$nRowsAffected = \DB::update($sql);

	// 	return $nRowsAffected;
	// }


	// public function undo_releaseSCsamples($rc)
	// {
	// 	$nSamplesReturned = $this->unsetReleaseCode($rc);
	// 	$newReleaseCode = $this->generateNewReleaseCode();

	// 	return response()->json(compact("nSamplesReturned", "newReleaseCode"));
	// }

	// public function setReleaseCode($rc)
	// {
	// 	$sql = $this->getReleaseSQL("'$rc'", "NULL");
	// 	$nRowsAffected = \DB::update($sql);

	// 	return $nRowsAffected;
	// }


	public function unsetReleaseCode($rc)
	{

		$sql = $this->getReleaseSQL("NULL", "'$rc'");
		$nRowsAffected = \DB::update($sql);

		return $nRowsAffected;
	}


	public function getReleaseSQL($new_rc, $old_rc)	
	{
		// 	usage:
		// 		setReleaseCode() does like this: getReleaseSQL('XQTZ 123', 'NULL')
		//		unsetReleaseCode() does like this: getReleaseSQL('NULL', 'XQTZ 123')		

		$u = \Auth::user()->id;
		$old_rc = $old_rc == "NULL" ? " is NULL" : " is NOT NULL";
		$ready_for_testing = $new_rc == "NULL" ? "'NO'" : "'YES'";

		$sql = "UPDATE dbs_samples 

					SET 	sickle_cell_release_code = $new_rc,
							ready_for_SCD_test = $ready_for_testing

					WHERE 	in_workSheet = 'NO' 
						AND sample_rejected = 'NO' 
						AND SCD_test_requested = 'YES' 
						AND PCR_test_requested = 'NO' 
						and sample_verified_by = '$u' 
						and sickle_cell_release_code $old_rc";
		return $sql;
	}
/*	
	public function getReleaseSQL($new_rc, $old_rc)	
	{
		// 	usage:
		// 		setReleaseCode() does like this: getReleaseSQL('XQTZ 123', 'NULL')
		//		unsetReleaseCode() does like this: getReleaseSQL('NULL', 'XQTZ 123')		

		$u = \Auth::user()->id;
		$old_rc = $old_rc == "NULL" ? " is NULL" : " is NOT NULL";
		$ready_for_testing = $new_rc == "NULL" ? "'NO'" : "'YES'";

		$sql = "UPDATE dbs_samples 

					SET 	sickle_cell_release_code = $new_rc,
							ready_for_SCD_test = $ready_for_testing

					WHERE 	in_workSheet = 'NO' 
						AND sample_rejected = 'NO' 
						AND SCD_test_requested = 'YES' 
						AND PCR_test_requested = 'NO' 
						and sample_verified_by = '$u' 
						and sickle_cell_release_code $old_rc";
		return $sql;
	}

	public function get_scws_sources()
	{
		// $sql = "SELECT 'APPROVAL DESK' AS source, 
		// 				sickle_cell_release_code AS bagID, 
		// 				count(sickle_cell_release_code ) AS nSamples 

		// 		FROM dbs_samples 
		// 		WHERE sickle_cell_release_code IS NOT NULL 
		// 		GROUP BY sickle_cell_release_code 

		// 	UNION 

		// 		SELECT 'EID Lab' as source, 
		// 				physical_location as bagID, 
		// 				count(id) as nSamples 

		// 		FROM 	dbs_samples 
		// 		WHERE 	ready_for_SCD_test = 'YES' 
		// 		AND 	physical_location  IS NOT NULL 
		// 		GROUP BY physical_location";

		$sql = "SELECT  id, 
						infant_name, 
						infant_exp_id, 
						
						if(sickle_cell_release_code is not null, 'DIRECT', 
							if(physical_location is not null, 'EID Lab', 'unknown_src') ) as source, 
						
						if(sickle_cell_release_code is not null, sickle_cell_release_code, 
							if(physical_location is not null, physical_location, 'unknown_loc') ) as physical_location 

				FROM  	dbs_samples     

				WHERE  	ready_for_SCD_test = 'YES'    
				ORDER BY 	source, physical_location";

// dd($sql);

		$sources = \DB::select($sql);

		return $sources;
	}


	public function make_ws()
	{
		return view('lab.scws_maker');
	}

*/
	
	public function find_sample()
	{
		return view('ws_search');
	}

	public function find_rejected_sample()
	{
		return view('rejects');
	}


	public function dispatch_results()
	{
		return view('lab.dispatch');		
	}





//======================= the code below is concerned with reversing upload =============================================

	public function cancel_CSV_upload($ws)
	{

		// return "reached cancel_CSV_upload( $ws )";

		$su = $this->undoCSV_getSamples($ws);

		$this->undoCSV_exec($ws, $su);

		$w = Worksheet::find($ws);

		if($w == null)
			return "--No-worksheet-found---";
		else
			return view('lab.uploadResultsCSV', array('ws' => $w) );
	}

	public function facility_envelope($batch_id)
	{		
		return view('envelope_facility', array('b' => $batch_id) );
	}

	public function rejected_envelopes($batch_id)
	{		
		return view('rejected_envelopes', array('b' => $batch_id) );
	}
	


	protected function numTestsDone($sample)
	{
		$test_1_result = $sample["test_1_result"];
		$test_2_result = $sample["test_2_result"];
		$test_3_result = $sample["test_3_result"];
		$test_4_result = $sample["test_4_result"];
		$test_5_result = $sample["test_5_result"];

		if($test_1_result == null)
			return 0;
		
		if($test_2_result == null)
			return 1;// the if() above guarantees that $test_1_result is NOT null. Repeat till 5

		if($test_3_result == null)
			return 2;

		if($test_4_result == null)
			return 3;

		if($test_5_result == null)
			return 4;
		else
			return 5;
	}

	protected function getTestResult($this_sample, $n)
	{
		if(empty($this_sample["test_".$n."_result"])) 
			return null;
		else
			return $this_sample["test_".$n."_result"];
	}

	protected function undoCSV_exec($ws, $sample_arr)
	{
		$sql = "SELECT 	infant_name, infant_exp_id, worksheet_number, batch_id, batch_number, sample_id, 
						PCR_test_requested, SCD_test_requested , sample_rejected 

				FROM    dbs_samples, batches, worksheet_index 

				WHERE   dbs_samples.id = worksheet_index.sample_id 
				AND     dbs_samples.batch_id = batches.id 
				AND 	worksheet_index.worksheet_number = '$ws'";

		$data = \DB::select($sql);

		$batches = [];
		$worksheets = [];


		foreach ($data as $this_sample) {
			$batches[ $this_sample->batch_id ] = "doesnt_matter";
			$worksheets[ $this_sample->worksheet_number ] = "doesnt_matter";
		}


		$dbsSQL = "";
		foreach ($sample_arr as $sample_id => $i) {

			$physical_location = $i > 1 ? "" : ", physical_location = NULL ";
			$test_result = "test_" . ($i==0 ? 1 : $i)  . "_result";
			$worksheet = "worksheet_" . ($i==0 ? 1 : $i);

			$dbsSQL .= "UPDATE dbs_samples SET accepted_result = NULL, in_workSheet = 'YES', 
								testing_completed = 'NO',  PCR_results_ReleasedBy = NULL, 
								$test_result = NULL, $worksheet = NULL /* NB: no comma needed here */ 
								$physical_location 
							WHERE id = '$sample_id'; ";
		}

		$bk = array_keys($batches);
		$batch_IDs = implode(", ", $bk);
		$batchSQL = ""; // was: "UPDATE batches SET all_samples_tested = 'NO' WHERE id IN ( $batch_IDs ); ";
						// however, we no longer use all_samples_tested. 
						// Instead we use PCR_results_released and SCD_results_released
						// This is better because it lets us know which lab has finished testing
		

		$wk = array_keys($worksheets);
		$worksheet_IDs = implode(", ", $wk);
		$worksheetSQL = "UPDATE lab_worksheets 
							SET HasResults = 'NO', PassedReview = 'NOT_YET_REVIEWED', ReviewedBy = NULL 
							WHERE id = '$ws'";

		$SQL = $dbsSQL . $batchSQL . $worksheetSQL; 

		\DB::unprepared($SQL);
	}

	public function undoCSV_getSamples($ws)
	{

		$ws = Worksheet::find($ws);
		
		if($ws == null) dd("Worksheet $ws DOES NOT exist");

		$wm = new WorkSheetManager( $ws );


		$samples = $wm->getSamples();
		$undo_samples = [];		
	
		foreach($samples as $this_sample){

			$nTestsDone = $this->numTestsDone($this_sample);
			$result = $this->getTestResult($this_sample, $nTestsDone);

			$axnNo = $this_sample["accession_number"];
			$undo_samples[$axnNo] = $nTestsDone;
		}

		return $undo_samples;

	}


	// public function load_csv_data(WorkSheetManager $wm){ /* handles CSV data POST-ed to it by upload form */

	// 	// return "---energy---";// return an appropriate view

	// 	$file_input_field = "csv";
	// 	$upload_error = $this->checkUploadedFile( $file_input_field );


	// 	if($upload_error){
	// 		\Flash::error($upload_error);
	// 		return redirect()->back();
	// 	}

	// 	$wm->uploadResultsCSV( $file_input_field );

	// }

	// public function  checkUploadedFile($file_field){

	// 	if( ! \Request::hasFile($file_field) ){ return "Upload Error: No File found - please select a file to upload"; }
		
	// 	if( ! \Request::file("csv")->isValid() ){ return "File upload failed"; }

	// 	return null;
	// }



	// public function generateNewReleaseCode()
	// {

	//     $len = 4;
	//     $num = rand(99, 1000);
	//     $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ";
	//     $str = substr( str_shuffle( $chars ), 0, $len );

	//     return "$str $num";
	// }

	// public function releaseSCsamples($rc)
	// {
	// 	$nSamplesReleased = $this->setReleaseCode($rc);
	// 	$newReleaseCode = $this->generateNewReleaseCode();

	// 	return response()->json(compact("nSamplesReleased", "newReleaseCode"));
	// }


	public function undo_releaseSCsamples($rc)
	{
		$nSamplesReturned = $this->unsetReleaseCode($rc);
		$newReleaseCode = $this->generateNewReleaseCode();

		return response()->json(compact("nSamplesReturned", "newReleaseCode"));
	}

	public function setReleaseCode($rc)
	{
		$sql = $this->getReleaseSQL("'$rc'", "NULL");
		$nRowsAffected = \DB::update($sql);

		return $nRowsAffected;
	}

	// public function unsetReleaseCode($rc)
	// {

	// 	$sql = $this->getReleaseSQL("NULL", "'$rc'");
	// 	$nRowsAffected = \DB::update($sql);

	// 	return $nRowsAffected;
	// }

	public function get_scws_sources()
	{
		$sql = "SELECT  
						DISTINCT
						dbs_samples.id, 
						infant_name, 
						infant_exp_id, 
						envelope_number,
						worksheet_number,
						
						if(repeated_SC_test = 'YES', 'REPEATS', 
							if(sickle_cell_release_code is not null, 'DIRECT', 
								if(physical_location is not null, CONCAT(' ', 'EID Lab'), 'unknown_src'))) as source, 

				/* this is easier to understand if you read from the last condition backwards to the first condition */
						if(repeated_SC_test = 'YES', coalesce(worksheet_number, physical_location) /* src = SC Lab (its a REPEAT) */, 						
							if(sickle_cell_release_code is not null /* src = DIRECT (SC test only, EID not requested) */, envelope_number, 
								if(physical_location is not null/* src = EID Lab */, physical_location, 'unknown_loc'))) as physical_location 

				FROM  	dbs_samples, batches, sc_worksheet_index

				WHERE  	ready_for_SCD_test = 'YES' 

				AND 	(
							(batches.id = dbs_samples.batch_id AND dbs_samples.id = sc_worksheet_index.sample_id) 

							OR 

							physical_location is NOT NULL
						) 

	

				ORDER BY 	source DESC, 
							physical_location ASC, 
							worksheet_number ASC";/* 	Lab workers prefer samples grouped as follows:
														REPEATS first, then DIRECT, and finally samples from EID LAB 

														Padding tricks database returning results that are already 
														sorted correctly i.e. (REPEATS, DIRECT, EID LAB).

														Of course, this means u have to trim(source) before display.
													*/

		$sql = "SELECT  distinct dbs_samples.id, infant_name, infant_exp_id, envelope_number, worksheet_number, 
				if(repeated_SC_test = 'YES', 'REPEATS', if(sickle_cell_release_code is not null, 'DIRECT', 
					if(physical_location is not null, CONCAT(' ', 'EID Lab'), 'unknown_src'))) as source, 

					if(repeated_SC_test = 'YES', coalesce(worksheet_number, physical_location) , 
						if(sickle_cell_release_code is not null, envelope_number, 
							if(physical_location is not null, physical_location, 'unknown_loc'))) as physical_location 

				FROM  	dbs_samples 

				JOIN batches on batches.id = dbs_samples.batch_id 
				LEFT JOIN sc_worksheet_index on dbs_samples.id = sc_worksheet_index.sample_id 

				WHERE  	ready_for_SCD_test = 'YES' 

				ORDER BY 	source DESC, physical_location ASC, worksheet_number ASC";

// dd($sql);
		
		$db_rows = \DB::select($sql);

// dd($db_rows);

		/* if a sample needs to be tested more than twice, the SQL above may cause sampleIDs to be repeated. So remove duplicates */
		$sources = $this->remove_duplicates_sc_samples($db_rows); 

		return $sources;
	}

	public function remove_duplicates_sc_samples($rows)
	{
		$sc_samples = [];

		foreach ($rows as $sample) {
			$id = $sample->id;
			$sc_samples[$id] = $sample;// this ensures no repeats
		}

		return $sc_samples;
	}

	public function make_ws()
	{
		return view('lab.scws_maker');
	}

}
