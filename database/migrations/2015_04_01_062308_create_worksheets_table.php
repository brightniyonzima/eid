<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksheetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lab_worksheets', function(Blueprint $table)
		{

			$table->engine = 'InnoDB';
			
			$table->increments('id');

			$table->dateTime('DateCreated')->default(date('Y-m-d')); // ->default(DB::raw('CURRENT_DATE'));
			$table->date('DateTested')->nullable();
			$table->date('DateReviewed')->nullable();

			$table->integer('CreatedBy')->unsigned();
			$table->string('Kit_Number', 25);
			$table->string('Kit_LotNumber', 25); 
			$table->date('Kit_ExpiryDate');
			$table->enum('HasResults', array('YES', 'NO'))->default('NO');
			$table->enum('PassedReview', array('YES', 'NO','NOT_YET_REVIEWED'))->default('NOT_YET_REVIEWED');
			$table->integer('ReviewedBy')->nullable()->unsigned();
			$table->string('comments', 25)->nullable();


			$table->index(array('HasResults', 'PassedReview'));
			// $table->foreign('CreatedBy')->references('id')->on('users');
			// $table->foreign('ReviewedBy')->references('id')->on('users');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lab_worksheets');
	}

}
