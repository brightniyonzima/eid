/* 	
	Make SC-only samples appear for printing 

Updated on 22nd Oct:
	-- based on db_alterations_21b_Oct-2015.sql
	-- updated the having clause to include SCD_test_result so that
	-- batches which have missing Sickle cell results are excluded

*/
UPDATE batches SET all_samples_tested = 'YES', date_testing_completed = '2015-10-21' 
	WHERE id IN ( 
					SELECT 	batch_id FROM dbs_samples 
					WHERE 	PCR_test_requested='NO' 
					AND 	ready_for_SCD_test = 'TEST_ALREADY_DONE' 
					AND 	SCD_test_result IS NOT NULL
					
					GROUP BY batch_id  
					HAVING (sum(PCR_test_requested='YES') = 0 AND sum(SCD_test_result is NULL) = 0)
				);

