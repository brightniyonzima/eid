<?php namespace EID\Lib;


class DispatchManager{
	
	static function calcRowsToSkip( $nRows_per_page )
	{
		$page_number = \Request::get('pg', 1);// page to show
		$page_offset = $page_number - 1;

		$pages_to_skip = $page_offset;
		$rows_to_skip = $pages_to_skip * $nRows_per_page;

		return $rows_to_skip;
	}

	static function get_extra_conditions( $prefix = '') {

		if(\Request::has('rj')){
			return " $prefix batches.all_samples_rejected = 'YES' ";
		}
		
		if(\Request::has('scd')) {
			$relaxed_condition = " date_SCD_testing_completed is not null ";
			return " $prefix (SCD_results_released = 'YES'  OR $relaxed_condition) ";
		}

		$relaxed_condition = " date_PCR_testing_completed is not null ";
		return " $prefix (PCR_results_released = 'YES'  OR $relaxed_condition) ";
	}


	static function getBatchesForDispatch_SC_only()
	{
		/* use default values for all params except last one ($sc_only) */
		return DispatchManager::getBatchesForDispatch(true, 0, 200, true);
	}
	
	static function getBatchesForDispatch( $include_negatives = true, $offset = 0, $nRowsToGet = 200, $sc_only = false)
	{
		$HUB_NOT_KNOWN = 99; /* I created a dummy hub for cases where a facility has no hub 
								(or its hub=0). 
								In my database, this dummy hub has id of 99. 
								Change this value to the correct value for your database. */

		$offset = DispatchManager::calcRowsToSkip( $nRowsToGet );
		$extra_conditions = DispatchManager::get_extra_conditions(" AND ");
		$date_testing_completed = \Request::has('scd') ? "date_SCD_testing_completed" : "date_PCR_testing_completed";
		$date_testing_completed = \Request::has('rj') ? "date_entered_in_DB" : $date_testing_completed;

		$t = $include_negatives ? " " : " AND accepted_result = 'POSITIVE' ";

		if(\Request::has('e404')){
			$untrusted_input = \Request::get('e404');
			$missing_batch = preg_replace("/[^A-Za-z0-9 ]/", '', $untrusted_input);// clean it

			$extra_conditions = " 	AND batch_number like '$missing_batch%' ";
		}


		$sql = "SELECT DISTINCT	

						batches.id, 
						batches.date_entered_in_DB,
						envelope_number,
						batch_number, 
						facility_id,
						date_PCR_testing_completed,
						date_SCD_testing_completed,
						cast(printed_PCR_results as char) as printed_PCR_results,
						cast(printed_SCD_results as char) as printed_SCD_results,
						PCR_results_released,
						SCD_results_released,
						tests_requested,

						facilities.facility, 
						hubs.hub AS hubname

				FROM 	batches,
						dbs_samples, 
						facilities, 
						hubs
				
				WHERE 	batches.id = dbs_samples.batch_id

				  AND	batches.facility_id = facilities.id 
				  AND 	hubs.id = if(facilities.hubID, facilities.hubID, $HUB_NOT_KNOWN)
				   		$extra_conditions
				   		$t

				ORDER BY $date_testing_completed DESC, hubname, facility

				LIMIT $offset, $nRowsToGet ";

// dd($sql);

		$batches = \DB::select($sql);
		return $batches;
	}
}






/**
Replace the SQL above with this one, because this one allows you to paginate (see comment after LIMIT clause)
update: problem solved in a different way

select (@r := @r+1) as row , z.* from(

  SELECT DISTINCT 
  
        batches.id, 
        facility_id,
        date_PCR_testing_completed,

        PCR_results_released,
        SCD_results_released,
        tests_requested,
  
        facilities.facility, 
        hubs.hub AS hubname
  
      FROM  batches,
        dbs_samples, 
        facilities, 
        hubs
      
      WHERE  batches.id = dbs_samples.batch_id
  
        AND batches.facility_id = facilities.id 
        AND  hubs.id = if(facilities.hubID, facilities.hubID, 99)
            AND  PCR_results_released = 'YES' 
            
  
      ORDER BY date_PCR_testing_completed DESC, hubname, facility

      
)z, (select @r:=0)y limit 2, 1; // where 2 is how many to skip, and 1 is how many to include

**/