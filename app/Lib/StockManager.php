<?php 

define('UNKNOWN_ACTION', '-44');
define('SAVE_AS_STOCK_ADJUSTMENT', 'STOCK_ADJUSTMENT');
define('SAVE_AS_STOCK_REQUISITION', 'STOCK_REQUISITION');
define('GET_ALL_DATA', 'GET_ALL_DATA');/* The value of this constant can be anything except 'YES' or 'NO' */


use EID\stock_status;
use EID\stock_requisition_header;
use EID\stock_requisition_line_items;
use EID\Http\Requests;

class StockManager{
	
	static function show_requisition_status($stage, $rqn){
		$requisition = $rqn->toArray();
		$stage_completed = false;

		switch ($stage) {
			case 'APPROVAL':	$stage_completed = $requisition["date_approved"];	break;
			case 'DISPATCH':	$stage_completed = $requisition["date_dispatched"];	break;
			case 'DELIVERY':	$stage_completed = $requisition["date_received"];	break;
		}

		if( $stage_completed ) {

			$reply = "YES";
			if($stage == 'DISPATCH') $reply = "SENT";
			
			return $reply;
		}
		
		$link_text = "NO";
		if($stage == "DISPATCH") $link_text = "PENDING";

		return link_to("/stock_approval/" . $requisition["id"], $link_text);
	}

	static function getCommodities( $commodity_id = 0 )
	{
		$sql = "SELECT id, commodity, categoryID, tests_per_unit FROM commodities";
		
		if($commodity_id > 0) {
			$sql .= " WHERE id = '$commodity_id' ";
		}

		$rows = \DB::select( $sql );
		$commodities = [];

		foreach ($rows as $c) {
			$id = $c->id;
			$name = $c->commodity;
			$commodities[ $id ] = $c;
		}

		return $commodities;
	}

	static function getCategories()
	{
		$sql = "SELECT id, category_name FROM commodity_categories";
		$categories = \DB::select( $sql );
		$commodity_categories = [];

		foreach ($categories as $c) {
			$id = $c->id;
			$name = $c->category_name;
			$commodity_categories["$id"] = $name;
		}

		return $commodity_categories;
	}

	
	static function getRequisitionMethods()
	{
		return [
			'AUTO_FORECAST' => 'AUTO (FORECAST BY SYSTEM)',
			'PHONE' => 'PHONE',
			'EMAIL' => 'EMAIL',
			'DBS_COMMENTS' => 'DBS COMMENTS',
			'OTHER' => 'OTHER'
		];
	}

	static function getFacilities()
	{
		$EID_DATABASE = "eid";
        $SQL = "SELECT  facilities.id AS facility_id, 
                        facilities.facility AS facility_name, 
                        districts.name AS district, districtcode 

                    FROM    $EID_DATABASE.facilities, $EID_DATABASE.districts 

                    WHERE   facilities.districtID  = districts.id 

                    ORDER BY    district, facility_name";

        $results = DB::select( $SQL );

        return $results;
	}

	static function store_requisition()
	{
        $ff = (object) Request::all();
        $nLineItems = count( $ff->commodity );
        $requisition_header_id = StockManager::store_requisition_header( $ff );
        
        for($i =0; $i < $nLineItems; $i++){
        	StockManager::store_requisition_line_item($requisition_header_id, $ff, $i);
        }
	}



    static function store_requisition_header( $ff )
    {

    	// dd( $ff );

        $old_requisition_header = stock_requisition_header::find($ff->id);
        $requisition_header = $old_requisition_header ?: new stock_requisition_header();

        $requisition_header->facility_id = $ff->facility_id;
        $requisition_header->requisition_date = $ff->requisition_date;
        $requisition_header->requisition_method = $ff->requisition_method;
        $requisition_header->requestors_batch_number = $ff->requestors_batch_number;
        $requisition_header->requestors_name = $ff->requestors_name;
        $requisition_header->requestors_phone = $ff->requestors_phone;

        $requisition_header->save();

        return $requisition_header->id;
    }


	static function store_requisition_line_item($requisition_header_id, $ff, $i)
	{

		$line_item = new stock_requisition_line_items;
		
		$line_item->requisition_header_id = $requisition_header_id;
		$line_item->commodity_id = $ff->commodity[$i];
		$line_item->quantity_requested = $ff->qty[$i];
		$line_item->save();

		$line_item_id = $line_item->id;

		$ff->commodity_id = $line_item->commodity_id;
		$ff->change_in_quantity = $line_item->quantity_requested;

		StockManager::update_stock_status(SAVE_AS_STOCK_REQUISITION, $line_item_id, $ff);

		return $line_item_id;
	}



	static function store_requisitionOLD()
	{
        $ff = (object) Request::all();

        $requisition = stock_requisition_header::find($ff->id);
        $requisition = $requisition ?: new stock_requisition_header();

        $requisition->facility_id = $ff->facility_id;
        $requisition->requisition_date = $ff->requisition_date;
        $requisition->requisition_method = $ff->requisition_method;
        $requisition->requestors_batch_number = $ff->requestors_batch_number;
        $requisition->requestors_name = $ff->requestors_name;
        $requisition->requestors_phone = $ff->requestors_phone;

        $requisition->save();

        $sql = "";
        $nLineItems = count( $ff->commodity );
        
        for ($i =0; $i < $nLineItems; $i++){   

        	/*
	        	// use this pattern instead ...     
	        	$line_item = new Requisition_line_item;
	      		$line_item->quantity = 250;
				$line_item->save();

				$line_item_id = $line_item->id;

				// ... so that u can do this:
				StockManager::update_stock_status($line_item_id, ...)
			*/
			
			$requisition_header_id = $requisition->id;
			$commodity_id = $ff->commodity[$i];
			$quantity_requested = $ff->qty[$i];

        	$sql .= " INSERT INTO stock_requisition_line_items 
        				(
        					requisition_header_id,
        					commodity_id,
        					quantity_requested
        				)
					VALUES 
						(
        					'$requisition_header_id',
        					'$commodity_id',
        					'$quantity_requested'
						);";
        }

        DB::unprepared( $sql );
	}

	static function getCommodityName( $commodity_id )
	{
 		$x = StockManager::getCommodities( $commodity_id );
 		$commodity = $x[ $commodity_id ];
 		return $commodity->commodity;
	}


	static function store_approved_quantities($requisition_id, $commodities, $qty_requested, $qty_approved, $facility_id, $requestors_name,$requestors_phone)
	{
		    $sql = "";
	        $nLineItems = count( $commodities );
	        
	        for ($i = 0; $i < $nLineItems; $i++){        	
				
				$requisition_header_id = $requisition_id;
				$commodity_id = $commodities[$i];
				$quantity_requested = $qty_requested[$i];
				$quantity_approved = $qty_approved[$i];

				$requestors=$requestors_name;
				$requestors_phone_no=$requestors_phone;

				$facility_id = Input::get('facility_id');
				StockManager::update_stock_on_hand_on_approval($requisition_header_id,$quantity_approved,$facility_id,$commodity_id,$requestors,
					                                           $requestors_phone_no);


	        	$sql .= "UPDATE stock_requisition_line_items 
	        				SET quantity_requested='$quantity_requested',
	        					quantity_approved='$quantity_approved'
	        			WHERE requisition_header_id='$requisition_header_id' AND commodity_id='$commodity_id';" ;
	            
	        }

	        DB::unprepared( $sql );
	}	

	static function get_stock_history($facility_id, $commodity_id, $sort_order = 'DESC')
	{
		return get_stock_status($facility_id, $commodity_id, GET_ALL_DATA, $sort_order);
	}

	static function get_stock_status($facility_id, $commodity_id, $get_only_most_recent_data = true, $sort_order = 'DESC')
	{

		$db_rows = stock_status::whereNested(function ($query) 
				use ($facility_id, $commodity_id, $get_only_most_recent_data)
				{
					$query->where('facility_id', '=', $facility_id);
					$query->where('commodity_id', '=', $commodity_id);

					if($get_only_most_recent_data){
						$query->where('is_most_recent_change', '=', 'YES');
					}
				})
				->orderBy('id', $sort_order);


		if( $get_only_most_recent_data )
			return $db_rows->first();
		else
			return $db_rows->get();// all
	}


    static function update_stock_status($action, $action_id, $request)
    {

    	$valid_action = ($action == SAVE_AS_STOCK_REQUISITION || $action ==  SAVE_AS_STOCK_ADJUSTMENT);
    	
    	if( !$valid_action )
    		throw new Exception("Invalid Action in StockManager::update_stock_status", $action?:UNKNOWN_ACTION );
    	
    // dd( $request );

        $facility_id = $request->facility_id;
        $commodity_id = $request->commodity_id;

        $new_status = new stock_status;

        $new_status->facility_id = $facility_id;
        $new_status->commodity_id = $commodity_id;
        $new_status->stock_changed_by = $action;
        $new_status->stock_change_details_id = $action_id;
        $new_status->average_monthly_consumption = StockManager::AMC($facility_id, $commodity_id);
        $new_status->alert_quantity = StockManager::get_alert_quantity($facility_id, $commodity_id);
        $new_status->initial_quantity = StockManager::estimate_available_stock($facility_id, $commodity_id);
        $new_status->restock_quantity = $request->change_in_quantity;
        $new_status->restock_date = date('Y-m-d');// today

        $new_status->save();

        StockManager::set_most_recent_change($new_status->id);
    }


    static function set_most_recent_change($newest_change_row_id)
    {

    	$s = stock_status::findOrFail($newest_change_row_id);
		$facility_id = $s->facility_id;
		$commodity_id = $s->commodity_id;

    	$sql = "UPDATE stock_status 

    			SET is_most_recent_change = 'NO'  
    			
    			WHERE	(	facility_id = '$facility_id' AND 
    						commodity_id = '$commodity_id' AND
    						is_most_recent_change = 'YES'
    					);  ";

		$sql .= "UPDATE stock_status SET is_most_recent_change = 'YES' WHERE id = '$newest_change_row_id'";

    	DB::unprepared( $sql );
    }

    static function AMC($facility_id, $commodity_id, $nDays=90)// AMC = Average Monthly Consumption
    {

		$today = new DateTime();
		$amc_end_date = $today->format("Y-m-d");


		$amc_since = $today->sub(new DateInterval("P" . $nDays . "D"));
		$amc_start_date = $amc_since->format("Y-m-d");

		
		$total_consumption = StockManager::get_stock_consumption_between($amc_start_date, $amc_end_date, $facility_id);
		$nMonths = $nDays / 30;


		$average_monthly_consumption = ceil($total_consumption / $nMonths);

		if ($average_monthly_consumption < 1) {
			$average_monthly_consumption = 1;
		}

		return $average_monthly_consumption;
    }


	static function get_stock_consumption_between($amc_start_date, $amc_end_date, $facility_id)
	{

		//$EID_DATABASE = "eid";
		$sql = "SELECT COUNT(infant_name) as nInfantsTested
		            FROM 	dbs_samples JOIN batches
		            ON dbs_samples.batch_id=batches.id
		            WHERE facility_id = '$facility_id'
		            AND 	date_dbs_taken >= '$amc_start_date' 
					AND 	date_dbs_taken <= '$amc_end_date'";
					/*
		$sql = "SELECT COUNT(infant_name) as nInfantsTested 

					FROM 	$EID_DATABASE.dbs_samples, $EID_DATABASE.batches 

					WHERE 	batch_id = batches.id 
					AND 	facility_id = '$facility_id'
					AND 	date_dbs_taken >= '$amc_start_date' 
					AND 	date_dbs_taken <= '$amc_end_date'";
					*/

		$db_rows = \DB::select($sql);

		$total_consumption = 0;

		foreach ($db_rows as $data) {
			$total_consumption = $data->nInfantsTested;
		}

		return $total_consumption;
		
	}


    static function get_alert_quantity($facility_id, $commodity_id)
    {
    	$average_monthly_consumption = StockManager::AMC($facility_id, $commodity_id);
		$alert_quantity = $average_monthly_consumption * 2;

		return $alert_quantity;
    }


    static function estimate_available_stock($facility_id, $commodity_id)
    {
        $old_status = StockManager::get_stock_status($facility_id, $commodity_id);
        
        if( $old_status == null ){// no stock data

        	return 0;// assume they have no stock. 
        			 // Use stock adjustment interface if you ever need to correct this assumption
        }

        $previous_restock_date = $old_status->restock_date;
        $quantity_available_after_last_restock = $old_status->total_stock_on_hand;
		$consumption_since_last_restock = StockManager::estimated_consumption_since( $previous_restock_date, $facility_id );
		
        $available_stock = 	$quantity_available_after_last_restock - $consumption_since_last_restock;

        return $available_stock;
    }

	static function estimated_consumption_since( $start_date, $facility_id)
	{
		$today = date('Y-m-d');
		$consumpution = StockManager::get_stock_consumption_between($start_date, $today, $facility_id);

		return $consumpution;
	}

	// static function estimated_adjustments_since( $start_date, $facility_id)
	// {
	// 	$today = date('Y-m-d');
	// 	$adjustments = StockManager::get_stock_adjustments_between($start_date, $today, $facility_id);

	// 	return $adjustments;
	// }
	static function get_months_of_stock_on_hand( $facility, $commodity )
	{
        /*
        if ($commodity_id === null){
        	$commodity_id = get_comodity_id_of_test_kit();
        }*/

		//$EID_DATABASE = "eid";
		$sql = "SELECT total_stock_on_hand as current_stock 
		           FROM stock_status JOIN
		           facilities ON stock_status.facility_id=facilities.id JOIN
		           commodities ON stock_status.commodity_id=commodities.id
		              WHERE facility='$facility' AND commodity='$commodity'";
		$db_rows = \DB::select( $sql );

		$available_stock=0;

		foreach ($db_rows as $data) {
			$available_stock = $data->current_stock;
		}

		$get_ids = "SELECT facilities.id as facility_id,commodities.id as commodity_id
		                            FROM facilities,commodities WHERE facility='$facility' AND commodity='$commodity'";
		$id_rows = \DB::select( $get_ids );
		$facility_id=0;
		$commodity_id=0;

		foreach ($id_rows as $data) {
			$facility_id = $data->facility_id;
			$commodity_id = $data->commodity_id;
		}

        
		$facility_amc = StockManager::AMC($facility_id, $commodity_id, $nDays=90);

		if($facility_amc == 0){
			$facility_amc = 1;
		}
		$months_of_stock_on_hand = floor($available_stock/$facility_amc);
		

		return $months_of_stock_on_hand;
	}

	static function reduce_stock_per_batch_sent($facility_id)
	{
		//update stock_status.total_stock_at_hand of that facility by reducing it by 1		
		//$EID_DATABASE = "eid";
		$sql .= "UPDATE stock_status SET total_stock_on_hand = total_stock_on_hand-1 WHERE facility_id = '$facility_id'";

    	DB::unprepared( $sql );
	}

	static function update_stock_on_hand_on_approval($requisition_header_id,$quantity_approved,$facility_id,$commodity_id, $requestors,$requestors_phone_no)
	{
		$update_total_stock_on_hand = "";
        $update_stock_requisition_header = "";
		$today=date('Y-m-d');
		$date_approved=$today;
		//update the total_stock_on_hand as of quantity approved for that commodity at that facility
	    $update_total_stock_on_hand .= "UPDATE stock_status
	                                       SET total_stock_on_hand=total_stock_on_hand+'$quantity_approved'
	                                       WHERE facility_id='$facility_id'
	                               	       AND commodity_id='$commodity_id';";

	    //update stock_requisition_headers
	    $update_stock_requisition_header .= "UPDATE stock_requisition_headers
	                                            SET requestors_name='$requestors',requestors_phone='$requestors_phone_no',date_approved='$date_approved'
	                                            WHERE facility_id='$facility_id'
	                               	            AND id='$requisition_header_id';";

	    DB::unprepared( $update_total_stock_on_hand );
	    DB::unprepared( $update_stock_requisition_header);
	}

	static function update_stock_on_stock_adjustment($stock_adjustment_id, $facility_id, $commodity_id, $change_in_quantity, $adjustment_type)
	{
		$update_total_stock_on_hand = "";

        if($adjustment_type=="INCREASE"){
            $update_total_stock_on_hand = "UPDATE stock_status SET total_stock_on_hand=total_stock_on_hand+'$change_in_quantity',
                                                                    stock_changed_by='STOCK_ADJUSTMENT',stock_change_details_id='$stock_adjustment_id'
                                           WHERE facility_id='$facility_id' AND commodity_id='$commodity_id';";
        }
        elseif($adjustment_type=="DECREASE"){
            $update_total_stock_on_hand = "UPDATE stock_status SET total_stock_on_hand=total_stock_on_hand-'$change_in_quantity',
                                                                    stock_changed_by='STOCK_ADJUSTMENT',stock_change_details_id='$stock_adjustment_id'
                                           WHERE facility_id='$facility_id' AND commodity_id='$commodity_id';";
        }
        DB::unprepared( $update_total_stock_on_hand );
	}

	static function get_facility_id_from_batches_table($batch_id)
	{
		$sql = "SELECT facility_id as f_id FROM batches WHERE id='$batch_id'";
		$facility_id=0;
		$db_rows = \DB::select( $sql );

		foreach ($db_rows as $data) {
			$facility_id=$data->f_id;
		}
		return $facility_id;
	}

	static function decrease_stock_at_facility($batch_id, $sample_id)
	{

		if(StockManager::is_already_decreased( $sample_id ))
			return;
		
		StockManager::decrease_stock_qty($batch_id, $sample_id);
	}


	static function decrease_stock_qty($batch_id, $sample_id)
	{
		$facility_id = StockManager::get_facility_id_from_batches_table($batch_id);

		$sql = "UPDATE stock_status SET total_stock_on_hand=total_stock_on_hand-1 WHERE facility_id='$facility_id' AND is_most_recent_change='YES';";
		\DB::unprepared( $sql );

		StockManager::remember_decrease( $sample_id );// so that we don't double-decrease 
	}

	static function remember_decrease( $sample_id )
	{
		session()->put('sample_' . $sample_id , 'decreased');
	}

	static function is_already_decreased( $sample_id )
	{
		if( session()->has('sample_' . $sample_id ))
			return true;
		else
			return false;
	}

	static function stock_reduction_per_infantTest_taken($batch_id)
	{
		$facility_id = StockManager::get_facility_id_from_batches_table($batch_id);
        $sample_tests_taken = 0;
        $tests_per_unit = 0;

        $sql = "SELECT count(infant_name) as total_samples FROM dbs_samples JOIN batches ON dbs_samples.batch_id=batches.id 
                                        WHERE batches.facility_id='$facility_id'";//sample count from that facility 
        $db_rows = \DB::select($sql);
        foreach ($db_rows as $data) {
			$sample_tests_taken = $data->total_samples;
		}

		$commodities = StockManager::getCommodities();        
        //reduce commodity units as of tests per unit for each commodity 
        foreach($commodities as $commodity){

	        $sql1 = "SELECT tests_per_unit as tpu FROM commodities WHERE id='$commodity->id'";	        
	        $db_rows = \DB::select( $sql1 );
		    foreach ($db_rows as $data) {
			    $tests_per_unit=$data->tpu;
		    }

	        $quantity_reduction = floor($samples_taken/$tests_per_unit);

	        $stock_update = DB::table('stock_status')
		                      ->where(['facility_id'=>$facility_id,'is_most_recent_change'=>'YES'])->decrement('total_stock_on_hand', $quantity_reduction);
        }              
	}

	static function boxes_to_send($commodity, $restock_quantity)
	{
		$quantity_units_per_package=0;

		$sql = "SELECT quantity_per_package as per_pack FROM commodities WHERE commodity='$commodity'";
		$db_rows = \DB::select($sql);
        foreach ($db_rows as $data) {
			$quantity_units_per_package = $data->per_pack;
		}

		$restock_qunatity =  $restock_quantity;
		$boxes_to_send= ceil($restock_qunatity/$quantity_units_per_package);
	}

}