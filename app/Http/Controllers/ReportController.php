<?php namespace EID\Http\Controllers;

use View;
use EID\Models\Batch;
use EID\Models\Sample;

class ReportController extends Controller {

	public function data_entry_qty()
	{
		return view('rpt_data_entry');
	}

	public function qty_eid()
	{
		return view('rpt_eid_tests');
	}

	public function fail_rate()
	{
		return view('rpt_failed_tests');
	}
	
	public function rpt_printed()
	{
		return view('rpt_printed');
	}
	
	

	public function data_entry_metrics()
	{		
		return view('data_entry_metrics');
	}	

	public function data_entry_performance()
	{		
		$team = \Request::get('team', false);
		$period = \Request::get('period', 1);

		if( !$team ) dd("Error - You shouldn't be here!");

		$start_date = $this->days_back( $period );

		$this->get_data_entrants_performance($team, $start_date, "");

		return "hello";
	}	

	public function days_back( $n )
	{
		$date = date_create();
		date_sub($date, date_interval_create_from_date_string("$n days"));
		return date_format($date, 'Y-m-d');
	}


	public function get_data_entrants_performance($type_of_work, $start_date, $end_date)
	{

// mysql> select other_name , date_results_entered , /* PCR_results_ReleasedBy, */ round(count(date_results_entered)/22) as nWorksheets , 'EID Samples' as work_type from dbs_samples, users  where PCR_results_ReleasedBy = users.id and accepted_result is not null and date_results_entered  > '2015-11-01' group by date_results_entered, PCR_results_ReleasedBy order by date_results_entered asc, nWorksheets desc;


		$sql = [];
		$sql["DATA_ENTRY"] = "select other_name as name, /* entered_by, */ count(date_entered_in_DB) as 'nBatches' , 'Data Entry' as work_type from dbs_samples, batches, users  where  users.id = entered_by and batches.id = dbs_samples.batch_id and date_entered_in_DB  > '$start_date' group by entered_by order by nBatches desc; ";
		$sql["SAMPLE_VERIFICATION"] = "select other_name,sample_verified_by, count(sample_verified_on) as `Samples Verified` , 'sample_verification' as work_type from dbs_samples, users  where users.id = sample_verified_by and sample_verified_on  > '$start_date' group by sample_verified_by order by `Samples Verified` desc; ";
		$sql["EID_LAB"] = "select other_name , /* PCR_results_ReleasedBy, */ round(count(date_results_entered)/22) as nWorksheets , 'EID Samples' as work_type from dbs_samples, users  where PCR_results_ReleasedBy = users.id and accepted_result is not null and date_results_entered  > '$start_date' group by PCR_results_ReleasedBy order by nWorksheets; ";
		$sql["SCD_LAB"] = "select other_name, /* SCD_results_ReleasedBy, */ round(count(date_SCD_testing_completed)/80) as nWorksheets , 'Sickle Cell Samples' as work_type from dbs_samples, batches, users  where batches.id = dbs_samples.batch_id and users.id = SCD_results_ReleasedBy and SCD_test_result is not null and date_SCD_testing_completed  > '$start_date' group by SCD_results_ReleasedBy order by nWorksheets desc;";

		$db_query = "SELECT 'No data found for type of work = $type_of_work' as db_error ";

		$db_query = array_key_exists($type_of_work, $sql) ? $sql[$type_of_work] : $db_query;
			
		$db_rows = \DB::select( $db_query );

		$data = $this->toAssArray( $db_rows );

		$csv_file_name = $type_of_work . "_" . date("Y-m-d");

		return $this->exportCSV($csv_file_name, $data);		
	}



	public function exportCSV($output_file_name, $data) /* data must be an array of arrays */
	{
		$default_data = [];
		$default_data[0]["err"] = "Export Failed - No data found!";

		$no_data_found = isset($data[0]) ? false : true;

		$data = $no_data_found ? $default_data : $data;

		\Excel::create($output_file_name, function($excel) use($data) {
		    $excel->sheet('Sheetname', function($sheet) use($data) {
		        $sheet->fromArray($data);
		    });
		})->export('csv');
	
	}



	public function toAssArray($db_rows)/* Associative Array from db rows */
	{

		$arr = [];

		foreach ($db_rows as $row) {
			$arr[] = (array) $row;
		}

		return $arr;
	}

}