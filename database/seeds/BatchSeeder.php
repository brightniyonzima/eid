<?php

define('SAMPLES_NEEDED_TO_RUN_ALL_TESTS', 200); /* Any bigger number will also work. See WorksheetRunner's constructor */
define('SAMPLES_NEEDED_FOR_THIS_WORKSHEET', 22);
define('SAMPLES_PER_BATCH', 7);
define('DEFAULT_USER', '1');// remove this as soon as user-login module is complete


use Faker\Factory as Faker;
use Illuminate\Database\Seeder as Seeder;
use Illuminate\Database\Eloquent\Model;
use EID\Models\Batch as Batch;
use EID\Models\Sample as Sample;


class BatchSeeder extends Seeder{
	/*
		Creates batches and samples needed by WorksheetRunner.
			This involves:
				- create 65 samples [NB: 65 == SAMPLES_NEEDED_TO_RUN_ALL_TESTS]
				- approve the samples
	*/
	private $faker;
	private $worksheets = [];
	private $totSamplesCreated;
	private $carbonDate;


	public function run(){

		DB::unprepared("ALTER TABLE dbs_samples AUTO_INCREMENT = 700700 ");		
		Eloquent::unguard();
		
		$this->faker = Faker::create();
		$this->carbonDate = Carbon::now();
		$this->totSamplesCreated = 0;

		while($this->totSamplesCreated < SAMPLES_NEEDED_TO_RUN_ALL_TESTS){

			$batch_id = $this->createBatch();
			$this->createSamples($batch_id);
		}
	}

 
	protected function createBatch(){
		
		$batch = new Batch;

		$batch->batch_number = $this->faker->numberBetween(42500, 425050);
		$batch->envelope_number = $this->faker->numberBetween(2015050500, 2015050599);

		$batch->facility_id = $this->faker->numberBetween(12, 1995);
		$batch->facility_name = 'Raphael Test Health Facility';
		$batch->facility_district = 0;

		$batch->senders_name = $this->faker->name;
		$batch->senders_telephone = $this->faker->numberBetween(256772100100, 256782900900);
		$batch->senders_comments = $this->faker->sentence( $this->faker->numberBetween(0, 7) );
		$batch->results_return_address = $this->faker->address;
		$batch->results_transport_method = 'POSTA_UGANDA';

		$batch->date_dispatched_from_facility = $this->faker->dateTimeBetween('-10 days', 'now');
		$batch->date_rcvd_by_cphl = $this->faker->dateTimeBetween('-7 days', 'now');
		$batch->date_entered_in_DB = $this->faker->dateTimeBetween('-10 days', $batch->date_rcvd_by_cphl);

		$batch->save();

		return $batch->id;
	}


	protected function createSamples($batch_id, $PCR=true, $SCD=false){

		$samples_on_this_worksheet = $this->totSamplesCreated % SAMPLES_NEEDED_FOR_THIS_WORKSHEET;
		$samples_needed = SAMPLES_NEEDED_FOR_THIS_WORKSHEET - $samples_on_this_worksheet;
		$samples_to_create = $samples_needed <= SAMPLES_PER_BATCH ? $samples_needed : $this->faker->numberBetween(1, SAMPLES_PER_BATCH);

		for($i=1; $i <= $samples_to_create; $i++){
			
			$this->createOneSample($batch_id, $i);
			$this->totSamplesCreated++;
		}
	}


	protected function createOneSample($batch_id, $pos){
		static $patient_id = 0;
		static $PCR = [true, true, true, false, true, true, true, false, true, true, true, false, true, true, true];
		// static $SCD = [true, false, true, false, true, false, true, false, true, false, true, false, true, false, false];
		static $SCD = [true, true, true, true, false];// this gives higher chance of SCD test

		$sample = new Sample;
		
		$sample->pos_in_batch = $pos;
		$sample->batch_id = $batch_id;

		$sample->date_dbs_taken = $this->faker->dateTimeBetween('-17 days', '-10 days');
		$sample->infant_name = $this->faker->name;
		$sample->infant_exp_id = $patient_id++;
		$sample->infant_gender = $this->faker->numberBetween(1,2);
		$infant_age = $this->faker->numberBetween(1, 18);
		$sample->infant_dob = date("Ymd", strtotime("-$infant_age months"));
		$sample->infant_age = $infant_age . " months";
		$sample->infant_contact_phone = $this->faker->optional()->numberBetween(256702100100, 256712900900);
		$sample->infant_entryPoint = $this->faker->numberBetween(1,7);
		$sample->sample_verified_by = DEFAULT_USER;

		$i = $this->faker->numberBetween(0, (count($PCR)-1));
		$j = $this->faker->numberBetween(0, (count($SCD)-1));
		
		$sample->PCR_test_requested = $PCR[$i] ? "YES" : "NO";
		$sample->SCD_test_requested = $SCD[$j] ? "YES" : "NO";

		if($sample->PCR_test_requested === "YES"){

			$sample->pcr = $this->faker->numberBetween(1, 4);
			$sample->infant_is_breast_feeding = $this->faker->numberBetween(1, 3);

			$sample->mother_antenatal_prophylaxis = $this->faker->numberBetween(2, 4);
			$sample->mother_delivery_prophylaxis = $this->faker->numberBetween(2, 4);
			$sample->mother_postnatal_prophylaxis = $this->faker->numberBetween(2, 4);
			$sample->infant_prophylaxis = $this->faker->numberBetween(2, 4);
		}

		$sample->sample_rejected = 'NO';// approve the sample
		$sample->nSpots = 5;

		$sample->save();

		return $sample->id;
	}
}