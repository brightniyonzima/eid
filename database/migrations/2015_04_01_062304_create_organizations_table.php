<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organizations', function(Blueprint $table)
		{

			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('name', 50);
			$table->integer('type');


			$table->integer('contact_person');
			$table->integer('contact_person2');


			$table->string('ip_classification', 5);
			$table->string('telephone', 20);
			$table->string('telephone2', 20);
			$table->string('email', 64);
			$table->string('email2', 64);
			$table->string('postal_address', 40);
			$table->string('physical_address', 40);

			// $table->timestamps();

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('organizations');
	}
}