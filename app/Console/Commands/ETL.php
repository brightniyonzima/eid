<?php namespace EID\Console\Commands;

use \DB as DB;
use EID\Models\User as User;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

define('USE_OLD_DATABASE', 1);
define('USE_NEW_DATABASE', 2);
define('UNKNOWN_DATABASE', 3);


class ETL extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'etl:start';

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

		// $this->migrate_users_data();
		$this->migrate_batches_data();
		$this->migrate_samples_data();

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


	protected function migrate_users_data()
	{
		$this->info("\nPreparing to migrate_users_data()...");

		$this->DB_exec(USE_NEW_DATABASE, 'TRUNCATE users');

		$this->DB_exec(USE_NEW_DATABASE, "insert into eid.users select id, username , '" . \Hash::make('admin') . "' as password , 1 as type, 1 as is_admin, surname as family_name, oname as other_name, signature , email , telephone, NULL as telephone2, NULL as organization_id, NULL as facilityID, NULL as hubID, NULL as ipID, if(datedeactivated is null, 0 , 1) as deactivated, 0 as loggedon, NULL as created, NULL as createdby, NULL as remember_token from old_eid.users");

		$this->create_default_user();

		$this->info(" ... DONE.");
	}

	protected function migrate_batches_data()
	{

		$this->info("\nPreparing to migrate_batches_data()...");

		$this->DB_exec(USE_NEW_DATABASE, 'SET FOREIGN_KEY_CHECKS=0');
		$this->DB_exec(USE_NEW_DATABASE, 'TRUNCATE batches');
		$this->DB_exec(USE_NEW_DATABASE, 'TRUNCATE dbs_samples');
		$this->DB_exec(USE_NEW_DATABASE, 'SET FOREIGN_KEY_CHECKS=1');		

		$this->DB_exec(USE_NEW_DATABASE, 
"INSERT IGNORE INTO eid.batches 

(
id,
  lab,
  batch_number,
  envelope_number,

  entered_by,
  facility_id,
  facility_name,
  facility_district,
  senders_name,
  senders_telephone,
  senders_comments,
  results_return_address,
  results_transport_method,
  date_dispatched_from_facility,
  date_rcvd_by_cphl,
  date_entered_in_DB,
  all_samples_rejected,
  date_PCR_testing_completed,
  date_SCD_testing_completed,
  PCR_results_released,
  SCD_results_released,
  printed_PCR_results,
  printed_SCD_results,
  date_dispatched_to_facility,
  date_rcvd_at_facility,
  f_senders_name,
  f_senders_telephone,
  f_date_dispatched_from_facility,
  f_date_rcvd_by_cphl,
  f_paediatricART_available,
  date_PCR_printed,
  date_SCD_printed,
  tests_requested

) 

select 0 as id, 'CPHL' as lab, trim(batchno), trim(envelopeno),  1 as entered_by,
facility as facility_id, facilitys.name as facility_name, facilitys.district as facility_district,  
contactperson as senders_name, contacttelephone as senders_phone, comments, '' as results_return_address, 
if(selfpick, 'COLLECTED_FROM_LAB', 'POSTA_UGANDA') as results_transport_method, 
	datedispatchedfromfacility, datereceived , dateenteredindatabase , 'NO' as all_samples_rejected,  
datetested as date_PCR_testing_completed, 
	  NULL as date_SCD_testing_completed,


'YES' as PCR_results_released,
'NO' as SCD_results_released,
NULL as printed_PCR_results,
NULL as printed_SCD_results,

datedispatched as date_dispatched_to_facility, 
NULL as date_rcvd_at_facility, 
NULL as f_senders_name, NULL as f_senders_telephone, NULL as f_date_dispatched_from_facility, 
NULL as f_date_rcvd_by_cphl, 'YES' as f_paediatricART_available, 

NULL as date_PCR_printed,
NULL as date_SCD_printed,
'PCR' as tests_requested
   from old_eid.samples, old_eid.facilitys where samples.facility=facilitys.id and repeatt = 0 and result in (1,2,3,4,5,6,7,8)");

		$this->info(" ... DONE.");
	}


	protected function migrate_samples_data($alter = false)
	{

		$this->info("\nPreparing to migrate_samples_data()...");

		if($alter){
			$this->alter_samples_table();
		}

		$i = 0;
		$j = 0;
		$sql = "";
		$job = [];	// each job = a batch of SQL queries to execute at the same time
		$job_size = 1;// number of SQL queries in a job

		$data = $this->DB_select(USE_NEW_DATABASE, 'select id, batch_number from batches');
		$this->comment("Got " . count($data) . " batches. Starting to import them...");
		
		foreach ($data as $current_batch) {
			
			$j++;

			$sql .= "INSERT /* IGNORE */ INTO dbs_samples SELECT 0 as id, samples.id AS old_id, '" . $current_batch->id . "' as batch_id, @i:=@i+1 as pos_in_batch, 0 as sample_verified_by, '1927-12-09' as sample_verified_on, spots as nSpots, if(rejectedreason=0, 'NO', 'YES') as sample_rejected, if(rejectedreason=0, NULL, rejectedreason) as rejection_reason_id, comments as rejection_comments, NULL as sickle_cell_release_code, if(infantname is null, '__john doe__', infantname)  as infant_name, patient as infant_exp_id, if(gender='F', 'FEMALE', if(gender='M', 'MALE', 'NOT_RECORDED') ) as infant_gender, old_eid.patients.age as infant_age, '1970-01-01' as infant_dob, if(feeding=1, 'YES', 'NO') as infant_is_breast_feeding, entry_point as infant_entryPoint, caregiverphn  as infant_contact_phone, antenalprophylaxis  as mother_antenatal_prophylaxis, deliveryprophylaxis  as mother_delivery_prophylaxis, postnatalprophylaxis  as mother_postnatal_prophylaxis, prophylaxis as infant_prophylaxis, if(datecollected, datecollected, '1970-01-01') as date_dbs_taken, if(dateenteredindatabase, dateenteredindatabase, '1970-01-01') as date_data_entered, if(datetested, datetested, '1970-01-01') as date_dbs_tested, if(datemodified, datemodified, '1970-01-01') as date_results_entered, if(datedispatched, datedispatched, '1970-01-01') as date_results_printed, 'YES' as PCR_test_requested, 'NO' as SCD_test_requested, if(pcr='1', 'FIRST', if(pcr='2', 'SECOND', 'UNKNOWN')) AS pcr, NULL as nonroutine , 'NO' as migrated_to_old_schema, labcomment as lab_comment, if(Inworksheet, 'YES', 'NO') as in_workSheet, 0 as pos_in_workSheet, worksheet as physical_location, 'NO' as ready_for_SCD_test, NULL as worksheet_1, NULL as worksheet_2, NULL as worksheet_3, NULL as worksheet_4, NULL as worksheet_5, NULL as test_1_result, NULL as test_2_result, NULL as test_3_result, NULL as test_4_result, NULL as test_5_result, if(repeatt=0, 'YES', 'NO') as testing_completed, if(repeatt !=0, NULL, if(result=1, 'NEGATIVE', if(result=2, 'POSITIVE', 'INVALID') )) AS accepted_result, NULL as SCD_test_result, 11 as PCR_results_ReleasedBy, NULL as SCD_results_ReleasedBy, f_results_rcvd_at_facility, f_results_collected_by_caregiver, f_date_results_collected, f_ART_initiated, f_date_ART_initiated, f_reason_ART_not_initated, f_ART_number, f_infant_referred, f_facility_referred_to, 'NO' as repeated_SC_test from ((old_eid.samples left join old_eid.patients on old_eid.patients.id = if(parentid='0', accessionno, parentid) ) left join old_eid.mothers on mothers.id = old_eid.patients.mother) where old_eid.samples.batchno = '" . $current_batch->batch_number . "' and repeatt = 0 and result in (1,2,3,4,5,6,7,8)  order by old_id;\n";

			if($j == $job_size){
				$job[] = $sql;
				$sql = "";
				$j=0;

				$i++;
				$this->info("\tJob # $i prepared for import...");
			}
			// if($i > 40) break;
		}

		$this->comment("Preparing for deep dive...do NOT hold your breath!");
		$i = 0;
		
		foreach ($job as $sql_query) {
			$i++;
			DB::transaction(function () use ($sql_query, $i) {

				$this->DB_select(USE_NEW_DATABASE, 'SELECT @i:=0;');
				$y = $this->DB_exec(USE_NEW_DATABASE, $sql_query);
				$this->info("\t Job # " . $i . " completed successfully [y = $y]");
			});
		}
		$this->info(" ... DONE.");		
	}


	protected function alter_samples_table()
	{

		$alt[] = "alter table samples add column `f_results_rcvd_at_facility` enum('YES','NO','LEFT_BLANK') COLLATE utf8_unicode_ci DEFAULT NULL;";
		$alt[] = "alter table samples add column `f_results_collected_by_caregiver` enum('YES','NO','LEFT_BLANK') COLLATE utf8_unicode_ci DEFAULT NULL;";
		$alt[] = "alter table samples add column `f_date_results_collected` date DEFAULT NULL; ";
		$alt[] = "alter table samples add column `f_ART_initiated` enum('YES','NO','LEFT_BLANK') COLLATE utf8_unicode_ci DEFAULT NULL;";
		$alt[] = "alter table samples add column `f_date_ART_initiated` date DEFAULT NULL;";
		$alt[] = "alter table samples add column `f_reason_ART_not_initated` tinyint(3) unsigned DEFAULT NULL;";
		$alt[] = "alter table samples add column `f_ART_number` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL;";
		$alt[] = "alter table samples add column `f_infant_referred` enum('YES','NO') COLLATE utf8_unicode_ci DEFAULT NULL; ";
		$alt[] = "alter table samples add column `f_facility_referred_to` mediumint(8) unsigned DEFAULT NULL;";

		foreach ($alt as $alter_statement) {
			$this->DB_exec(USE_OLD_DATABASE, $alter_statement);
			$this->info("\n\tQuery OK:....  added a new column to table `samples` ");
		}

		$this->info("\nSuccessfully modified table `samples`");
		$this->info("\n\tThe new columns store follow-up data and start with 'f_'");
		$this->info("\n\tThe MySQL command 'desc samples' should display them as the bottom columns of the samples table");
		$this->info("\n\n");

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


	public function create_default_user()
	{
		User::create(array(
				'username'	=> 	'admin',
				'password' 	=> 	'admin123',
				'type' 		=> 	1, // DATA_CLERK
				'is_admin' 	=> 	true,
				'email' 	=> 	'admin@test.cphl',
				'family_name'	=> 'Administrator',
				'other_name'	=> 'Kent (Data) Clark',
			)
		);
	}

	public function cancel_csv_upload() {/* This was used for testing during development. 
											No longer used here. Its used in production by 
											EID worksheets to cancel upload of CSV file with results  */

		$this->comment("Starting the CSV destroyer ====== PLEASE BE CAREFUL =====...\n\n");
		
		$sql = "SELECT  infant_name, infant_exp_id, worksheet_number, batch_id, batch_number, sample_id, PCR_test_requested, SCD_test_requested , sample_rejected FROM    dbs_samples, batches, worksheet_index WHERE   dbs_samples.id = worksheet_index.sample_id AND     dbs_samples.batch_id = batches.id and worksheet_index.worksheet_number IN (select distinct worksheet_number from worksheet_index)";

		$data = $this->DB_select(USE_NEW_DATABASE, $sql);
		

		$samples = [];
		$batches = [];
		$worksheets = [];

		foreach ($data as $this_sample) {
			$samples[ $this_sample->sample_id ] = "doesnt_matter";
			$batches[ $this_sample->batch_id ] = "doesnt_matter";
			$worksheets[ $this_sample->worksheet_number ] = "doesnt_matter";
		}


		$sk = array_keys($samples);
		$sample_IDs = implode(", ", $sk);
		$dbsSQL = "UPDATE dbs_samples SET accepted_result = NULL, in_workSheet = 'YES', testing_completed = 'NO',  
							PCR_results_ReleasedBy = NULL, test_1_result = NULL, 
							test_2_result = NULL, test_3_result = NULL, test_4_result = NULL, test_5_result = NULL, 
							worksheet_1 = NULL, worksheet_2 = null, worksheet_3 = null, worksheet_4  = null, worksheet_5 = null, 
							physical_location = null 
						WHERE id IN ($sample_IDs)";

		$this->DB_exec(USE_NEW_DATABASE, $dbsSQL);
		$this->comment("1/3: samples done");

		// dd($sample_IDs);


		$bk = array_keys($batches);
		$batch_IDs = implode(", ", $bk);
		$batchSQL = "UPDATE batches SET all_samples_tested = 'NO' WHERE id IN ( $batch_IDs) ";
		
		$this->DB_exec(USE_NEW_DATABASE, $batchSQL);
		$this->comment("2/3: BATCHES done");

		// dd($batches);

		$wk = array_keys($worksheets);
		$worksheet_IDs = implode(", ", $wk);
		$worksheetSQL = "UPDATE lab_worksheets 
							SET HasResults = 'NO', PassedReview = 'NOT_YET_REVIEWED', ReviewedBy = NULL 
							WHERE id IN ($worksheet_IDs)";

		$this->DB_exec(USE_NEW_DATABASE, $worksheetSQL);
		$this->comment("3/3: WORKSHEETS done");
	}
}
// $sql = "SELECT CONCAT('DROP TABLE `',t.table_schema,'`.`',t.table_name,'`;') AS stmt FROM information_schema.tables t WHERE t.table_schema = 'das' AND t.table_name LIKE 'cscart\_%' ";
// $tables_to_drop = $this->DB_select(USE_NEW_DATABASE, $sql);

// foreach ($tables_to_drop as $table) {
// 	$this->info($table->stmt);
// }
