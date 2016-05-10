<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorksheetsIndexTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('worksheet_index', function(Blueprint $table)
		{

			$table->engine = 'InnoDB';
			
			$table->integer('worksheet_number')->unsigned();
			$table->integer('sample_id')->unsigned();
			$table->tinyInteger('pos_in_workSheet')->unsigned();			

			$table->unique(array('worksheet_number', 'sample_id'));
			$table->index('sample_id');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('worksheet_index');
	}

}
