
/* Adding columns to support the new dispatch & printing algorithm */
ALTER TABLE batches ADD COLUMN date_PCR_printed DATETIME DEFAULT NULL;
ALTER TABLE batches ADD COLUMN date_SCD_printed DATETIME DEFAULT NULL;


/* Update batches with data about the "tests_requested" for each batch */
ALTER TABLE batches ADD COLUMN tests_requested 
	ENUM('PCR', 'SCD' , 'BOTH_PCR_AND_SCD', 'UNKNOWN') NOT NULL DEFAULT 'UNKNOWN';

UPDATE batches 
JOIN dbs_samples ON batches.id = batch_id 
	SET tests_requested = 'PCR' 
	WHERE dbs_samples.PCR_test_requested = 'YES' AND dbs_samples.SCD_test_requested = 'NO';


UPDATE batches 
JOIN dbs_samples ON batches.id = batch_id 
	SET tests_requested = 'SCD' 
	WHERE dbs_samples.PCR_test_requested = 'NO' AND dbs_samples.SCD_test_requested = 'YES';

UPDATE batches 
JOIN dbs_samples ON batches.id = batch_id 
	SET tests_requested = 'BOTH_PCR_AND_SCD' 
	WHERE dbs_samples.PCR_test_requested = 'YES' AND dbs_samples.SCD_test_requested = 'YES';

/* 	
	Break up date_testing_completed into 2 columns: 1 for sickle cell and another for EID
	This allows us to know when PCR was completed and when SCD was completed 
*/
ALTER TABLE batches CHANGE COLUMN date_testing_completed date_PCR_testing_completed DATE DEFAULT NULL;
ALTER TABLE batches ADD COLUMN date_SCD_testing_completed DATE DEFAULT NULL AFTER date_PCR_testing_completed;

/*
Note: 
	We no longer use all_samples_tested to know if testing is completed. 
	Instead we use PCR_results_released and SCD_results_released. 
	This is better because it lets us know which lab has finished testing.

	So this command removes it and replaces it with all_samples_rejected, which is then
	used by dispatch manager for tracking rejected samples and printing/dispatching them.
*/
ALTER TABLE batches CHANGE all_samples_tested all_samples_rejected ENUM('YES', 'NO') NOT NULL DEFAULT 'NO';



/* Without sickle_cell_release_code, the worksheet creation algorithm files them under location "unknown_loc" */
UPDATE dbs_samples set sickle_cell_release_code = 'AUTO'  
	WHERE 	sickle_cell_release_code is null 
	AND 	(SCD_test_requested = 'YES' and PCR_test_requested = 'NO') 
	AND 	ready_for_SCD_test = 'YES';


UPDATE dbs_samples JOIN sc_worksheet_index ON sample_id = dbs_samples.id 
	SET SCD_test_result = tie_break_result, 		
		SCD_results_ReleasedBy = 53,  /* 53 = Madam Mercy, head of Sickle Cell Lab */
		date_results_entered = '2015-12-01',		
		ready_for_SCD_test = 'TEST_ALREADY_DONE'
	WHERE  worksheet_number = '400578';


/* 
Dispatch Module:
	These are the fields we need to populate - select the relevant ones for your situation 
	Make sure EID and SCD modules are inserting data wherever its needed.

	The queries below try to transform any data in the old format to use this new format.

update batches set 	date_PCR_testing_completed=defaul,
					date_SCD_testing_completed=z,
					
					date_PCR_printed=x,
					date_SCD_printed=y,
					
					PCR_results_released=x,
					SCD_results_released,
					
					printed_PCR_results,
					printed_SCD_results,
*/

/* EID: these batches (should) have been printed */
UPDATE batches 
	SET date_PCR_printed = date_add(date_PCR_testing_completed, interval 1 day),
		PCR_results_released='YES', 
		printed_PCR_results='{"n":0}'  
	WHERE date_PCR_testing_completed IS NOT NULL ;


/* EID: although their date_PCR_testing_completed is null, these batches deserve to printed */
UPDATE batches 
	SET date_PCR_testing_completed=date_add(date_entered_in_DB, interval 2 day), 
		date_PCR_printed=date_add(date_entered_in_DB, interval 3 day), 
		PCR_results_released='YES', 
		printed_PCR_results = '{"n":0}' 
	WHERE date_PCR_testing_completed IS NULL
	AND id IN (	SELECT batch_id 
					FROM dbs_samples 
					WHERE PCR_test_requested = 'YES'
					GROUP BY batch_id 
					HAVING sum(accepted_result is null) = 0 
			);

/* SCD: although their batches.date_testing_completed is null, these SCD samples deserve to printed */
UPDATE batches 
	SET date_SCD_testing_completed = date_add(date_entered_in_DB, interval 1 day), 
		date_SCD_printed=date_add(date_entered_in_DB, interval 2 day), 
		SCD_results_released='YES', 
		printed_SCD_results='{"n":0}' 
	WHERE date_PCR_testing_completed IS NULL 
	AND id IN (	SELECT batch_id FROM dbs_samples WHERE
					SCD_test_requested = 'YES'  
						GROUP BY batch_id 
						HAVING SUM(SCD_test_result IS NULL) = 0 
	);


/* 	False Positives: 
	Claim to be complete batches (i.e. having all the results) but they aren't. 
	Some results are missing 
*/
UPDATE batches SET 	date_PCR_testing_completed=null,
					date_PCR_printed=null,
					PCR_results_released='NO',
					printed_PCR_results = null
			where 	date_PCR_testing_completed is not null /* this claims that batch is completed... */
			AND 	batches.id in (	select batch_id from dbs_samples 
								where PCR_test_requested = 'YES' 
								and accepted_result is null /* ... but NULL result is proof that its not yet completed */
								group by batch_id
						);



/*
-- This query displays date of key events: can be used to calculate TAT
	select batch_id , 
			datediff(date_rcvd_by_cphl, date_dispatched_from_facility) as d1,
			datediff( date_entered_in_DB, date_rcvd_by_cphl) as d2,
			datediff(sample_verified_on, date_entered_in_DB) as d3 ,  
			datediff(date_PCR_testing_completed, date_entered_in_DB) as d4, 
			datediff(date_PCR_printed, date_PCR_testing_completed)+0  as d5 
	from batches, dbs_samples 
	where batches.id = batch_id  
	and date_rcvd_by_cphl >= '2015-11-23'

-- This query gives us the HIV rates
select accepted_result, count(accepted_result ) as n from dbs_samples where accepted_result  is not null group by accepted_result ;

--- This query gives the SCD rates
select SCD_test_result, count(SCD_test_result ) as n from dbs_samples where SCD_test_result  is not null group by SCD_test_result ;

-- This query gives you results received vs results released for a specified day
select count(id) as n, "in" as act from dbs_samples where batch_id in (select id from batches where date_rcvd_by_cphl = '2015-11-23' ) union select count(id) as n, "out" as act from dbs_samples where batch_id in (select id from batches where date_PCR_testing_completed   = '2015-11-25' ) ;

-- This query tells you who entered the most data
select users.id as user_id, users.family_name , users.other_name, count(dbs_samples.id) as nSamples   from batches, dbs_samples, users where batches.id = dbs_samples.batch_id and users.id = batches.entered_by group by user_id order by nSamples desc;

-- This tells you pass vs repeat vs fail rates for EID
select  sum(if(test_1_result is null, 0, 1) + if(test_2_result is null,0,1) +  if(test_3_result is null, 0, 1) + if(test_4_result is null, 0, 1) + if(test_5_result is null, 0, 1)) as nTests, count(test_2_result) + count(test_3_result)   as nRepeats,
sum(if(test_1_result =  'fail', 1, 0) + if(test_2_result = 'fail',1,0) +  if(test_3_result = 'fail', 1, 0) + if(test_4_result = 'fail', 1, 0) + if(test_5_result = 'fail', 1, 0)) as nFails from dbs_samples;


=>
-- Printed vs Not Printed (for EID and SCD)
select 'SCD' as test_type, count(id), count(printed_SCD_results ) from batches where tests_requested in ('SCD', 'BOTH_PCR_AND_SCD') UNION select 'EID' as test_type, count(id), count(printed_PCR_results ) from batches where tests_requested in ('PCR', 'BOTH_PCR_AND_SCD');


http://www.highcharts.com/demo/bar-basic
http://www.highcharts.com/demo/bar-stacked
http://www.highcharts.com/demo/column-rotated-labels
http://www.highcharts.com/demo/pie-basic

*/


/*
	These batches should have been repeated, but they did not automatically return to the worksheet:
	This SQL is here for documentation only - it has already been done, don't repeat:
	UPDATE dbs_samples 
	SET in_workSheet = 'NO', 
		testing_completed = 'NO' 
	WHERE id IN (710152,704412,705884, 712753, 717249, 720443, 712759, 712763) and sample_rejected = 'NO';	
*/	



/* This is used by admin dashboard to track changes made during sample verification and approval */
CREATE TABLE IF NOT EXISTS data_entry_accuracy(
	
	batch_id INT UNSIGNED,
	data_entered_by INT UNSIGNED,
	data_checked_by INT UNSIGNED,
	original_data TEXT NOT NULL,
	changes TEXT DEFAULT NULL,
	nChanges INT default NULL comment 'calculated by insert statement as: JSON_LENGTH(changes)',
	last_changed TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

	UNIQUE(batch_id)
);

CREATE TABLE IF NOT EXISTS data_entry_speed(
	data_type ENUM ('BATCH_HEADER', 'SAMPLE', 'SAMPLE_VERIFICATION') not null,
	batch_id  INT UNSIGNED not null,	
	sample_id  INT UNSIGNED not null default 0,
	seconds_used SMALLINT UNSIGNED not null default 0,
	data_entered_by INT UNSIGNED not null,
	data_entry_unix_time INT UNSIGNED not null,

	index (data_entered_by),
	index (data_entry_unix_time)
);


/* mark these sickle cell results as released */
update batches set SCD_results_released = 'YES' where printed_SCD_results is not null;

/* copy sickle cell results so that they are enabled  for printing. See db_alterations-02-Nov.sql for detailed comments. */
UPDATE dbs_samples JOIN sc_worksheet_index ON sample_id = dbs_samples.id SET SCD_test_result = tie_break_result, date_results_entered = if(date_results_entered, date_results_entered, '2015-12-08' ) WHERE  tie_break_result not IN ('INVALID', 'SICKLER.TEST_AGAIN') ;
UPDATE dbs_samples SET SCD_results_ReleasedBy = 53, date_results_entered = if(date_results_entered, date_results_entered, '2015-12-08' ) WHERE 	SCD_results_ReleasedBy is null AND 	SCD_test_result is not null AND 	PCR_test_requested = 'YES';	

