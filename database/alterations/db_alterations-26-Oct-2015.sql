/*
	Sickle Cell Worksheets:
	There are some samples whose results are ready, but yet they do not appear in the list of
	samples to be printed. 

	These 2 queries provides temporary relief until we fix that issue.
	1st update query sets SCD_test_result
*/
UPDATE sc_worksheet_index JOIN dbs_samples ON sample_id = dbs_samples.id 
SET SCD_test_result = tie_break_result, 
	date_results_entered = '2015-10-26' 
WHERE  tie_break_result not IN ('INVALID', 'SICKLER.TEST_AGAIN') ;

/* 	
	Make SC-only samples appear for printing 

Updated on 26th Oct:
	-- direct copy of db_alterations_22b_Oct-2015.sql 
	-- main change is the value of date_testing_completed
	--
*/
UPDATE batches SET all_samples_tested = 'YES', date_testing_completed = '2015-10-26' 
	WHERE id IN ( 
					SELECT 	batch_id FROM dbs_samples 
					WHERE 	PCR_test_requested='NO' 
					AND 	ready_for_SCD_test = 'TEST_ALREADY_DONE' 
					AND 	SCD_test_result IS NOT NULL
					
					GROUP BY batch_id  
					HAVING (sum(PCR_test_requested='YES') = 0 AND sum(SCD_test_result is NULL) = 0)
				);