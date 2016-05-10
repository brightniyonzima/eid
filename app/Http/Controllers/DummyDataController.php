<?php 	namespace EID\Http\Controllers;

use EID\Models\Worksheet;
use EID\Lib\WorkSheetManager;
use Faker\Factory as Faker;


class DummyDataController extends Controller {

	private $faker;
	private $worksheet_manager;
	private $test_results = [];


	public function dummy_ws($ws_id){
		return view('lab.dummy_ws', ["ws_id"=>$ws_id]);
	}

	public function dummy_rs($ws_id){
		return view('lab.dummy_results', ["ws_id"=>$ws_id]);
	}

	public function storeDummyResults($ws_id, $json_data)// for use during repeat tests, if any
	{

		$sql_data = "";
		$sql = "REPLACE INTO pcr_dummy_results 
					(
						sample_id, 
						worksheet_number, 
						
						expected_test_1_result, 
						expected_test_2_result, 
						expected_test_3_result, 
						
						expected_final_result 
					)
				VALUES ";

		$data = json_decode($json_data);
		
		foreach ($data as $sample_id => $test_results) {

			$t1 = empty($test_results[0]) ? "NULL" : "'$test_results[0]'";
			$t2 = empty($test_results[1]) ? "NULL" : "'$test_results[1]'";
			$t3 = empty($test_results[2]) ? "NULL" : "'$test_results[2]'";

			$final = $test_results[3];// cant be empty.


			$sql_data .= " \n\t\t('$sample_id', '$ws_id',	$t1, $t2, $t3, '$final'	) ";
			$sql_data .= " ,";
		}
		
		$sql_data = rtrim($sql_data, ",");

		$sql = $sql . "\n" . $sql_data . "";

		\DB::unprepared($sql);

		return $sql;
	}

	public function exportCSV($ws_id)
	{

		$ws = Worksheet::findOrFail($ws_id);
		$this->worksheet_manager = new WorkSheetManager( $ws );
		$tn = 0; // was \Request::get('x');, but is now ignored (but plz confirm!)

		$this->assignDummyResults( $ws_id , $tn);		
		$this->exportDummyResultsCSV($ws_id);
	}

	public function makeDummyCSV($ws_id, $json_data){

		if(\Request::has('t')){// trigger creation of CSV
			$this->storeDummyResults($ws_id, $json_data);
			return ; // stop
		}

		if(\Request::has('x')){// export the CSV (download it)

			$this->exportCSV($ws_id);
			return;
		}

		return "makeDummyCSV: -- u shd'nt be able to get here if you crossed your x and t correctly --";
	}	

	public function assignDummyResults( $ws_id , $tn = null)
	{
		$db_samples = $this->getDummyResults($ws_id);
		$ws_samples = $this->worksheet_manager->getSamples();
// dd($ws_samples);

		foreach ($ws_samples as $ws_sample_id => $ws_sample) {

			if (array_key_exists($ws_sample_id, $db_samples)) {// i.e. sample_id from DB === sample_id from WS

				$n = $ws_sample["nTestsDone"] + 1;
				$expected_result = "expected_test_" . $n ."_result";

				$this_result = $db_samples[$ws_sample_id][$expected_result];
			// $this_result = 'POSITIVE';// TESTING ONLY: THIS DISPLAYS AN ERROR WHICH I WANT TO EXAMINE. PLEASE DELETE.
				$this->save_test_results($ws_sample_id, $this_result);

			}else{
				$this->save_test_results($ws_sample_id, 'NEGATIVE');
			}
		}
	}


	public function exportDummyResultsCSV($output_file_name, $data='')
	{
		if(empty($data)) {
			$data = $this->test_results;
		}

		\Excel::create($output_file_name, function($excel) use($data) {
		    $excel->sheet('Sheetname', function($sheet) use($data) {
		        $sheet->fromArray($data);
		    });
		})->export('csv');
	}


	public function dummyRocheRFI($this_result)
	{
		if($this_result === NEGATIVE) return $this->faker->randomFloat(3, 1, 2);
		if($this_result === POSITIVE) return $this->faker->randomFloat(2, 6, 10);
		if($this_result === LOW_POSITIVE) return $this->faker->randomFloat(2, 2, 4);
		if($this_result === FAIL) return $this->faker->randomFloat(2, 20, 40);
	}

	public function dummyRocheElbow($this_result)
	{
		if($this_result === NEGATIVE) return "";
		if($this_result === POSITIVE) return "24.7";
		if($this_result === LOW_POSITIVE) return "33.5";
		if($this_result === FAIL) return $this->faker->randomFloat(2, 20, 40);
	}

	public function getDummyResults($ws_id)
	{
		
		// $sql = "SELECT 	pcr_dummy_results.*, accepted_result  FROM pcr_dummy_results, dbs_samples 
		// 		WHERE 	worksheet_number = '$ws_id' 
		// 		AND 	dbs_samples.id = pcr_dummy_results.sample_id 
		// 		AND 	accepted_result IS NULL ";

		$sql = "SELECT *  FROM pcr_dummy_results WHERE 	worksheet_number = '$ws_id'"; 

		$db_rows = \DB::select($sql);

		$dummy_results = [];

		foreach ($db_rows as $db_sample) {
			$sample_id = ($db_sample->sample_id);
			$dummy_results[$sample_id] = (array) $db_sample;
		}

		return $dummy_results;
	}

	public function save_test_results($sample_id, $this_result){
		
		$nCols = 90;
		$this->startFaker();

		for ($i=0; $i < $nCols; $i++){
			$csv_column[$i] = "xx";
		}

		$csv_column[3] = date('Y-m-d'); // today
		$csv_column[4] = $sample_id;
		$csv_column[8] = $this->fmtResult( $this_result );

		$csv_column[60] = $this->dummyRocheElbow( $this_result );
		$csv_column[64] = $this->dummyRocheRFI( $this_result );

		$this->test_results[] = $csv_column; /* add these columns to the results buffer */

		return $csv_column;
	}

	public function fmtResult($the_result){

		switch ($the_result) {
			case LOW_POSITIVE: 	return "Detected DBS";
			case POSITIVE: 	return "Detected DBS";
			case NEGATIVE: 	return "Not Detected DBS";			
			default: 	return "INVALID"; // test failed
		}
	}

	public function make_next_ws($prev_ws_id)
	{
		$prev_ws = Worksheet::findOrFail($prev_ws_id);

		$KitNum = $prev_ws->Kit_Number;
		$LotNum = $prev_ws->Kit_LotNumber;
		$ExpDate= $prev_ws->Kit_ExpiryDate;

		$wm = new WorkSheetManager( new Worksheet );
		$ws = $wm->createWorksheet($LotNum, $KitNum, $ExpDate);

		// use either this:
		// $this->exportCSV($ws);

		// or this:
		$this->update_dummy_results($prev_ws_id, $ws);

		$x = \Input::get('x') ?: 'z';

		return \Redirect::route("printer2", ["i"=>"view", "ws"=>"$ws", "dummy"=>$x]);// goal is something like /ws?i=view&ws=105000&dummy=yes#
	}

	public function update_dummy_results($old_ws_id, $new_ws_id)
	{

		$repeats = $this->getRepeatSamples($old_ws_id, $new_ws_id);
		$new_samples = $this->getNewSamples($old_ws_id, $new_ws_id);

		$SQL = $repeats . " ; " . $new_samples;
// dd($SQL);
		\DB::unprepared($SQL);
	}


	public function getNewSamples($old_ws_id, $new_ws_id)
	{
		$sql = "REPLACE INTO pcr_dummy_results 
						(sample_id, worksheet_number, nTestsDone,
							expected_test_1_result, expected_final_result ) 
					
					SELECT sample_id, '$new_ws_id', '0', 'NEGATIVE', 'NEGATIVE' 

					FROM worksheet_index where worksheet_number = '$new_ws_id' 

					AND sample_id NOT IN ( select sample_id  from worksheet_index where worksheet_number = '$old_ws_id')";

		return $sql;
	}
	
	public function getRepeatSamples($old_ws_id, $new_ws_id)
	{
		$sql = "REPLACE INTO pcr_dummy_results 
					(sample_id, worksheet_number, nTestsDone,
						expected_test_1_result, expected_test_2_result, expected_test_3_result, 
							expected_final_result ) 
					SELECT sample_id, '$new_ws_id', count(sample_id),
							expected_test_1_result, expected_test_2_result, expected_test_3_result, 
								expected_final_result 
					
					FROM pcr_dummy_results where worksheet_number = '$old_ws_id' 
					
					AND sample_id IN ( select sample_id  from worksheet_index where worksheet_number = '$new_ws_id' ) 

					GROUP BY sample_id";

// mysql> select sample_id, count(sample_id) from worksheet_index where worksheet_number <> '105002' group by sample_id;

		return $sql;
	}

	public function startFaker(){
		$this->faker = Faker::create();
	}
}

/**
this query gets all results for all completed tests.
mysql> SELECT id, test_1_result as actual_1, expected_test_1_result as target_1, test_2_result as actual_2, expected_test_2_result as target_2, test_3_result as a3, expected_test_3_result as x3, if(accepted_result is not null, 'YES', 'NO') as done, expected_final_result as xfinal, accepted_result as aFinal FROM dbs_samples, pcr_dummy_results WHERE pcr_dummy_results.sample_id = dbs_samples.id;

**/