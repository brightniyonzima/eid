<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoginSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('login_session', function(Blueprint $table)
		{
			
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->bigInteger('ip');
			$table->integer('user_id');
			$table->string('browser', 32);
			$table->string('platform', 32);
			$table->tinyInteger('browser_is_mobile')->unsigned();
			$table->tinyInteger('browser_major_version')->unsigned();
			$table->tinyInteger('browser_minor_version')->unsigned();
			$table->integer('login_timestamp')->unsigned();
			$table->integer('logout_timestamp')->unsigned()->nullable();

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('login_session');
	}

}
