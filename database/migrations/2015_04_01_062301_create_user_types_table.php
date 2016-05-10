<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_types', function(Blueprint $table)
		{

		//	get data for "id" from users.account in old EID database
		// 	populate "type" and "descr" using old index.php (see $_SESSION['accounttype'] )

		// 	i have decided to retain an "id" column, although "type" is unique and could be an ID.
		//	This is to avoid headaches in case users change the value used for any "type"
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('type', 32)->unique();
			$table->string('descr', 256);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_types');
	}

}


/*
	// we should seed the DB with a default user_type.
	//	probably like this...

	class UserTableSeeder extends Seeder {
		public function run()
		{
			$default_type = UserType::firstOrCreate(['id' => '-1',
														'type' => 'NONE',
														'descr' => 'SYSTEM-USER: DONT CHANGE ANYTHING!']);
		}
	}


// call it like this in up()
		$this->call('UserTableSeeder');
		$this->command->info('User table seeded!');
**/