<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	**/
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');

			// login data:
			$table->string('username', 32)->unique();
			$table->string('password', 72);
			$table->integer('type')->unsigned();// foreign key: see below 
			$table->tinyInteger('is_admin')->default(false);

			// bio data:
			$table->string('family_name', 32)->nullable();
			$table->string('other_name', 32)->nullable();
			$table->string('signature', 64)->nullable();

			// contact data:
			$table->string('email', 64);
			$table->string('telephone', 20)->nullable();
			$table->string('telephone2', 20)->nullable();
			$table->smallInteger('organization_id')->unsigned()->nullable();;

			$table->integer('facilityID')->nullable()->default(NULL);
			$table->integer('hubID')->nullable()->default(NULL);
			$table->integer('ipID')->nullable()->default(NULL);
			$table->tinyInteger('deactivated')->default(0);
			$table->tinyInteger('loggedon')->default(0);
			$table->Date('created')->nullable();
			$table->integer('createdby')->nullable();


			$table->string('remember_token')->nullable();

			$table->index( 'username' );
			$table->foreign('type')->references('id')->on('user_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}
}
