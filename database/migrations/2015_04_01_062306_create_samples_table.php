<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSamplesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dbs_samples', function(Blueprint $table)
		{
/*
	This table is due for refactoring.
	It's a very important table, so don't touch it unless you know what you are doing!
	Even if you know what you are doing, the consequences could be far-reaching.
	You've been warned.


	My initial ideas for refactoring it are:
		1) First group of columns = Match the rows of a DBS form holding SC data
		2) Second group of columns = PMTCT data for EID data (NB: EID columns = SC columns + PMTCT columns)
		3) Third group = Computed or Utility fields e.g. sickle_cell_release_code, accepted_result, etc
		4) Fourth group = Follow-up data
		5) Fifth group = Indexes

	After the table is well sorted, start eliminating columns e.g
		1) Obsolete/extinct columns like migrated_to_old_schema, worksheet_1/2/3/4/5, etc 
		2) Columns that belong to their own table like follow-up columns (but NOT test_1_result ... test_5_result!)
		3) Update the index choices based on actual usage

	Richard K. Obore, 10th Oct 2015
*/			
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('batch_id')->unsigned();
			$table->tinyInteger('pos_in_batch')->unsigned();


		// sample verification details:
			$table->integer('sample_verified_by')->nullable()->default(NULL);
			$table->tinyInteger('nSpots')->nullable()->default(NULL);
			$table->enum('sample_rejected', array('YES','NO', 'NOT_YET_CHECKED'))->default('NOT_YET_CHECKED');
			$table->tinyInteger('rejection_reason_id')->unsigned()->nullable();
			$table->string('rejection_comments', 256)->nullable();
			$table->string('sickle_cell_release_code', 10)->nullable()->default(NULL);

			

		// infant details:
			$table->string('infant_name');
			$table->string('infant_exp_id', 16)->nullable()->default(NULL);
			$table->enum('infant_gender', array('MALE', 'FEMALE', 'NOT_RECORDED'))->default('NOT_RECORDED');
			$table->string('infant_age', 30)->nullable();
			$table->date('infant_dob')->nullable();
			$table->enum('infant_is_breast_feeding', array('YES','NO','UNKNOWN'))->default('UNKNOWN');
			$table->tinyInteger('infant_entryPoint')->unsigned()->nullable()->default(NULL);
			$table->string('infant_contact_phone', 20)->nullable();


		// prohylaxis details:
			$table->tinyInteger('mother_antenatal_prophylaxis')->unsigned()->nullable()->default(NULL);
			$table->tinyInteger('mother_delivery_prophylaxis')->unsigned()->nullable()->default(NULL);
			$table->tinyInteger('mother_postnatal_prophylaxis')->unsigned()->nullable()->default(NULL);
			$table->tinyInteger('infant_prophylaxis')->unsigned()->nullable()->default(NULL);
			
		
		// important dates:
			$table->date('date_dbs_taken')->nullable();
			$table->date('date_data_entered')->default(Carbon::now());
			$table->date('date_dbs_tested')->nullable();
			$table->date('date_results_entered')->nullable();
			$table->date('date_results_printed')->nullable();

		// lab data:
			$table->enum('SCD_test_requested', array('NO', 'YES'))->default('NO');
			$table->enum('ready_for_SCD_test', array('YES','NO', 'TEST_NOT_NEEDED', 'TEST_ALREADY_DONE'))->default('NO');	
			$table->enum('repeated_SC_test', 	array('YES', 'NO'))->default('NO');

			$table->enum('PCR_test_requested', array('NO', 'YES'))->default('YES');
			$table->enum('pcr', array('FIRST', 'SECOND', 'NON_ROUTINE', 'UNKNOWN'))->default('UNKNOWN');
			$table->enum('non_routine', array('R1', 'R2'))->nullable()->default(NULL);
			$table->enum('migrated_to_old_schema', array('YES', 'NO'))->default('NO');
			$table->string('lab_comment', 125)->nullable();


		// add these columns to hold results data. TO-DO: move to separate table.
			$table->enum('in_workSheet', array('YES','NO'))->default('NO');			
			$table->string('physical_location', 45)->nullable();// ID of zip-lock bag used for sample storage


			$table->mediumInteger('worksheet_1')->unsigned()->nullable()->default(NULL);
			$table->mediumInteger('worksheet_2')->unsigned()->nullable()->default(NULL);
			$table->mediumInteger('worksheet_3')->unsigned()->nullable()->default(NULL);
			$table->mediumInteger('worksheet_4')->unsigned()->nullable()->default(NULL);
			$table->mediumInteger('worksheet_5')->unsigned()->nullable()->default(NULL);

			$table->enum('test_1_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);
			$table->enum('test_2_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);
			$table->enum('test_3_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);
			$table->enum('test_4_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);
			$table->enum('test_5_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);

			// removed nFailedTests, below, because it can be computed just like nTestsDone is computed in generateSQL()
			// $table->tinyInteger('nFailedTests')->unsigned()->default(0);

			$table->enum('testing_completed', array('YES','NO'))->default('NO');
			$table->enum('accepted_result', array('POSITIVE', 'NEGATIVE', 'INVALID', 'SAMPLE_WAS_REJECTED'))->nullable()->default(NULL);
			$table->enum('SCD_test_result', array('NORMAL', 'VARIANT', 'CARRIER', 'SICKLER', 'FAILED', 'SAMPLE_WAS_REJECTED'))->nullable()->default(NULL);

			$table->integer('PCR_results_ReleasedBy')->nullable()->default(NULL);
			$table->integer('SCD_results_ReleasedBy')->nullable()->default(NULL);

		// add follow-up data:
			$table->enum('f_results_rcvd_at_facility', array('YES','NO', 'LEFT_BLANK'))->nullable()->default(NULL);
			$table->enum('f_results_collected_by_caregiver', array('YES','NO', 'LEFT_BLANK'))->nullable()->default(NULL);
			$table->date('f_date_results_collected')->nullable()->default(NULL);
			$table->enum('f_ART_initiated', array('YES','NO', 'LEFT_BLANK'))->nullable()->default(NULL);
			$table->date('f_date_ART_initiated')->nullable()->default(NULL);
			$table->tinyInteger('f_reason_ART_not_initated')->unsigned()->nullable()->default(NULL);
			$table->string('f_ART_number', 12)->nullable()->default(NULL);
			$table->enum('f_infant_referred', array('YES','NO'))->nullable()->default(NULL);
			$table->mediumInteger('f_facility_referred_to')->unsigned()->nullable()->default(NULL);


		// add indexes			
			$table->index('in_workSheet');
			$table->index('testing_completed');
			$table->index('accepted_result');			
			$table->index('ready_for_SCD_test');
			

			$table->unique(array('batch_id', 'pos_in_batch'));
			$table->foreign('batch_id')->references('id')->on('batches');			
		});

		Schema::create('pcr_dummy_results', function(Blueprint $table){ /* for testing only! do not use in production. */

			$table->engine = 'InnoDB';

			$table->integer('sample_id')->unsigned();
			$table->integer('worksheet_number')->unsigned();
			$table->tinyInteger('nTestsDone')->unsigned()->default(0);
			
			$table->enum('expected_test_1_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);
			$table->enum('expected_test_2_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);
			$table->enum('expected_test_3_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);
			$table->enum('expected_test_4_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);
			$table->enum('expected_test_5_result', array('POSITIVE', 'LOW_POSITIVE', 'NEGATIVE', 'FAIL'))->nullable()->default(NULL);

			$table->enum('expected_final_result', array('POSITIVE', 'NEGATIVE', 'INVALID'))->nullable()->default(NULL);

			$table->unique(array('worksheet_number', 'sample_id'));
		});		
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pcr_dummy_results');
		Schema::drop('dbs_samples');
	}
}
