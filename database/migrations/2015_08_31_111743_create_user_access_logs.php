<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAccessLogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('user_access_logs', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';			
			$table->increments('id');		
			$table->string('username', 32);
			$table->datetime('log_time');

			$table->string('url_accessed', 5024);

			$table->string('resource_accessed', 64)->nullable();
			$table->index('username');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('user_access_logs');
	}

}
