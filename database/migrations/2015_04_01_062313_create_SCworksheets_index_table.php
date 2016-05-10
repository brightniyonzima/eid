<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateSCworksheetsIndexTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sc_worksheet_index', function(Blueprint $table)
		{

			$table->engine = 'InnoDB';
			
			$table->integer('worksheet_number')->unsigned();
			$table->integer('sample_id')->unsigned();

			$table->char('position', 4);
			$table->string('result1', 15)->nullable();// consider the pros/cons of using enum instead
			$table->string('result2', 15)->nullable();
			$table->string('tie_break_result', 15)->nullable();

			$table->unique(array('worksheet_number', 'sample_id'));
			$table->unique(array('worksheet_number', 'position'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sc_worksheet_index');
	}
}