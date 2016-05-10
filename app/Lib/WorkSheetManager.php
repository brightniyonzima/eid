<?php namespace EID\Lib;

use EID\Models\Worksheet;

// test results:
define('FAIL', 'FAIL');/* test was inconclusive */
define('NEGATIVE', 'NEGATIVE');
define('POSITIVE', 'POSITIVE');
define('LOW_POSITIVE', 'LOW_POSITIVE');
define('INVALID', 'INVALID');

define('DEFAULT_ID', '-1');
define('DEFAULT_USER', '1');// remove this as soon as user-login module is complete
define('SAMPLES_PER_WORKSHEET', '22');// should be 22;
define('BLOOD_SAMPLES_AVAILABLE', 5);// DBS samples per infant

define('TEST_NOT_NEEDED', 'TEST_NOT_NEEDED');


class WorkSheetManager{	// can manage a new or existing worksheet

	private $worksheet = null;
	private $current_user = DEFAULT_USER;

	private $samples = [];
	private $sampleIDs = "-2";
	private $nSamples_expected = 0;

	private $db_rows = null;


	public function __construct(WorkSheet $worksheet_model, $nSamples = SAMPLES_PER_WORKSHEET) {
				
		$this->selfInitialize($worksheet_model, $nSamples);
	}

	protected function selfInitialize($worksheet_model, $nSamples){

		$this->worksheet = $worksheet_model;
		$this->db_rows = $this->selectSamples($this->worksheet, $nSamples);
		$this->current_user = \Auth::check() ? \Auth::user()->id : DEFAULT_USER;// in production, we should die() if no logged in user

		$this->nSamples_expected = 0;
		$this->sampleIDs = DEFAULT_ID;
		$this->sampleIDsArr=array();

		$i = 0;

		foreach ($this->db_rows as $sample) {
			
			$i++;
			$sample_id = $sample->accession_number;

			$this->samples[$sample_id]["pcr"] = $sample->pcr;
			$this->samples[$sample_id]["envelope_number"] = $sample->envelope_number;
			$this->samples[$sample_id]["batch_number"] = $sample->batch_number;
			$this->samples[$sample_id]["infant_name"] = $sample->infant_name;
			$this->samples[$sample_id]["infant_exp_id"] = $sample->infant_exp_id;
			$this->samples[$sample_id]["accession_number"] = $sample_id;

			$this->samples[$sample_id]["worksheet_1"] = $sample->worksheet_1 ?: null;
			$this->samples[$sample_id]["worksheet_2"] = $sample->worksheet_2 ?: null;
			$this->samples[$sample_id]["worksheet_3"] = $sample->worksheet_3 ?: null;
			$this->samples[$sample_id]["worksheet_4"] = $sample->worksheet_4 ?: null;
			$this->samples[$sample_id]["worksheet_5"] = $sample->worksheet_5 ?: null;

			$this->samples[$sample_id]["test_1_result"] = $sample->test_1_result ?: null;
			$this->samples[$sample_id]["test_2_result"] = $sample->test_2_result ?: null;
			$this->samples[$sample_id]["test_3_result"] = $sample->test_3_result ?: null;
			$this->samples[$sample_id]["test_4_result"] = $sample->test_4_result ?: null;
			$this->samples[$sample_id]["test_5_result"] = $sample->test_5_result ?: null;

			$this->samples[$sample_id]["in_workSheet"] = $sample->in_workSheet ?: 'NO';
			$this->samples[$sample_id]["pos_in_workSheet"] = $i;
			$this->samples[$sample_id]["nWorksheets"] = 0;// will soon be updated by getAllRelatedWorksheets()			
			$this->samples[$sample_id]["testing_completed"] = $sample->testing_completed ?: 'NO';
			$this->samples[$sample_id]["accepted_result"] = $sample->accepted_result ?: null;
			$this->samples[$sample_id]["current_test_id"] = $this->generateTestID($sample);

			$this->samples[$sample_id]["SCD_test_requested"] = $sample->SCD_test_requested;
			$this->samples[$sample_id]["ready_for_SCD_test"] = $sample->ready_for_SCD_test;
			$this->samples[$sample_id]["physical_location"] = $sample->physical_location;
			
			
			$this->samples[$sample_id]["nTestsDone"] = $sample->nTestsDone;

			$this->sampleIDs .= ", '$sample_id'";
			$this->sampleIDsArr[] = $sample_id;
			$this->nSamples_expected++;			

			// echo "<b style='color:red'>$i</b><br>";
		}
		
		// dd($this->samples);

		if($this->worksheet->exists) 
			$this->getAllRelatedWorksheets();

		return true;
	}

	public function getAllRelatedWorksheets()
	{
		$list_of_samples = $this->sampleIDs;

		$sql = "SELECT sample_id, count(sample_id) as nWorksheets 
					FROM worksheet_index 
						WHERE sample_id IN ($list_of_samples)  
							GROUP BY (sample_id)";

		$db_rows = \DB::select($sql);

		foreach ($db_rows as $row) {

			$sample_id = $row->sample_id;
			$nWorksheets = $row->nWorksheets;

			$this->samples[$sample_id]["nWorksheets"] = $nWorksheets;
		}

		$this->update_SCD_test_readiness();
	}

	public function generateTestID($sample){// cx: testable
		
		$doAnotherTest = $sample->testing_completed == 'YES' ? 0 : 1;
		$nPreviousTestsDone = $sample->nTestsDone;
		$current_test_no = $nPreviousTestsDone + $doAnotherTest;
		$current_test_id = $sample->accession_number . "/" . ($current_test_no);
		
		if($current_test_no > 1){
			$current_test_id .= "[RPT]";
		}

		return $current_test_id;
	}

	public function getSamples(){
		return $this->samples;
	}

	public function getSample($sample_id)
	{
		if(array_key_exists($sample_id, $this->samples))
			return $this->samples[$sample_id];
		else
			throw new \Exception("WorksheetManager->getSample(): sample_id $sample_id does NOT exist", 1);
	}


	public function updateSample($sample_id, $new_data)
	{
		if( ! is_array($new_data))
			throw new \Exception("WorksheetManager->updateSample() expected 2nd parameter to be an array", 1);
		
		$old_sample = $this->getSample($sample_id);
		$new_sample = array_merge($old_sample, $new_data);
		$this->samples[$sample_id] = $new_sample;

		return $new_sample;
	}


	public function getWorkSheetID(){ // cx: testable
		
		if($this->worksheet->exists)
			return $this->worksheet->id;
		else
			return 0;
	}

	private function destroyWorksheetIndex(){// cx: testable

		$wNo = 	$this->quote($this->worksheet->id);

		$sql = 	"DELETE FROM worksheet_index WHERE worksheet_number = $wNo";
		
		return $sql;
	}


	private function createWorksheetIndex(){// cx: testable
		
		$sql = "INSERT INTO worksheet_index (worksheet_number, sample_id, pos_in_workSheet) VALUES  ";
		
		foreach ($this->sampleIDsArr as $sample_id) {
			$pos = $this->samples[$sample_id]["pos_in_workSheet"];
			$sql.="('".$this->worksheet->id."', '$sample_id', '$pos'),";
		}
		
		return rtrim($sql, ",");
	}


	public function getAttachSQL(){

		$dbs_sql =	"UPDATE dbs_samples  SET in_workSheet = 'YES' " . 
						"WHERE 	id  IN (" . $this->sampleIDs . ") " . 
						"AND  in_workSheet = 'NO'";

		$index_sql = $this->createWorksheetIndex();
		
		$SQL = $dbs_sql . " ;\n " . $index_sql;

		return $SQL;
	}


	public function getDetachSQL($IDs_to_detach = null){	

		$samples_to_detach = empty($IDs_to_detach) ? $this->sampleIDs :  $IDs_to_detach;

		$dbs_sql =	"UPDATE dbs_samples SET	in_workSheet = 'NO' " . 
					"WHERE 	id  IN (" . $samples_to_detach . ") " .
					"AND	in_workSheet = 'YES'";

		$index_sql = $this->destroyWorksheetIndex();

		$SQL = $dbs_sql . " ; " . $index_sql;

		return $SQL;
	}

	private function attachSamples($check_samples_before_attach = true){
	
		if($check_samples_before_attach && $this->SelectedSamples_StillAvailable() == false){			
			return 0;// selected samples were attached to another worksheet
		}

		$SQL = $this->getAttachSQL();

		$this->db_execute( $SQL );
		$this->getAllRelatedWorksheets();

		return $this->nSamples_expected; // success
	}



	public function update_SCD_test_readiness()
	{
		$list_of_IDs = "-1";

		foreach ($this->sampleIDsArr as $key => $sample_id) {

			$dbs = $this->get_SCD_testReadiness($sample_id, $this->get_first_valid_result($sample_id));
			$this->samples[$sample_id]["SCD_test_requested"] = $dbs["SCD_test_requested"];
			$this->samples[$sample_id]["ready_for_SCD_test"] = $dbs["ready_for_SCD_test"];

			if($dbs["ready_for_SCD_test"] == "YES")	$list_of_IDs .= ", $sample_id";
		}

		$sql = "UPDATE dbs_samples SET ready_for_SCD_test = 'YES' WHERE id IN ($list_of_IDs)";
		\DB::unprepared($sql);
	}
	

	public function deleteWorksheet(){

		$detach_err = $this->detachSamples();

		if ($detach_err) { 

			$detach_err["msg"] = "Can't Delete Worksheet: " . $detach_err["msg"];
			return $detach_err;	/* failed: samples could not be detached */
		}

		$this->worksheet->delete();
		return null;// success
	}







	public function createWorksheet($Kit_LotNumber, $Kit_Number, $Kit_ExpiryDate){ /* only works for new worksheets */
	//
	// 	Tests:
	//	0) 	$wm = new WorkSheetManager( new Worksheet ); 
	// 		$ws = $wm->createWorksheet($LotNum, $KitNum, $ExpDate);
	//		$xx = $wm->updateWorksheet($Kit_Number, $Kit_LotNumber, $Kit_ExpiryDate)
	//
	//	1) if ws exists, return error
	//	2) if all ok, return $this->worksheet->id
	//  3) 
		if($this->worksheet->exists){

			$err = array();
			$err["code"] = 1;
			$err["msg"] = "Failed To Create Worksheet #" . $this->worksheet->id . " because it already exists";

			return json_encode($err);
		}


		if( $this->SelectedSamples_StillAvailable() == false ){// there are no samples we can add to this worksheet

			$err = array();
			$err["code"] = 2;
			$err["msg"] = "Failed To Create Worksheet: Samples already used by someone else - Please make a new worksheet";

			return json_encode($err);
		}
		
		$this->worksheet->CreatedBy = $this->current_user;

		$this->worksheet->DateCreated = date('Y-m-d');
		$this->worksheet->Kit_Number = $Kit_Number;
		$this->worksheet->Kit_LotNumber = $Kit_LotNumber;
		$this->worksheet->Kit_ExpiryDate = $Kit_ExpiryDate;


		$this->worksheet->save();
			
		$this->attachSamples($this->worksheet->id, $CHECK_SAMPLES_AGAIN=false);

		return $this->worksheet->id;
	}



	public function updateWorksheet($Kit_Number, $Kit_LotNumber, $Kit_ExpiryDate){
		/* only works for existing worksheets. Does not affect the samples. */

		if( ! $this->worksheet->exists ){
			return false;
		}

		$this->worksheet->CreatedBy = $this->current_user;

		$this->worksheet->Kit_Number = $Kit_Number;
		$this->worksheet->Kit_LotNumber = $Kit_LotNumber;
		$this->worksheet->Kit_ExpiryDate = $Kit_ExpiryDate;

		$this->worksheet->save();
		return $this->worksheet->id;
	}

	public function storeWorksheet($Kit_Number, $Kit_LotNumber, $Kit_ExpiryDate){

		if( $this->worksheet->exists ){			
			return $this->updateWorksheet($Kit_Number, $Kit_LotNumber, $Kit_ExpiryDate);
		}else{
			return $this->createWorksheet($Kit_Number, $Kit_LotNumber, $Kit_ExpiryDate);
		}
	}



	public function selectSamples($this_worksheet=null, $nSamples=SAMPLES_PER_WORKSHEET){

		$this_worksheet = $this_worksheet ?: $this->worksheet;

		if($this_worksheet->exists){
			$SQL = $this->selectSamplesFromExistingWorkSheet($this_worksheet->id);
		}
		else{
			$SQL = $this->selectSamplesForNewWorksheet($nSamples);
		}

		$this->db_rows = \DB::select( $SQL );

		return $this->db_rows;
	}

	public function generateSQL($nSamples = null, $worksheet_number = null){

		$is_new_worksheet = empty( $worksheet_number) ? true : false;		
		$nSamples = $nSamples ?: SAMPLES_PER_WORKSHEET;
		$sample_position = "";

		if($is_new_worksheet){
			\DB::select( "SELECT @i := 0;" );
			$sample_position = " @i := @i+1 as pos, ";
		}

		$select_clause = 	"SELECT $sample_position
									envelope_number, batch_number, pcr, testing_completed,
									SCD_test_requested, ready_for_SCD_test, physical_location,
									dbs_samples.id as accession_number, infant_name, infant_exp_id, in_workSheet,
									worksheet_1, worksheet_2, worksheet_3, worksheet_4, worksheet_5, accepted_result,
									test_1_result, test_2_result, test_3_result, test_4_result, test_5_result,
									((test_1_result is not null) +
									 (test_2_result is not null) + 
									 (test_3_result is not null) + 
									 (test_4_result is not null) + 
									 (test_5_result is not null) ) AS nTestsDone ";
										

		if( $is_new_worksheet ){// this is a new worksheet
		
			$from_clause = 		"FROM 	batches, dbs_samples ";
	
			$where_clause = 	"WHERE 	dbs_samples.in_workSheet = 'NO'
									AND 	dbs_samples.PCR_test_requested = 'YES'
									AND 	dbs_samples.testing_completed = 'NO'
									AND 	dbs_samples.sample_rejected = 'NO'
									AND		batches.id = dbs_samples.batch_id ";

			$order_by_clause =	" ORDER BY nTestsDone DESC, date_entered_in_DB ASC, envelope_number ASC, batch_id ASC, pos_in_batch ASC LIMIT $nSamples";
		
		}
		else{// this is an existing worksheet
			
			$select_clause = $select_clause . ", worksheet_index.pos_in_workSheet ";

			$from_clause = 	" FROM 	batches, dbs_samples, worksheet_index ";

			$where_clause = " WHERE worksheet_index.worksheet_number = '$worksheet_number' 
								AND worksheet_index.sample_id = dbs_samples.id 
								AND dbs_samples.batch_id = batches.id ";

			$order_by_clause =	" ORDER BY worksheet_index.pos_in_workSheet ";
		}

		$SQL = $select_clause . " " . $from_clause . " " . $where_clause . " " . $order_by_clause;
	
		return $SQL;
	}


	public function selectSamplesFromExistingWorkSheet($worksheet_number){

		$SQL = $this->generateSQL(null, $worksheet_number);

		return $SQL;
	}

	public function selectSamplesForNewWorksheet($nSamples){


		$SQL = $this->generateSQL($nSamples, null);

		return $SQL;
	}


	public function SelectedSamples_StillAvailable(){
	/*	
		In a multi-user situation, all users who call selectSamples() may get the same samples.
		In such cases, only the first user to call attachSamples() will be able to use the samples.
		All the other users should call selectSamples() again to get a fresh set of samples for the worksheet.
	*/

		if($this->nSamples_expected == 0){
			return false;// no samples available
		}

		$IDs = $this->getSampleIDs();

		$SQL = "SELECT 	count(*) AS nSamples_available 
				FROM 	dbs_samples 
				WHERE 	id IN ( $IDs )	/* check only the samples we intend to use */
				AND 	in_workSheet = 'NO'";	/* check that no one else has used any of the samples */

		$db_reply = \DB::select( $SQL );
		$nSamples_available = $db_reply[0]->nSamples_available;
		
		if($nSamples_available == $this->nSamples_expected){
			return true;// all ok
		}else{
			return false;// samples have been used. call selectSamples() to get new samples.
		}
	}


	protected function getSampleIDs(){
		
		return $this->sampleIDs;
	}

	public static function getWorkSheets(){

		$worksheet_type = \Request::has('wtype') ? \Request::input('wtype') : "PENDING";
		$get_only_mine = \Request::has('mine') ? \Request::input('mine') : true;

		$get_only_mine = false; // by special request from Lab guys		

		$rows_to_get = \Request::has('skip') ? \Request::input('mine') : 50;
		$rows_to_skip = \Request::has('n') ? \Request::input('mine') : 0;

		$worksheets = self::getWorksheetsOfType($worksheet_type, $get_only_mine, $rows_to_get, $rows_to_skip);

		return $worksheets ?: [];
	}

	public static function  getWorksheetsOfType($worksheet_type, $get_only_mine = true, 
														$rows_to_get=50, $rows_to_skip = 0){
		
		$me = \Auth::user()->id;

		$empty_array = [];
		$condition = $get_only_mine ? " CreatedBy = '$me' AND " : " ";
		$worksheet_type = strtoupper($worksheet_type);

		switch ($worksheet_type) {
			case "PENDING"	: 	$condition .= "HasResults = 'NO'";	break;
			case "4REVIEW"	: 	$condition .= "HasResults = 'YES' AND PassedReview = 'NOT_YET_REVIEWED'"; break;
			case "COMPLETED":	$condition .= "HasResults = 'YES' AND PassedReview = 'YES' ";	break;
			case "FLAGGED"	:	$condition .= "HasResults = 'YES' AND PassedReview = 'NO' ";	break;
			case "ALL"		:	$condition .=  " 1"; break;
			default 		: 	return $empty_array;
		}

		$SQL = "SELECT 	*, 
						DATEDIFF(DateReviewed, DateCreated) AS lab_turnaround_time ,
						IF(HasResults = 'YES' AND PassedReview = 'YES', 'YES', 'NO') as is_completed

				FROM 	lab_worksheets 
				WHERE 	$condition 
				
				ORDER BY id DESC	
				
				LIMIT 	$rows_to_get
				OFFSET 	$rows_to_skip";

		$results = \DB::select( $SQL );
		return $results;
	}

	public function getUploadedFile($fileInput_field){	// to-do: assert($worksheet_number == $this->worksheet->id) 
														// Validate: to avoid double-upload

		if( \Request::hasFile($fileInput_field) == false ){
			return "Upload Failed: No File was found - please select the file to upload";
		}

		if( \Request::file($fileInput_field)->isValid() == false ){
			return "File upload failed";
		}
		$worksheet_number = preg_replace("/[^0-9]/", "", \Request::file($fileInput_field)->getClientOriginalName());
		$extension = "." . \Request::file($fileInput_field)->getClientOriginalExtension();

		if($this->worksheet->id != $worksheet_number )
			dd("You uploaded the wrong results file. (Expected " . $this->worksheet->id . ".csv)");

		$dest_folder = public_path();
		$dest_fileName = $worksheet_number . "." . time()  . $extension;
		$uploaded_file =  \Request::file($fileInput_field)->move($dest_folder, $dest_fileName);

		$uploaded_file = $dest_folder . "/" . $dest_fileName;
		return $uploaded_file;
	}


	public function getFileHandle($file_src, $mode="r")
	{
		$file_handle = null;

		if (is_resource($file_src) && get_resource_type($file_src) == "stream"){
			$file_handle = $file_src;// it's a file and it's already open
		}
		else if(is_string($file_src)){
			$file_handle = fopen($file_src, $mode);
		}
		
		return $file_handle;
	}


	public function CSVtoSQL($the_csv_file)
	{
		$csv_file_handle = $this->getFileHandle($the_csv_file);
		$data = fgetcsv($csv_file_handle);// skip 1st row (it has headers)

		$SQL = "";
		
		while (($data = fgetcsv($csv_file_handle, 2500, ",")) !== FALSE) {

			$test_result = $this->parseRow($data);
			
			$date_tested = $data[3];
			$xx = explode("/", $data[4]);

			$sample_id = $xx[0];
			$sample_id = $sample_id ?: "__none__";

			if(array_key_exists($sample_id, $this->samples)) {// make sure ID in CSV == ID in Worksheet
				$SQL .= $this->getTestResultSQL($sample_id, $date_tested, $test_result);
			}
		}

		return $SQL;
	}

	public function wrongResult(){ /* used for testing. PLEASE DISABLE BEFORE SHIPPING 

		PURPOSE:
		Used during testing.
		It Intentionally returns a wrong result, so that you can prove that errors, if any,
		would be caught and properly displayed by the DummyController test suite.

		DummyController generates a CSV file with fake results data for a given EID worksheet.
		It also provides a step-by-step process for uploading the fake data to make sure that
		the "actual result" is the same as the "expected result".

==>This function provides a wrong "actual result" so that you can see how errors are handled by the test suite.<==

		USAGE:
		1) In your .env file, set change APP_ENV to testing (i.e. set APP_ENV=testing)
		2) Copy this if() statement to the first line of parseRow().
				if (\App::environment('testing')) {
				    return $this->wrongResult();
				}
		3) ******MOST IMPORTANTLY: Delete the `if() statement from parseRow() after your tests

		TIPS:
		A) The if() statement above generates some wrong results, so that you can see how errors are displayed
		   by the DummyController test suite.
		B) The DummyController test suite, is invisible unless you set APP_ENV=testing
		C) Search routes.php for DummyController. 
		D) Select "Dummy Data" from action button on EID worksheet list. 
		   Note: "Dummy Data" only appears after you set APP_ENV=testing. 
		
		*/

		$n = mt_rand(0, 99);

		if($n <= 30) return POSITIVE;
		if($n <= 60) return LOW_POSITIVE;
		if($n <= 70) return NEGATIVE;
		
		return FAIL;
	}



	public function parseRow( $col=array() ) /* algorithm: Iga Tadeo. Implementation: Richard K. Obore */
	{/* test this */

		$result = strtoupper(trim($col[8]));// Valid
		$ctm_elbow_ch1 = $col[60];// 

		$ctm_rfi_ch1 = $col[64];


		if($result == "FAIL" || $result == "FAILED" || $result == "INVALID"){
			return FAIL;
		}

		if(empty($ctm_elbow_ch1) || $ctm_elbow_ch1 == "-"){
			return NEGATIVE;
		}

		if($ctm_elbow_ch1 < 30){
			return POSITIVE;
		}

		if( ($ctm_elbow_ch1 >= 30.0 ) && ($ctm_rfi_ch1 >= 5.0) ){
			return POSITIVE;
		}

		if( ($ctm_elbow_ch1 > 30) && ($ctm_rfi_ch1 < 5) ){
			return LOW_POSITIVE;
		}

		else return NEGATIVE;/* all other cases are negative */		
	}


	public function parseUploadedFile($the_file){

		$today = date("Y-m-d");

		$SQL = "START TRANSACTION;\n";

		$SQL .= $this->CSVtoSQL($the_file);

		$SQL .= "\n";


	// identify completed batches, if any.// cxxx
		$SQL .= "UPDATE batches SET date_PCR_testing_completed = '$today' " .

					"WHERE 	id IN ( " .
								"SELECT 	batch_id FROM 	dbs_samples " .
								"WHERE 	dbs_samples.id IN (" . $this->sampleIDs . ") " .
								"GROUP BY batch_id " .
								"HAVING sum(accepted_result is null) = 0" .
							");";

		$SQL .= "\n";
		$SQL .= "COMMIT;";
		
		return $SQL;
	}

	public function getTestResultSQL($sample_id, $date_tested, $test_result)
	{

		$today = date("Y-m-d");
		$date_tested = date("Y-m-d", strtotime($date_tested));
		$interpretation = $this->interpreteResults($sample_id, $test_result);

		
		$sql = "UPDATE dbs_samples SET ";

		foreach ($interpretation as $db_col => $quoted_value) {
			$sql .= "$db_col = $quoted_value, ";
		}
		
		$sql .= "date_dbs_tested = '$date_tested', ";
		$sql .= "date_results_entered = '$today' ";
		$sql .= "WHERE id = '$sample_id';";
		$sql .= "\n";

		return $sql;
	}


	public function countFailedTests($this_sample) /* test this */
	{
		$nFailedTests = 0;

		if ($this_sample["test_1_result"] == FAIL) $nFailedTests++;
		if ($this_sample["test_2_result"] == FAIL) $nFailedTests++;
		if ($this_sample["test_3_result"] == FAIL) $nFailedTests++;
		if ($this_sample["test_4_result"] == FAIL) $nFailedTests++;
		if ($this_sample["test_5_result"] == FAIL) $nFailedTests++;

		return  $nFailedTests;
	}



	public function getResultsForTest($testNumber, $sample_id, $skipInvalidResults = false) /* test this */
	{
		if( ! $skipInvalidResults ) {

			$result_column = "test_" . $testNumber . "_result";
			$test_result = $this->samples[$sample_id][$result_column];
			return $test_result;
		}
		
		for($count = 0, $i=1; $i <= BLOOD_SAMPLES_AVAILABLE; $i++){

			$result_column = 'test_' . $i . '_result';
			$test_result = $this->samples[$sample_id][$result_column];

			if($test_result == NEGATIVE || $test_result == POSITIVE || $test_result == LOW_POSITIVE) $count++;
			if($count == $testNumber) return $test_result;
		}

		return FAIL;// signal error. 
					//	We should never reach here unless 
					//		a) we run out of samples, or... 
					//		b) we call this function before doing the tests.
	}

	public function get_stop_codes()
	{
		return [
		
		// These codes tell us when to stop testing. 
		// They correspond to the highlighted boxes in 
		// CPHL-test-results-interpreter.jpg (in this folder)

			"1Z"   	=> 	NEGATIVE,
			"2XP" 	=> 	POSITIVE, 
			"3XFP" 	=> 	POSITIVE, 
			"4XF" 	=> 	NEGATIVE, 
			"5XQQ" 	=> 	POSITIVE,
			"6XQN" 	=> 	NEGATIVE, 
			"7XQQ" 	=> 	INVALID,
			"8XQN" 	=> 	INVALID,

			"4Z4Z" 	=> 	INVALID, // not a CPHL code. It's used to indicate that...
								 // EITHER: no valid results found for earlier tests
								 // 	OR: too many tests failed and we are out of samples.
		];
	}

	public function interpreteResults($sample_id, $this_test_result, 
											$expected_scenario = null){ /* see CPHL-test-results-interpreter.jpg */
	/* test this */

		$nPreviousTestsDone = $this->samples[$sample_id]["nTestsDone"];
		$nPreviousFailedTests = $this->countFailedTests($this->samples[$sample_id]);
		$nPreviousValidTests = $nPreviousTestsDone - $nPreviousFailedTests;
		$nBloodSamplesUsed = $nPreviousTestsDone + $nPreviousFailedTests;

		$currentTestNumber = $nPreviousTestsDone + 1;
		$result_column = "test_" . $currentTestNumber . "_result";
		$this->samples[$sample_id][$result_column] = $this_test_result;

		$result1 = $this->samples[$sample_id]["test_1_result"];
		$result2 = $this->samples[$sample_id]["test_2_result"];
		$result3 = $this->samples[$sample_id]["test_3_result"];
		$result4 = $this->samples[$sample_id]["test_4_result"];
		$result5 = $this->samples[$sample_id]["test_5_result"];

		$worksheet_1 = $this->samples[$sample_id]["worksheet_1"];
		$worksheet_2 = $this->samples[$sample_id]["worksheet_2"];
		$worksheet_3 = $this->samples[$sample_id]["worksheet_3"];
		$worksheet_4 = $this->samples[$sample_id]["worksheet_4"];
		$worksheet_5 = $this->samples[$sample_id]["worksheet_5"];

		$scenario = "";
		$accepted_result = INVALID;

		$stop_codes = $this->get_stop_codes();

		$nFailedTests = $nPreviousFailedTests;
		if($this_test_result == FAIL) $nFailedTests++;

		if($nFailedTests == 0){
			$someTestsFailed = false;
			$actualTestNumber = $currentTestNumber;			
		}
		elseif($nFailedTests == BLOOD_SAMPLES_AVAILABLE){// all tests failed. we've run out of samples
			$someTestsFailed = true;
			$actualTestNumber = BLOOD_SAMPLES_AVAILABLE;
		}		
		else{
			$someTestsFailed = true;
			$actualTestNumber = $currentTestNumber - $nPreviousFailedTests;// ignore the failed tests
		}
		
		// dd( "actualTestNumber = $actualTestNumber, currentTestNumber = $currentTestNumber, nPreviousFailedTests = $nPreviousFailedTests" );
		// dd( $actualTestNumber );

		if($actualTestNumber == 1){
			
			$test1 = $this_test_result;

			if($test1 == NEGATIVE)	{
				$accepted_result = NEGATIVE; // test #1Z
				$scenario = $someTestsFailed ? "4XF" : "1Z";  
			}
			else{//
				$scenario = "DO_ANOTHER_TEST";// in all other cases, do another test
			}
		}


		if($actualTestNumber == 2){// we only get here because first valid result was positive or low_positive
			
			$test1 = $this->getResultsForTest(1, $sample_id, "skip_failed_tests");
			$test2 = $this_test_result;

			if($test1 == POSITIVE && $test2 == POSITIVE){

				$accepted_result = POSITIVE;// test #2XP // test #3XFP
				$scenario = $someTestsFailed ? "3XFP" : "2XP";  
			}
			elseif($test1 == LOW_POSITIVE && $test2 == POSITIVE){
			/* 	for reasons i was not given, this case (LOW_POSITIVE followed by POSITIVE)
				is accepted by CPHL team as POSITIVE, yet the reverse (POSITIVE followed by LOW_POSITIVE) 
				is not considered conclusive and so has to be tested a third time  
			*/
				$accepted_result = POSITIVE;// test #2XP // test #3XFP
				$scenario = $someTestsFailed ? "3XFP" : "2XP";
			}
			else{
				$scenario = "DO_ANOTHER_TEST";// in all other cases, do another test. cx: why?	
			}
		}

		
		if($actualTestNumber == 3){	
		// Note: 
		// 	1) We only get to this if() because we had some LOW_POSITIVEs.
		//	2) Within this if(), both POSITIVE and LOW_POSITIVE are treated as low_positive

			$test1 = $this->getResultsForTest(1, $sample_id, "skip_failed_tests");// get 1st valid result
			$test2 = $this->getResultsForTest(2, $sample_id, "skip_failed_tests");// get 2nd valid result
			$test3 = $this_test_result;
						
			$nNegatives = 0;
			$nLowPositives = 0;

			if($test1 == LOW_POSITIVE || $test1 == POSITIVE){ $test1 = LOW_POSITIVE; $nLowPositives++; }
			if($test2 == LOW_POSITIVE || $test2 == POSITIVE){ $test2 = LOW_POSITIVE; $nLowPositives++; }
			if($test3 == LOW_POSITIVE || $test3 == POSITIVE){ $test3 = LOW_POSITIVE; $nLowPositives++; }

			if($test1 == NEGATIVE){ 	$nNegatives++;	}
			if($test2 == NEGATIVE){ 	$nNegatives++;	}
			if($test3 == NEGATIVE){ 	$nNegatives++;	}
			
			if($nLowPositives == 3)	{	$scenario = "5XQQ"; $accepted_result = POSITIVE; }
			if($nNegatives == 2){	$scenario = "6XQN"; $accepted_result = NEGATIVE; }	// test #6XQN


			if($test1 == LOW_POSITIVE && $test2 == LOW_POSITIVE && $test3 == NEGATIVE){
				$scenario="7XQQ"; $accepted_result = INVALID; // test #7XQQ
			}

			if($test1 == LOW_POSITIVE && $test2 == NEGATIVE && $test3 == LOW_POSITIVE){
				$scenario="8XQN"; $accepted_result = INVALID; // test #8XQN
			}
			
			if($test3 == FAIL){
				$scenario = "DO_ANOTHER_TEST";
			}
		}

		if($actualTestNumber == BLOOD_SAMPLES_AVAILABLE){// rare case: we've run out of samples before getting a result
			$scenario = "4Z4Z"; 
			$accepted_result = INVALID;
		}
		

		$interpretation = array();
		$dbs = $this->get_SCD_testReadiness($sample_id, $this->get_first_valid_result( $sample_id ));

		$interpretation["SCD_test_requested"] = $this->quote($dbs["SCD_test_requested"]);
		$interpretation["ready_for_SCD_test"] = $this->quote($dbs["ready_for_SCD_test"]);

		$interpretation["test_1_result"] = $this->quote($result1);
		$interpretation["test_2_result"] = $this->quote($result2);
		$interpretation["test_3_result"] = $this->quote($result3);
		$interpretation["test_4_result"] = $this->quote($result4);
		$interpretation["test_5_result"] = $this->quote($result5);

		$interpretation["pos_in_workSheet"] = $this->samples[$sample_id]["pos_in_workSheet"];
		
		$interpretation["worksheet_1"] = $this->quote($worksheet_1);
		$interpretation["worksheet_2"] = $this->quote($worksheet_2);
		$interpretation["worksheet_3"] = $this->quote($worksheet_3);
		$interpretation["worksheet_4"] = $this->quote($worksheet_4);
		$interpretation["worksheet_5"] = $this->quote($worksheet_5);


		if($nPreviousTestsDone == 0){
			$rtype = ($test1 == NEGATIVE) ? NEGATIVE : "NON-NEGATIVE";
			$rtype = ($test1 == NEGATIVE) ? "-ve" : "+ve";
			$interpretation["physical_location"] = $this->worksheet->id;
			$interpretation["physical_location"] = $this->quote($interpretation["physical_location"]);
		}


		if($scenario == "DO_ANOTHER_TEST"){

			$interpretation["in_workSheet"] = $this->quote("NO");
			$interpretation["testing_completed"] = $this->quote("NO");
			$interpretation["accepted_result"] = "NULL";

		}else{
			
			$interpretation["in_workSheet"] = $this->quote("YES");
			$interpretation["testing_completed"] = $this->quote("YES");
			$interpretation["accepted_result"] = $this->quote($accepted_result);
			$interpretation["PCR_results_ReleasedBy"] = $this->current_user;

			$this->check_assumptions($scenario, $expected_scenario, $accepted_result);
		}

		return $interpretation;
	}

	public function check_assumptions($scenario, $expected_scenario, $accepted_result)
	{				
		$stop_codes = $this->get_stop_codes();
		$this->assertThat($stop_codes[$scenario] == $accepted_result, 
				"ASSERT failed because 'stop_codes[$scenario]' !== '$accepted_result'");

		if($expected_scenario === null)
			return;
	
		$this->assertThat( $scenario == $expected_scenario,
			"ASSERT failed: Scenarios don't match: [expected $expected_scenario, got $scenario]");
	}

	public function assertThat($assertion_is_true, $err_msg)
	{
		if( $assertion_is_true ) 
			return;
		else
			throw new \Exception($err_msg, 1);
	}

	public function unQuote( $str )
	{
		$str = trim($str, "'");
		$str = rtrim($str, "'");

		return $str;
	}

	public function quote($str){ /* test this */
	/* 	
		WARNING: This function handles null test_results in interpreteResults(). 
				 DO NOT use it anywhere else! It's not general enough. 				
	
		THE DETAILS: test_results are stored in a MySQL enum; 
					 They must always be quoted unless their value is NULL.					
	*/
		if($str == null || strtoupper($str) == "NULL") 
			return "NULL";
		else
			return "'$str'";
	}

	public function uploadResultsCSV($fileInput_field){

		if( ! $this->worksheet->exists ){
			return "Upload Failed: No worksheet found for the uploaded results";
		}

		if($this->worksheet->HasResults=='YES'){
			dd("ERROR: This worksheet has already been uploaded!");
		}

		$file_name = $this->getUploadedFile($fileInput_field);
		$file_handle = $this->getFileHandle($file_name);
		$SQL = $this->parseUploadedFile($file_name);

		$this->db_execute( $SQL );

		fclose($file_handle);

		// update the worksheet
		$this->worksheet->HasResults = true;
		$this->worksheet->save();

		return null;// success
	}

	private function db_execute($sql){
		return \DB::unprepared($sql);/* runs raw SQL (as opposed to prepared statements) */
	}

	public function detachSamples(){

		$reason = null;
		if($this->cantDetachSamples($reason)) return $reason;

		$SQL = $this->getDetachSQL();

		$this->db_execute( $SQL );

		return null;// success
	}

	public function cantDetachSamples(&$reason){

		if( ! $this->worksheet->exists ){
			$reason = array();
			$reason["code"] = 1;
			$reason["msg"] = "Worksheet had not yet been created (it has no samples attached to it)";

			return true; // not ok to detach samples
		}


		if($this->worksheet->HasResults == "YES"){

			$reason = array();
			$reason["code"] = 2;
			$reason["msg"] = "Worksheet has already been tested (so system can't detach its samples)";

			return true; // not ok to detach samples
		}

		return false;// its ok to detach samples
	}

	public function countAllTestsEverDone(){

		$SQL = "SELECT SUM(	(test_1_result is not null) + 
							(test_2_result is not null) + 
							(test_3_result is not null) + 
							(test_4_result is not null) + 
							(test_5_result is not null) ) as total_tests  from dbs_samples";
		
		$db_reply = \DB::select($SQL);
		$total = $db_reply[0]->total_tests;

		return $total;
	}

	public function get_first_valid_result($sample_id)
	{
		return $this->getResultsForTest(1, $sample_id, "skip_failed_tests");

	}

	public function getNumOfWorksheets( $sample_id )
	{
		if(array_key_exists($sample_id, $this->samples))
			return $this->samples[$sample_id]["nWorksheets"];
		else
			return -1;// sample does not exist		
	}

	public function setNumOfWorksheets( $sample_id, $nWorksheets )
	{
		if(array_key_exists($sample_id, $this->samples))
			$this->samples[$sample_id]["nWorksheets"] = $nWorksheets;
		else
			throw new \Exception("setNumOfWorksheets()... sample_id `$sample_id` does not exist", 1);
	}

	public function get_SCD_testReadiness($sample_id, $first_valid_result) /* test this */
	{
	// test for:
	//	1) if(testReadiness_is_alreadyKnown) dont change anything
	//	2) if(first_valid_result == FAIL) ...
	//	3) if(first_valid_result == NEGATIVE && $SCD_test_requested == "YES") ...
	//	4) if(first_valid_result == NEGATIVE && $SCD_test_requested == "NO") ...
	//	5) if(first_valid_result == LOW_POSITIVE) ...
	//	6) if(first_valid_result == POSITIVE) ...


		$reply = array();

		$this_sample = $this->samples[$sample_id];
		$nWorksheets = $this->samples[$sample_id]["nWorksheets"];		
		$SCD_test_requested = $this_sample["SCD_test_requested"];
		$testReadiness_is_alreadyKnown = $this_sample["ready_for_SCD_test"] == 'YES' || 
										 $this_sample["ready_for_SCD_test"] == 'TEST_ALREADY_DONE';

		if(	$testReadiness_is_alreadyKnown){// don't change anything

			$reply["ready_for_SCD_test"] = $this_sample["ready_for_SCD_test"];
			$reply["SCD_test_requested"] = $this_sample["SCD_test_requested"];

			return $reply;
		}

		if($first_valid_result == FAIL){
			//
			//	We are here because
			//		a) EID tests havent been run yet, or 
			//		b) all the tests done so far have failed
			//	So, do not change anything. 
			//
			$reply["ready_for_SCD_test"] = $this_sample["ready_for_SCD_test"];
			$reply["SCD_test_requested"] = $this_sample["SCD_test_requested"];

			return $reply;
		}

		if($first_valid_result == NEGATIVE) {
			
			if($SCD_test_requested == "YES"){
				$reply["ready_for_SCD_test"] = "YES";
				$reply["SCD_test_requested"] = "YES";	
			}

			if($SCD_test_requested == "NO"){
				$reply["ready_for_SCD_test"] = "NO";
				$reply["SCD_test_requested"] = "NO";	
			}
			
			return $reply;
		}

		if($first_valid_result == POSITIVE){

			$reply["SCD_test_requested"] = "YES"; 			
 			$reply["ready_for_SCD_test"] =  ($nWorksheets >= 2) ? "YES" : "NO";

 			return $reply;
		}


		if($first_valid_result == LOW_POSITIVE){
			// dd("4");

			$reply["SCD_test_requested"] = "YES"; 			
 			$reply["ready_for_SCD_test"] =  ($nWorksheets >= 3) ? "YES" : "NO";

 			return $reply;
		}


		//  
		// 	We should never reach here unless 
		//		a) EID tests havent been run or 
		//		b) all the tests done so far have failed
		//	Action = do not change anything
		//
		$reply["ready_for_SCD_test"] = $this_sample["ready_for_SCD_test"];
		$reply["SCD_test_requested"] = $this_sample["SCD_test_requested"];

		return $reply;
	}
}

//
// #1) if invalid, add a remark that says "Invalid due to failed QC criteria"
// #2) does CSV have worksheet ID? If yes, can we reject upload-results for CSV which already has results?
// 