<?php

class Lab{
	
	protected static function talk(){
		$b = Batch::find(1);

		printf("talk: hello cyrax<br><br>");
		printf("<pre>" . json_encode($b) . "</pre>");
	}

	
	public static function get_Samples_from_newDB(){


		$SQL = 	"SELECT		*, dbs_samples.id as accession_number 

					FROM 	dbs_samples, batches 

					WHERE 	batches.id = '3'
					AND 	dbs_samples.batch_id = batches.id
					AND 	dbs_samples.migrated_to_old_schema='NO' 
					AND 	dbs_samples.sample_rejected IN ('YES', 'NO')"; /* i.e. sample has been checked */

		$result = DB::select($SQL);

		foreach ($result as $new_sample) {

			$mother_id = self::saveMotherData( $new_sample );
			$infant_id = self::saveInfantData( $new_sample, $mother_id );
			$sample_id = self::saveSampleData( $new_sample, $infant_id );
		}
	}

	protected static function saveMotherData( $sample ){
		
		$mum = new Mother;

		$mum->facility = $sample->facility_id;
		$mum->entry_point = $sample->infant_entryPoint;
		$mum->caregiverphn = $sample->infant_contact_phone;
		$mum->feeding = $sample->infant_is_breast_feeding;
		$mum->antenalprophylaxis = $sample->mother_antenatal_prophylaxis;
		$mum->deliveryprophylaxis = $sample->mother_delivery_prophylaxis;
		$mum->postnatalprophylaxis = $sample->mother_postnatal_prophylaxis;
		$mum->status = 0;// unused in old DB: 99.99% of values are 0. redundant, since all mothers in EID program are assumed HIV+
		$mum->batchno = $sample->batch_number;
		$mum->labtestedin = 1;	// defaults to 1: CPHL's EID lab
		$mum->fcode = 0;	// defaults to 0. all values are 0. usage is unclear.
		$mum->synched = 0; 	// defaults to 0. all values are 0. usage is unclear.

		$mum->save();

		return $mum->id;
	}

	protected static function saveInfantData($sample, $mother_id){

		$infant_data = array(
			"ID" => $sample->accession_number,//	NB: This table has a separate auto-increment field called AutoID
			"infantname" => $sample->infant_name,
			"infantid" => $sample->infant_exp_id,
			"mother" => $mother_id,
			"age" => $sample->infant_age,
			"gender" => $sample->infant_gender,
			"prophylaxis" => $sample->infant_prophylaxis,
			"labtestedin" => 1, // defaults to 1: CPHL's EID lab
			"datecreated" => (date('Y-m-d'))
		);


		$infant_id = DB::table('patients')->insertGetId( $infant_data );

		return $infant_id;

	}

	protected static function saveSampleData($src_sample, $infant_id){

		$dest_sample = new OldSample;


		$dest_sample->accessionno = $src_sample->accession_number;
		$dest_sample->pcr = $src_sample->pcr;
		$dest_sample->patient = $infant_id;
		$dest_sample->batchno = $src_sample->batch_number;
		$dest_sample->envelopeno = $src_sample->envelope_number;
		$dest_sample->parentid = 0; // WARNING: this is the sample's parent, not the infant's parent!
		$dest_sample->datecollected = $src_sample->date_dbs_taken;
		$dest_sample->datereceived = $src_sample->date_rcvd_by_cphl;
		$dest_sample->datedispatchedfromfacility = $src_sample->date_dispatched_from_facility;
		$dest_sample->facility = $src_sample->facility_id;
		$dest_sample->comments = $src_sample->senders_comments;
		$dest_sample->testtype = $src_sample->test_type;// unused
		$dest_sample->nonroutine = NULL; // unused. all values are NULL
	
		$dest_sample->Inworksheet = 0;// default = 0, i.e. "NO"
		$dest_sample->receivedstatus = $src_sample->sample_rejected;
		$dest_sample->result = NULL;
		$dest_sample->status = 1;
		$dest_sample->Flag = 1;	// default(1). usage+meaning is unclear



		printf("\n\nsaved sample with {envelope_number, batch_number} => {" . 
									$src_sample->envelope_number . " , " . $src_sample->batch_number . " }");

		$dest_sample->save();



		$source_sample = Sample::find($src_sample->accession_number);
		$source_sample->migrated_to_old_schema = "YES";
		$source_sample->update();


		printf("Sample with Accession# $src_sample->accession_number: migration completed...");
		return $dest_sample->id;

	}
}