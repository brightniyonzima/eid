<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	private $tables = [
		
		// 'users',
		// 'user_types',

					// 'dm_names',

		'batches',
		'dbs_samples',

		'lab_worksheets',

		'worksheet_index',

		'sc_worksheets',
		'sc_worksheet_index'

	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		$this->truncateTables();

		DB::unprepared("ALTER TABLE batches AUTO_INCREMENT = 200200 ");		
		DB::unprepared("ALTER TABLE dbs_samples AUTO_INCREMENT = 700700 ");		

		DB::unprepared("ALTER TABLE lab_worksheets AUTO_INCREMENT = 100100 ");		
		DB::unprepared("ALTER TABLE worksheet_index AUTO_INCREMENT = 300300 ");		

		DB::unprepared("ALTER TABLE sc_worksheets AUTO_INCREMENT = 400400 ");		
		DB::unprepared("ALTER TABLE sc_worksheet_index AUTO_INCREMENT = 500500 ");


		// $this->call('UserTypeSeeder');
		// $this->call('UserSeeder');

		// $this->call('BatchSeeder');


		// // $this->call('WorksheetRunner');		

		// // $this->call('dmSeeder');
	}

	protected function truncateTables()
	{
		DB::statement('set FOREIGN_KEY_CHECKS=0');
		foreach ($this->tables as $tableToSeed) {
			DB::table($tableToSeed)->truncate();
		}
		DB::statement('set FOREIGN_KEY_CHECKS=1');
	}
}
