<?php

//
// /*
// 	Double Metaphone:
// 	===================
// 		- populate the database with names (a few; even 20) and their dm()
// 		- create an HTML page with a search box and the names
// 		- when the user makes search, do the needful.




// 	create table names(
// 		name as text not null,
// 		pri_dm as text not null,
// 		sec_dm as text default null
// 	);

// 	names to seed with:
// 	// Paul, Geoffrey, Ina, Linda, Henry, Richard, Doreen, Hillary, Bill, Chelsea, Clinton
// */

// define('DATA_CLERK', 1);

// use Faker\Factory as Faker;
// use Illuminate\Database\Seeder as Seeder;
// use Illuminate\Database\Eloquent\Model;



// class dmSeeder extends Seeder{

// 	public function run(){


// 		Paul, Geoffrey, Ina, Linda, Henry, Richard, Doreen, Hillary, Bill, Chelsea, Clinton

// 		$values = "";
		
// 		$sql_base = "INSERT INTO dm_names (name, pri_dm, sec_dm) VALUES ";

// 		$values .=	"('', dm(''))"; 





// 		User::create(array(
// 				'username'	=> 	'test',
// 				'password' 	=> 	'test',
// 				'type' 		=> 	DATA_CLERK,
// 				'is_admin' 	=> 	false,
// 				'email' 	=> 	'x@y.z',
// 				'family_name'	=> 'Data',
// 				'other_name'	=> 'Clark Kent',
// 			)
// 		);
// 	}
// }