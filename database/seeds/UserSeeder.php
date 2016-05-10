<?php

define('DATA_CLERK', 1);

use Faker\Factory as Faker;
use Illuminate\Database\Seeder as Seeder;
use Illuminate\Database\Eloquent\Model;
use EID\Models\User as User;

class UserSeeder extends Seeder{

	public function run(){
				
		User::create(array(
				'username'	=> 	'admin',
				'password' 	=> 	'admin',
				'type' 		=> 	DATA_CLERK,
				'is_admin' 	=> 	true,
				'email' 	=> 	'admin@test.cphl',
				'family_name'	=> 'Administrator',
				'other_name'	=> 'Kent (Data) Clark',
			)
		);
	}
}