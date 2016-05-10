<?php

define('HIV_NEGATIVE', 'NEGATIVE');
define('HIV_POSITIVE', 'POSITIVE');
// define('LOW_POSITIVE', 'POSITIVE');
define('FAILED', 'INVALID');

use Faker\Factory as Faker;
use Illuminate\Database\Seeder as Seeder;
use Illuminate\Database\Eloquent\Model;
use EID\Models\Worksheet;
/* 
	This class is for testing only. 
	It tests our implementation of the algorithm in CPHL-test-results-interpreter.jpg
*/
class WorksheetRunner extends Seeder{

	private $faker;
	private $patients = [];
	private $worksheet_manager;
	private $results_file;

	private $nWorksheetRuns = 0;
	private $test_results = [];

	public function run(){

		if($this->nWorksheetRuns > 4)
			die("This WorksheetRunner can not do more than 4 runs");


		DB::unprepared("ALTER TABLE lab_worksheets AUTO_INCREMENT = 200200 ");

		$this->startFaker();// This starts the Faker engine which is used to generate test data

	// test run 1
		$this->prepareWorksheet();// sets $this->worksheet
		$this->runWorksheet(); // sets $this->results_file (which points to a CSV file with the results)
		$this->uploadResults();	// calls WorksheetManager::parseUploadedFile()


	// // test run 2
	// 	$this->prepareWorksheet();// sets $this->worksheet
	// 	$this->runWorksheet(); // sets $this->results_file (which points to a CSV file with the results)
	// 	$this->uploadResults();	// calls WorksheetManager::parseUploadedFile()

	// // test run 3
	// 	$this->prepareWorksheet();// sets $this->worksheet
	// 	$this->runWorksheet(); // sets $this->results_file (which points to a CSV file with the results)
	// 	$this->uploadResults();	// calls WorksheetManager::parseUploadedFile()

	// // test run 4
	// 	$this->prepareWorksheet();// sets $this->worksheet
	// 	$this->runWorksheet(); // sets $this->results_file (which points to a CSV file with the results)
	// 	$this->uploadResults();	// calls WorksheetManager::parseUploadedFile()
	}

	public function prepareWorksheet(){

		$this->worksheet_manager = new WorkSheetManager( new Worksheet);
		$this->initializeWorksheet();
	}

	public function runWorksheet(){	

		$this->assignDummyResults();
		$this->createResultsFile();
		$this->nWorksheetRuns++;

		return $this->results_file;
	}

	public function uploadResults(){

		$sql = $this->worksheet_manager->parseUploadedFile( $this->results_file );
		DB::unprepared($sql);
	}


	public function createResultsFile(){

		static $a = 1;

		$result = $this->test_results;
		$csv_file = "/tmp/" . $this->worksheet_manager->getWorkSheetID() . ".csv";
		
		$fp = fopen($csv_file, 'w+');

		foreach ($result as $line){	
			fputcsv($fp, $line); 
		}

		$this->results_file = $csv_file;

		return $fp;
	}


		public function runTestsOnWorksheet(){

			$this_test = $this->nWorksheetRuns;
			$this->test_results = [];// prepare for new set of results

			$worksheet_samples = $this->worksheet_manager->getSamples();
			$sample_IDs = array_keys($worksheet_samples);
			$nSamples = count($sample_IDs);

			for($i=0; $i < $nSamples; $i++){

				$this_sample = $sample_IDs[$i];
				$patient_id = $worksheet_samples[$this_sample]["infant_exp_id"];

				$preset_result_exists = isset($this->patients[$patient_id][$this_test]) ? true : false;
				$this_result = $preset_result_exists? $this->patients[$patient_id][$this_test] : HIV_NEGATIVE;

				$this->save_test_results($this_sample, $this_result);
			}
		}	

	public function assignDummyResults($testNum = 1) // replaces runTestsOnWorksheet()
	{
		$dummy_results = $this->getDummyResults();
		$db_col = 'test_' . $testNum . '_result';

		foreach ($dummy_results as $result){
						
			$this_sample = $result['sample_id'];
			$this_result = $result['test_1_result'];			

			$this->save_test_results($this_sample, $this_result);
		}
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

	public function getDummyResults()
	{
		$ws_id = $this->worksheet_manager->getWorkSheetID();
		
		$sql = "SELECT * FROM pcr_dummy_results WHERE worksheet_number = '$ws_id'";

		$dummy_results = \DB::select($sql);

		return $dummy_results;
	}

	public function save_test_results($sample_id, $this_result){
		
		$nCols = 90;

		for ($i=0; $i < $nCols; $i++){
			$csv_column[$i] = "xx";
		}

		$csv_column[3] = date('Y-m-d'); // today
		$csv_column[4] = $sample_id;
		$csv_column[8] = $this->fmtResult( $this_result );

		$csv_column[60] = $this->dummyRocheElbow( $this_result );
		$csv_column[64] = $this->dummyRocheRFI( $this_result );

		$this->test_results[] = $csv_column; /* add these columns to the results buffer */
	}

	public function fmtResult($the_result){

		switch ($the_result) {
			case LOW_POSITIVE: 	return "Detected DBS";
			case HIV_POSITIVE: 	return "Detected DBS";
			case HIV_NEGATIVE: 	return "Not Detected DBS";			
			default: 	return "INVALID"; // test failed
		}
	}

	public function initializeWorksheet()
	{


		$Kit_LotNumber = $this->faker->word(4) . $this->faker->randomNumber(5);
		$Kit_Number = $this->faker->word(4) . $this->faker->randomNumber(5);
		$Kit_ExpiryDate = $this->faker->dateTimeBetween('now', '+3 years');

		$id = $this->worksheet_manager->createWorksheet($Kit_LotNumber, $Kit_Number, $Kit_ExpiryDate);
		
		if( ! is_numeric($id) )	
			dd($id);
	}

	public function startFaker($value=''){

		$this->faker = Faker::create();
	}
}