<?php namespace EID\Http\Controllers;

use View;
use EID\Models\Batch;
use EID\Models\Sample;
use DB;

define('DBS_CARD_WITHOUT_SAMPLE', 31);// 31 got as: select id from appendices where appendix = 'DBS card without sample';
define('DBS_SAMPLE_NOT_RECEIVED', 32);// 32 got as: select id from appendices where appendix = 'DBS Sample not  Received';

class SamplesController extends Controller {


	/**
	 * Display a listing of the resource.
	 * GET /samples
	 *
	 * @return Response
	 */
	public function samples()
	{
		 return View::make('sample');
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /pages/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created DBS sample in storage.
	 * POST /pages
	 *
	 * @return Response
	 */
	public function store_sample()
	{


		$ff = \Input::all();
// dd( $ff["rowNumber"] );
		$i = $ff["rowNumber"];
		$id_field = "sample_" . $i;
		$sample_id = $ff[ $id_field ];


		$sample = empty( $sample_id ) ? new Sample : Sample::findOrFail( $sample_id );		
// dd( $sample_id );
		$sample->pos_in_batch = $i;
		$sample->batch_id = $ff["batch_id"];

		$sample->date_dbs_taken = $ff["date_dbs_taken_".$i];
		if( ! empty( $ff["infant_name_".$i] )) $sample->infant_name = $ff["infant_name_".$i];
		if( ! empty( $ff["infant_exp_id_".$i] )) $sample->infant_exp_id = $ff["infant_exp_id_".$i];
		if( ! empty( $ff["infant_gender_".$i] )) $sample->infant_gender = $ff["infant_gender_".$i];
		if( ! empty( $ff["infant_age_".$i] )) $sample->infant_age = trim($ff["infant_age_".$i]) ?: null;
		if( ! empty( $ff["infant_dob_".$i] )) $sample->infant_dob = $ff["infant_dob_".$i];
		if( ! empty( $ff["infant_entryPoint_".$i] )) $sample->infant_entryPoint = $ff["infant_entryPoint_".$i];

		$sample->PCR_test_requested = $ff["PCR_test_requested_".$i];
		$sample->SCD_test_requested = $ff["SCD_test_requested_".$i];

		if($sample->PCR_test_requested === "YES"){

			if( ! empty( $ff["pcr_".$i] ))	$sample->pcr = $ff["pcr_".$i];
			if( ! empty( $ff["non_routine_".$i] ))	$sample->non_routine = $ff["non_routine_".$i];
			
			if( ! empty($ff["infant_contact_phone_".$i])) 	$sample->infant_contact_phone = $ff["infant_contact_phone_".$i];
			if( ! empty($ff["infant_is_breast_feeding_".$i])) $sample->infant_is_breast_feeding = $ff["infant_is_breast_feeding_".$i];

			if( ! empty( $ff["mother_antenatal_prophylaxis_".$i] ))	$sample->mother_antenatal_prophylaxis = $ff["mother_antenatal_prophylaxis_".$i];
			if( ! empty( $ff["mother_delivery_prophylaxis_".$i] ))	$sample->mother_delivery_prophylaxis = $ff["mother_delivery_prophylaxis_".$i];
			if( ! empty( $ff["mother_postnatal_prophylaxis_".$i] )) 	$sample->mother_postnatal_prophylaxis = $ff["mother_postnatal_prophylaxis_".$i];
			if( ! empty( $ff["infant_prophylaxis_".$i] )) 	$sample->infant_prophylaxis = $ff["infant_prophylaxis_".$i];			
		}


		
		$sample->save();

		// dd( session()->all() );

		\StockManager::decrease_stock_at_facility( $sample->batch_id , $sample->id);

		$http_response = array("row_number" => $i, "row_id" => $sample->id);
		return json_encode( $http_response );		
	}


	/**
	 * Store a newly created DBS batch in storage.
	 * POST /pages
	 *
	 * @return Response
	 */
	public function store_batch()
	{
		$ff = (object) \Input::all();

		$batch = empty($ff->id) ? new Batch : Batch::find($ff->id);

// dd( $batch->toArray() );

		$batch->batch_number = $ff->batch_number;
		$batch->envelope_number = $ff->envelope_number;
		$batch->entered_by = \Auth::user()->id;

		$batch->facility_id = $ff->facility_id;
		$batch->facility_name = $ff->facility_name;
		$batch->facility_district = $ff->facility_district;

		$batch->senders_name = $ff->senders_name;
		$batch->senders_telephone = $ff->senders_telephone;
		$batch->senders_comments = $ff->senders_comments;
		$batch->results_return_address = $ff->results_return_address;
		$batch->results_transport_method = $ff->results_transport_method;

		$batch->date_dispatched_from_facility = $ff->date_dispatched_from_facility;
		$batch->date_rcvd_by_cphl = $ff->date_rcvd_by_cphl;
		$batch->date_entered_in_DB = $ff->date_entered_in_DB;

		$batch->save();
		return json_encode( array('batch_id' => $batch->id) );		
	}

	public function record_data_entry_speed( $ff )
	{
		$sql = "";
		$n = count($ff["meta_data"]["durations"]);
		for ($i=0; $i < $n; $i++) { 

			$speed = (object) $ff["meta_data"]["durations"][$i];
			
			$data_type = $speed->data_type;
			$batch_id = $speed->batch_id;
			$sample_id = $speed->sample_id;
			$seconds_used = $speed->seconds_used;
			$data_entry_unix_time = $speed->time_stamp;
			$data_entered_by = \Auth::user()->id;

			$sql .= "\nINSERT INTO data_entry_speed 
						(data_type, batch_id, sample_id, seconds_used, data_entered_by, data_entry_unix_time) 
					VALUES 
						('$data_type', '$batch_id', '$sample_id', '$seconds_used', '$data_entered_by', '$data_entry_unix_time'); ";
		}
		return $sql;
	}


	public function save_original_data( $ff )
	{
		$batch_id = $ff["batch_id"];
		$data_entered_by = \Auth::user()->id;
		$original_data = json_encode($ff);

		$sql = "\nINSERT INTO data_entry_accuracy 
						(batch_id, data_entered_by, original_data) 
					VALUES
						('$batch_id', '$data_entered_by', '$original_data'); ";
		return $sql;	
	}


	public function save_data_changes( $ff )
	{
		$batch_id = $ff["batch_id"];
		$data_checked_by = \Auth::user()->id;
		$changes = json_encode( $ff["changes"] );

		$sql = "UPDATE 	data_entry_accuracy 
					SET data_checked_by = '$data_checked_by', 
						changes = '$changes',
						nChanges = JSON_LENGTH(changes) 
					WHERE batch_id = '$batch_id'; ";

		return $sql;
	}


	public function data_entry_speed()
	{
		$ff = \Request::all();
		$sql = $this->record_data_entry_speed( $ff );


		if(\Request::has('changes')) 
			$sql .= $this->save_data_changes( $ff );
		else
			$sql .= $this->save_original_data( $ff );

		 $sql = "START TRANSACTION; $sql COMMIT;";

// dd($sql);

		\DB::unprepared( $sql );
		return "done";
	}


	/**
	 * Display the specified resource.
	 * GET /pages/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$batch = Batch::findOrFail( $id );
		return View::make('sample', compact('batch') );
	}


	/**
	 * Show the form for editing the specified resource.
	 * GET /pages/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}



	/**
	 * Update the specified resource in storage.
	 * PUT /pages/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /pages/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	/**
	 * Approve EID samples.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function approve($batch_id)
	{

		return View::make('approve', compact('batch_id'));
	}

/**
	 * Show the list of already approved Batches .
	 *
	 * @return Response
	 */
	public function approvedbatches()
	{
		return view('dbs.approvedbatches');
	}

	/**
	 * Show the list of already approved samples .
	 *
	 * @return Response
	 */
	public function approvedsamples($batchID)
	{
        $batches=Batch::getapprovedsamples($batchID);
		return view("dbs.approvedsamples",compact("batches"));
	}


/**
	 * Show the list of rejected Batches .
	 *
	 * @return Response
	 */
	public function rejectedbatches()
	{
		return view('dbs.rejectedbatches');
	}

	




	/**
	 * Show the list of samples pending approval.
	 * GET /batchQ
	 *
	 * @return Response
	 */
	public function pending_batches()
	{
		return view('dbs.pending_batches');
	}

	protected function WorksheetToPDF($worksheet_id){
		
		$view = \View::make('scd.ws', array('ws'=>$worksheet_id, 'hide_menu'=>"yes") );
		$contents = $view->renderSections()['content'];

		$pdf = \PDF::loadHTML($contents);
		// return $pdf->setOrientation('landscape')->download("$worksheet_id".".pdf");
		return $pdf->setOrientation('landscape')->stream("$worksheet_id".".pdf");
	}


	public function scws($sc_worksheet_id)
	{
		if(\Request::has('pp'))
			return $this->WorksheetToPDF($sc_worksheet_id);
		else
			return view('scd.ws');
	}


	/**
	 * Show the list of samples pending approval.
	 * GET /dbsQ/{batch_id}
	 *
	 * @return Response
	 */
	public function pending_samples($batch_id)
	{

		$batch = Batch::findOrFail( $batch_id );
		return view('dbs.pending_samples', compact('batch'));	
	}

	public function get_dbs_data($batch_id)
	{

		$sql = "SELECT original_data FROM data_entry_accuracy WHERE batch_id = '$batch_id'";
		$data = \DB::select( $sql );

		if(count($data) == 0)  {
			$reply = array("dbs_error" => "Batch $batch_id does not exist");
			return json_encode($reply);
		}

		return $data[0]->original_data;
	}




	public function newDates()
	{
		$ff = (object) \Input::all();
		$batch = Batch::findOrFail( $ff->batch_id );

        if( $ff->date_rcvd_by_cphl < $ff->date_dispatched_from_facility ){

			$reply = ["error" => "Check dates: Date recieved CAN NOT be before date dispatched"];
			return json_encode( $reply );
        }
		

        $batch->date_dispatched_from_facility = $ff->date_dispatched_from_facility;
        $batch->date_rcvd_by_cphl = $ff->date_rcvd_by_cphl;

        $batch->save();

		$reply = array("batch_number" => $batch->batch_number);
		return json_encode( $reply );
	}


	public function store_approval($sample_id){
		
		$sample = Sample::findOrFail( $sample_id );
		$batch = Batch::findOrFail( $sample->batch_id );
		$ff = (object) \Input::all();


		$sample->nSpots = $ff->nSpots;
		$sample->infant_name = $ff->infant_name;
		$sample->infant_exp_id = $ff->infant_exp_id;
		$sample->date_dbs_taken = $ff->date_dbs_taken;

		$sample->sample_verified_on = \Carbon::now();
		$sample->sample_verified_by = $ff->sample_verified_by;
		$sample->sample_rejected = $ff->sample_rejected;
		$sample->rejection_reason_id = $ff->rejection_reason_id ?: null;
		$sample->rejection_comments = $ff->reason_other;

		$sample->PCR_test_requested = $ff->PCR_test_requested;
		$sample->SCD_test_requested = $ff->SCD_test_requested;
		$sample->ready_for_SCD_test = $ff->ready_for_SCD_test;
		$sample->sickle_cell_release_code = null;


		if(!empty($ff->infant_age) && !empty($ff->infant_dob)){
            $sample->infant_age = $ff->infant_age;
            $sample->infant_dob = $ff->infant_dob;
		}

		if(	$sample->nSpots == 'Unknown' && 
			$sample->rejection_reason_id != DBS_SAMPLE_NOT_RECEIVED ){

				$err = [
					'error' => 	'If Number of spots = Unknown, it should be ' .
								'rejected with reason: `DBS Sample not  Received`',
			 		'expected' => DBS_SAMPLE_NOT_RECEIVED,
			 		'got' => $sample->rejection_reason_id
			 	];
				return json_encode( $err );
		}


		$sample_rejected_for_PCR = false;
		$sample_rejected_for_SCD = false;
	

		if($sample->sample_rejected == "YES") {
		// 	NOTE: 
		//	To avoid printing unnecessary rejection slips,
		//	a test should not be marked as rejected, until we confirm that it had been requested

			if( $sample->PCR_test_requested == "YES" ){
				$sample_rejected_for_PCR = true;
			}

			if( $sample->SCD_test_requested == "YES" ){ 
				$sample_rejected_for_SCD = true;

				if( $sample->ready_for_SCD_test == "YES" ){	// 	then ignore the rejection and do sickle cell test
															// 	This allows samples rejected for PCR (e.g due to age)
															// 	to be tested for sickle cell.
					$sample_rejected_for_SCD = false;
					$sample->sickle_cell_release_code = "AUTO";
				}
			}
		}

		if ( $sample_rejected_for_PCR ){
		
			$sample->accepted_result = "SAMPLE_WAS_REJECTED";
			$sample->PCR_results_ReleasedBy = \Auth::user()->id;
		
		}else{// reset:
		
			$sample->accepted_result = null;
			$sample->PCR_results_ReleasedBy = null;			
		}


		if( $sample_rejected_for_SCD ){
			
			$sys_rejector = env('SYS_REJECTOR', 1);
			$sickle_cell_rejector = $sample_rejected_for_PCR ? $sys_rejector : \Auth::user()->id;
			
			$sample->SCD_test_result = "SAMPLE_WAS_REJECTED";
			$sample->SCD_results_ReleasedBy = $sickle_cell_rejector;
		
		}else{// reset:

			$sample->SCD_test_result = null;
			$sample->SCD_results_ReleasedBy = null;
		}


		$sample->save();

	//
	// save batch data:
	//
		$reply = [];
		$reply["sample_id"] = $sample_id;
		$TODAY = date("Y-m-d");


		if( $ff->batch_number != $batch->batch_number ){// batch number was changed

			$reply["batch_number"] = $ff->batch_number;
			
			$batch->batch_number = $ff->batch_number;
			$batch->tests_requested = $ff->tests_requested;
			$batch->save();
		}

		if($ff->all_rejected == "YES"){ /* 	This means results can be released right now, since results are the
											same for every sample in this batch (each result == "SAMPLE_WAS_REJECTED")

											But...

											EID Lab is very strict, so sometimes Sickle Cell lab will accept 
											samples rejected by EID Lab e.g. if age is missing EID lab will reject 
											but SC lab will accept.

											In such cases, $sample_rejected_for_SCD == false and so we MUST NOT release the 
											sickle cell results now. We must wait for the tests to be run.

											NB: 
											if PCR_results_released == "YES", that batch will appear for EID results printing.
											if SCD_results_released == "YES", that batch will appear for SCD results printing.
											A "YES" at this point means the result will be "SAMPLE_WAS_REJECTED".
										*/

			$batch->PCR_results_released = "NO";
			$batch->SCD_results_released = "NO";

			if($sample_rejected_for_PCR){
				$batch->PCR_results_released = "YES";
				$batch->date_PCR_testing_completed = $TODAY;
			}

			if($sample_rejected_for_SCD){
				$batch->SCD_results_released = "YES";
				$batch->date_SCD_testing_completed = $TODAY;
			}


			$batch->tests_requested = $ff->tests_requested;
			$batch->all_samples_rejected = "YES";

			$batch->save();
		}
		return json_encode( $reply );
	}


	public function batchNo_unique()
	{
	    $batch_number = \Request::get('batch_number');
	    $envelope_number = \Request::get('envelope_number');

	    $batch_found = $this->find_batch_in_database($batch_number, $envelope_number);

	    if($batch_found)
	    	$reply = array("already_exists" => true);
	    else
	    	$reply = array("already_exists" => false);

	    return json_encode( $reply );
	}


// public function buffer()
// {
// // if nSpots == unKnown, sample must be rejected with reason DBS_SAMPLE_NOT_RECEIVED
// // if nSpots < 4, sample must be rejected for PCR

// 	if(	$sample->nSpots == 'Unknown' || $sample->nSpots < 4){
		
// 	}

// 	if(	$sample->nSpots == 'Unknown' && 
// 		$sample->rejection_reason_id != DBS_SAMPLE_NOT_RECEIVED ){

// 			$err = [
// 				'error' => 	'If Number of spots = Unknown, it should be ' .
// 							'rejected with reason: `DBS Sample not  Received`',
// 		 		'expected' => DBS_SAMPLE_NOT_RECEIVED,
// 		 		'got' => $sample->rejection_reason_id
// 		 	];
// 			return json_encode( $err );
// 	}


// 	if(	$sample->nSpots < 4 && 
// 		$sample->rejection_reason_id != DBS_CARD_WITHOUT_SAMPLE ){

// 			$err = [
// 				'msg' => 'Unexpected Rejection Reason for Sample',
// 		 		'expected' => DBS_CARD_WITHOUT_SAMPLE,
// 		 		'got' => $sample->rejection_reason_id
// 		 	];
// 			return json_encode( $err );
// 	}
// }
	public function find_batch_in_database($batch_number, $envelope_number)
	{
	    $sql = "SELECT COUNT(id) AS nBatches FROM batches 
	    			WHERE 	batch_number = '$batch_number' 
	    			AND 	envelope_number = '$envelope_number' 
	    			LIMIT 	1";

	    $rows = \DB::select($sql);
	    $found = $rows[0]->nBatches;

	    if($found)
	    	return true;
	    else
	    	return false;
	}


	public function dispatchList()
	{
		return view('lab.dispatch_list');
	}

	public function dispatchList_SC()
	{
		return view('lab.scd_dispatch_list');
	}

	public function followUp()
	{
		return view('lab.follow_up');
	}



	public function show_results()
	{
		return view('eid_results');
	}

	public function review_results($ws_id)
	{
		return view('lab.eid_review', array('ws_id' => $ws_id));
	}

	public function release_eid_results()
	{
		$this->release_eid_batches();
		return view('lab.worksheet_list');
	}

	public function release_eid_batches()
	{
		$sql = "";
		$today = date("Y-m-d");
		$released = \Request::all();

		$batches_to_retain = $released["no"];
		$batches_to_release = $released["yes"];		
		$there_are_batches_to_retain = !empty($batches_to_retain);
		$there_are_batches_to_release = !empty($batches_to_release);

		if( $there_are_batches_to_retain ){
			$sql .= "UPDATE batches 
						
						SET PCR_results_released = 'NO', 
							date_PCR_testing_completed = NULL 
						
						WHERE id IN ($batches_to_retain) ;";
		}

		if( $there_are_batches_to_release ){
			$sql .= "UPDATE batches 

						SET PCR_results_released = 'YES', 
							date_PCR_testing_completed = '$today'  

						WHERE id IN ($batches_to_release) ;";
		}
		
		\DB::unprepared($sql);
	}


	public function show_rejected_results()
	{
		return view('rejected_results');
	}

	public function sc_result_slips()
	{
		return view('sc_result_slips');
	}


/* These 2 eron() functions are used for printing backlog of SC results */
	public function eron()
	{
		return view('eron');
	}

	public function eron_envelopes()
	{
		return view('eron_envelopes');
	}



	public function saveARTdata()
	{
		$ff = array_dot( \Request::all() );
		// dd($ff);
		$nRows = $ff["nRows"];
		$sql = "";

		for ($i=0; $i < $nRows; $i++) { 
			
			

			$sql .= "UPDATE dbs_samples " .
					"	SET f_results_rcvd_at_facility = '" . ($ff["f_results_rcvd_at_facility.$i"] ?: 'LEFT_BLANK') . "'," .
					"		f_results_collected_by_caregiver = '" . ($ff["f_results_collected_by_caregiver.$i"] ?: 'LEFT_BLANK') . "'," .
					"		f_date_results_collected = '" . (new \Carbon ($ff["f_date_results_collected.$i"])) . "'," .
					"		f_ART_initiated = '" . ($ff["f_ART_initiated.$i"] ?: 'LEFT_BLANK')  . "'," .
					"		f_date_ART_initiated = '" . (new \Carbon($ff["f_date_ART_initiated.$i"])) . "'," .
					"		f_reason_ART_not_initated = " . (empty($ff["f_reason_ART_not_initated.$i"]) ? "NULL" : "'".$ff["f_reason_ART_not_initated.$i"]."'") . "," .
					"		f_ART_number = '" . $ff["f_ART_number.$i"] . "'," .
					"		f_infant_referred = " . (empty($ff["f_infant_referred.$i"]) ? "NULL" : "'".json_decode($ff["f_infant_referred.$i"])->facility_id."'") . "," .
					"		f_facility_referred_to = " . (empty($ff["f_facility_referred_to.$i"]) ? "NULL" : "'".json_decode($ff["f_facility_referred_to.$i"])->facility_id."'") . 
					"	WHERE 	id = '" . $ff["sample_id.$i"] . "';" ;

		}


	  	$sql .= "UPDATE batches SET f_paediatricART_available = '" . ($ff['f_paediatricART_available']?: 'LEFT_BLANK') . "'," .
	  			"					f_senders_name =  '" . $ff['f_senders_name'] . "'," .
	  			"					f_senders_telephone =  '" . $ff['f_senders_telephone'] . "'," .
	  			"					f_date_dispatched_from_facility = '" . (new \Carbon($ff['f_date_dispatched_from_facility'])) . "'," .
	  			"					f_date_rcvd_by_cphl = '" . (new \Carbon($ff['f_date_rcvd_by_cphl'])) . "'," .
	  			"					date_dispatched_to_facility = '" . (new \Carbon($ff['date_dispatched_to_facility'])) . "'," .
	  			"					date_rcvd_at_facility = '" . (new \Carbon($ff['date_rcvd_at_facility'])) . "'";

		\DB::unprepared($sql);
		return \Redirect::back()->withFlashMessage('Successfully saved');
	}

	public function list_batches(){
		$batches=Batch::getBatches();
		return view("dbs.batches",compact("batches"));
	}

}