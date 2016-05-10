<?php

define('SC_SAMPLES_PER_WORKSHEET', 80);// Sickle Cell Samples per worksheet. should be 80;
define('NUMBER_OF_CONTROLS', 8);// Sickle Cell Samples per worksheet. should be 80;


class SCManager{

	static function getNumSamplesPerWorksheet()
	{
		return SC_SAMPLES_PER_WORKSHEET;
	}

	public function sortString($str)// e.g. given 'bac' it returns 'abc'
	{

		$stringParts = str_split($str);
		sort($stringParts);
		$sorted_string = implode('', $stringParts);

		return $sorted_string;
	}

	public function compare_sc_results($result1, $result2)	// did 2 lab techs return the same result?
	{														// if yes, return it. Otherwise return empty string
		$r1 = strtoupper($result1);
		$r1 = sortString($r1);

		$r2 = strtoupper($result2);
		$r2 = sortString($r2);

		if ($r1 == $r2) return $r1;
		else return "";
	}


	static function isControl($position)
	{

		$as_subscripts = is_numeric($position) ? true : false;
		$controls = SCManager::getControlSamples( $as_subscripts );

		$is_control = in_array($position, $controls);

		if($is_control)
			return true;
		else
			return false;
	}


	static function getControlSamples($as_array_subscripts = true){
	
		if($as_array_subscripts)
			return [ 0, 11,	22,	33,	44,	55,	66,	77 ];// as subscripts of tray layout array
		else
			return [ 'A01', 'A12', 'B11', 'C10', 'D09',	'E08', 'F07', 'G06' ];// as values of tray layout array

	}

	static function getTrayLayout()
	{

		return [
			'A01', 	'A02', 	'A03', 	'A04',  'A05', 	'A06', 	'A07', 	'A08', 	'A09', 	'A10', 	'A11', 	'A12',
			'B01', 	'B02', 	'B03', 	'B04', 	'B05', 	'B06', 	'B07', 	'B08', 	'B09', 	'B10', 	'B11', 	'B12',
			'C01', 	'C02', 	'C03', 	'C04', 	'C05', 	'C06', 	'C07', 	'C08', 	'C09', 	'C10', 	'C11', 	'C12',
			'D01', 	'D02', 	'D03', 	'D04', 	'D05', 	'D06', 	'D07', 	'D08', 	'D09', 	'D10', 	'D11', 	'D12',
			'E01', 	'E02', 	'E03', 	'E04', 	'E05', 	'E06', 	'E07', 	'E08', 	'E09', 	'E10', 	'E11', 	'E12',
			'F01', 	'F02', 	'F03', 	'F04', 	'F05', 	'F06', 	'F07', 	'F08', 	'F09', 	'F10', 	'F11', 	'F12',
			'G01', 	'G02', 	'G03', 	'G04', 	'G05', 	'G06', 	'G07', 	'G08', 	'G09', 	'G10', 	'G11', 	'G12',
			'H01', 	'H02', 	'H03', 	'H04'
		];
	}


	static function getLocationCode($position)
	{

		if($position < 0 || $position > SC_SAMPLES_PER_WORKSHEET+NUMBER_OF_CONTROLS){
			return "XXX";// out of range
		}

		$matrix = SCManager::getTrayLayout();

		return $matrix[$position]; 
	}

	static function getLocationCodeOLD($pos, $skip_controls = true)
	{

		$position = $pos;
		$next_position = $position + 1;


		if($position < 0 || $position > SC_SAMPLES_PER_WORKSHEET+NUMBER_OF_CONTROLS){
			die("getLocationCode(): position is out of range [$position]");
		}


		$matrix = SCManager::getTrayLayout();
		$controls = SCManager::getControlSamples();


		if(in_array($position, $controls)) 
			$is_control = true;// current position is reserved for control samples
		else
			$is_control = false;


		if($is_control && $skip_controls)
			return $matrix[$next_position];
		else
			return $matrix[$position];

	}	
	
	public function getWorksheetData($scws_id)
	{
		$sql = "SELECT sample_id as id, position FROM sc_worksheet_index 
					WHERE worksheet_number = '$scws_id' 
						ORDER by position ASC";

		$data = DB::select($sql);

		return $data;
	}

	public function getWorksheets(){

		$sql = "SELECT id, DateCreated, Examiner1_ResultsReady, Examiner2_ResultsReady, TieBreaker_ResultsReady 
					FROM sc_worksheets 
					
					/* WHERE id IN (SELECT DISTINCT worksheet_number FROM sc_worksheet_index WHERE TieBreaker_ResultsReady = 'NO') */
					
					ORDER BY id DESC";

		$worksheets = DB::select($sql);
		
		return $worksheets;
	}

	public function hasActiveWorksheet()
	{
		$sql = "SELECT id FROM sc_worksheets 
					WHERE 	Examiner1_ResultsReady = 'NO' 
					  	OR  Examiner2_ResultsReady = 'NO' 
					  	OR  TieBreaker_ResultsReady = 'NO' 
					ORDER BY id DESC LIMIT 1";// "order by" and "limit" keep this query fast as table grows
		$results = DB::select($sql);
		$nRows = count( $results );

		return ($nRows == 0) ? false : $results[0]->id;
	}

 


	// public function createWorksheet($nSamplesNeeded = SC_SAMPLES_PER_WORKSHEET){ 
		/* i suspect this function is no longer used. Check. */
	// // NB: This currently does not force them to go get EID samples

	// 	if($ws = $this->hasActiveWorksheet()){
	// 		return $ws;
	// 	}


	// 	$sc_fields = "id, SCD_test_result ";
	// 	$sql = "SELECT $sc_fields FROM dbs_samples 
	// 			WHERE in_workSheet = 'NO' 
	// 			  AND (SCD_test_requested = 'YES' AND PCR_test_requested = 'NO') 
	// 			ORDER BY id ASC
	// 			LIMIT $nSamplesNeeded"; // Get samples that need SC test only.
	// 									// Samples that also need PCR test, go for PCR first.
	// 							  		// The PCR module adds them to a SC worksheet after its done.

	// 	$sc_samples = DB::select($sql);

	// 	$samples_available = count($sc_samples);

	// 	if( $samples_available < $nSamplesNeeded )	return -1; // not enough samples. stop.

	// 	// create Worksheet
	// 	$scws = new SCWorksheet;
	// 	$scws->CreatedBy = \Auth::user()->id;
	// 	$scws->DateCreated = date('Y-m-d');
		
	// 	$scws->save();
	// 	$worksheet_number = $scws->id;

	// 	// create Worksheet's index
	// 	$sql = "INSERT INTO sc_worksheet_index (worksheet_number, sample_id, position) VALUES ";

	// 	$j = 0;
	// 	$IDs =  "";
	// 	$comma = ",";

	// 	foreach ($sc_samples as $sample) {

	// 		if(SCManager::isControl($j)){
	// 			$j++;
	// 			continue;
	// 		}

	// 		$j++;
	// 		$sample_id = $sample->id;
	// 		$sample_position = $this->getTrayPosition($j);

	// 		$sql .= "\n ('$worksheet_number', '$sample_id', '$sample_position') " . $comma;
	// 		$IDs .= " '$sample_id' " . $comma;
	// 	}

	// 	rtrim($sql, $comma);
	// 	rtrim($IDs, $comma);

	// 	DB::unprepared($sql);
	// 	DB::unprepared("UPDATE dbs_samples SET in_workSheet = 'YES'  WHERE id IN ($IDs)");
		
	// 	return $worksheet_number;		
	// }

	public function getTestResults($scws_id = null){// scws = Sickle Cell Worksheet

		$sql = "SELECT sample_id as infant_id, position as pos, result1, result2, tie_break_result,
						(substring_index(result1, '_', 1) = substring_index(result2, '_', 1)) as result1_equals_result2
					FROM sc_worksheet_index
						WHERE 	worksheet_number = '$scws_id' 
								ORDER BY position ASC";
		
		$results = DB::select($sql);
		return $results;
	}

	public function tieBreak()
	{
		$results = [];
		$sql = "SELECT * FROM sc_worksheet_index WHERE worksheet_number = '$scws_id' ORDER BY position ASC";
		$rows = DB::select($sql);


		foreach ($rows as $this_row) {

			$sample = new StdClass;
			$sample->pos = $this_row['position'];
			$sample->infant_id = $this_row['sample_id'];
			$sample->result = compare_sc_results($this_row['result1'], $this_row['result2']);

			$results[] = $sample;
		}
		return $results;
	}


	public function showResultsStatus($rn, $scws)
	{
		$scws_id = $scws->id;

	    if($rn === 1){
	        if($scws->Examiner1_ResultsReady == "YES")
	            return "YES";
	        else
	            return "NO. <a href='/scd?scws=$scws_id&rn=1'>Enter them now</a>";
	    }

	    if($rn === 2){
	        if($scws->Examiner2_ResultsReady == "YES")
	            return "YES";
	        else
	            return "NO. <a href='/scd?scws=$scws_id&rn=2'>Enter them now</a>";
	    }

	    if($rn === 3){
	    	if($scws->Examiner1_ResultsReady === "NO" || $scws->Examiner1_ResultsReady == "NO"){
	    		return "NO";
	    	}


	        if($scws->TieBreaker_ResultsReady == "YES"){
	            return "YES. <a target='_blank' href='/scd_results/$scws_id?pp=1'>See Results</a>" . " | " .
	            			"<a target='_blank' href='/scd?scws=$scws_id&rn=3'>Change Results</a>";
	        }
	        else
	            return "NO. <a href='/scd?scws=$scws_id&rn=3'>Tie-Break now</a>";
	    }
	}
}