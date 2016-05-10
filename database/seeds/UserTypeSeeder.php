<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder as Seeder;
use Illuminate\Database\Eloquent\Model;


class UserTypeSeeder extends Seeder{

	public function run(){
		
		UserType::create(array('type' => "DATA_CLERK", 'descr' => "default-user: do not edit"));
	}
}