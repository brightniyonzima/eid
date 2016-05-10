<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSCworksheetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sc_worksheets', function(Blueprint $table)
		{

			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->integer('CreatedBy')->unsigned();
			$table->dateTime('DateCreated')->default(date('Y-m-d')); // ->default(DB::raw('CURRENT_DATE'));
			$table->date('DateTested')->nullable();

			$table->integer('TestsDoneBy')->nullable()->unsigned();
			
			$table->integer('ResultsExaminer1')->nullable()->unsigned();
			$table->integer('ResultsExaminer2')->nullable()->unsigned();
			$table->integer('ResultsTieBreaker')->nullable()->unsigned();// aka sc_worksheets.ReviewedBy

			$table->enum('Examiner1_ResultsReady', array('YES', 'NO'))->default('NO');			
			$table->enum('Examiner2_ResultsReady', array('YES', 'NO'))->default('NO');			
			$table->enum('TieBreaker_ResultsReady', array('YES', 'NO'))->default('NO');// i.e. final results ready. dispatch ASAP

			$table->string('lab_comments', 75)->nullable();

			// $table->foreign('CreatedBy')->references('id')->on('users');
			// $table->foreign('ReviewedBy')->references('id')->on('users');

		});
		DB::update("ALTER TABLE sc_worksheets AUTO_INCREMENT = 22500");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sc_worksheets');
	}
}