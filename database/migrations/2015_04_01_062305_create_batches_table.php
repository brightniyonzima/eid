<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('batches', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->string('lab', 16)->default('CPHL');// CPHL = Central Public Health Lab

			$table->string('batch_number', 32);
			$table->string('envelope_number', 32);

			$table->integer('entered_by')->unsigned()->default(NULL);
			
			$table->mediumInteger('facility_id')->unsigned();
			$table->string('facility_name', 255)->nullable();
			$table->string('facility_district', 255)->nullable();


			$table->string('senders_name', 75)->nullable();
			$table->string('senders_telephone', 50)->nullable();
			$table->string('senders_comments', 512);
			$table->string('results_return_address', 128);
			$table->enum('results_transport_method', array('POSTA_UGANDA', 'COLLECTED_FROM_LAB') );

			$table->date('date_dispatched_from_facility');
			$table->date('date_rcvd_by_cphl');// CPHL = Central Public Health Lab
			$table->date('date_entered_in_DB')->nullable();
			$table->enum('all_samples_tested', array('YES','NO'))->default('NO');
			$table->date('date_PCR_testing_completed')->nullable();
			$table->date('date_dispatched_to_facility')->nullable();
			$table->date('date_rcvd_at_facility')->nullable();

		// follow-up data:
			$table->string('f_senders_name', 75)->nullable();
			$table->string('f_senders_telephone', 50)->nullable();
			$table->date('f_date_dispatched_from_facility')->nullable();
			$table->date('f_date_rcvd_by_cphl')->nullable();
			$table->enum('f_paediatricART_available', array('YES','NO', 'LEFT_BLANK'))->default('NO');

			$table->unique(array('batch_number')); // remove after importing data from old DB
			// $table->unique(array('batch_number', 'envelope_number')); // re-activate after importing data from old DB
			$table->index('all_samples_tested');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('batches');
	}
}
