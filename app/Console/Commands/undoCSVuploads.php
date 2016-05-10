<?php namespace EID\Console\Commands;

use \DB as DB;
use EID\Models\User as User;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

define('USE_OLD_DATABASE', 1);
define('USE_NEW_DATABASE', 2);
define('UNKNOWN_DATABASE', 3);


class undoCSV extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'etl:undoCSV';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Load data from old EID database into revamped one';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->comment("Welcome to the CSV destroyer ====== PLEASE BE CAREFUL =====...\n\n");
		// $this->migrate_users_data();
		// $this->migrate_batches_data();
		// $this->migrate_samples_data();

		
		$sql = "SELECT  infant_name, infant_exp_id, worksheet_number, batch_id, batch_number, sample_id, PCR_test_requested, SCD_test_requested , sample_rejected FROM    dbs_samples, batches, worksheet_index WHERE   dbs_samples.id = worksheet_index.sample_id AND     dbs_samples.batch_id = batches.id and worksheet_index.worksheet_number IN (select distinct worksheet_number from worksheet_index)";

		$data = $this->DB_select(USE_NEW_DATABASE, $sql);
		

		$samples = [];
		$batches = [];
		$worksheets = [];

		foreach ($data as $sample) {
			$samples[ $this_sample->sample_id ] = "doesnt_matter";
			$batches[ $this_sample->batch_id ] = "doesnt_matter";
			$worksheets[ $this_sample->worksheet_number ] = "doesnt_matter";
		}


		$sk = array_keys($samples);
		$sample_IDs = implode(", ", $sk);
		$dbsSQL = "UPDATE dbs_samples SET accepted_result = NULL, in_workSheet = 'NO', testing_completed = 'NO',  
							PCR_results_ReleasedBy = NULL, test_1_result = NULL, 
							test_2_result = NULL, test_3_result = NULL, test_4_result = NULL, test_5_result = NULL, 
							worksheet_1 = NULL, worksheet_2 = null, worksheet_3 = null, worksheet_4  = null, worksheet_5 = null, 
							physical_location = null 
						WHERE id IN ($sample_IDs)";

		// dd($dbsSQL);

		$bk = array_keys($batches);
		$batch_IDs = implode(", ", $bk);
		$batchSQL = "UPDATE batches SET all_samples_tested = 'NO' WHERE id IN ( $batch_IDs) ";

		dd($batches);

		$wk = array_keys($worksheets);
		$worksheet_IDs = implode(", ", $wk);
		$worksheetSQL = "UPDATE lab_worksheets 
							SET HasResults = 'NO', PassedReview = 'NOT_YET_REVIEWED', ReviewedBy = NULL 
							WHERE id IN ($worksheet_IDs)";





	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['example', InputArgument::OPTIONAL, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}



	protected function DB_select($db, $query)
	{
		if($db == USE_NEW_DATABASE)
			return DB::select($query);

		if($db == USE_OLD_DATABASE)
			return DB::connection('old_database')->select($query);

		$this->fatal_error(UNKNOWN_DATABASE, $db, $query);// should never reach here
	}


	protected function DB_exec($db, $query)
	{
		if($db == USE_NEW_DATABASE)
			return DB::unprepared($query);

		if($db == USE_OLD_DATABASE)
			return DB::connection('old_database')->unprepared($query);

		$this->fatal_error(UNKNOWN_DATABASE, $db, $query);// should never reach here
	}

	protected function fatal_error($error_type, $db, $query)
	{
		$this->error("UNKNOWN_DATABASE: $db => Can't connect to database, so can't execute this query\n$sql");
	}    


}
