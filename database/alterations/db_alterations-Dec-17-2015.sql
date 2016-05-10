
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


UPDATE batches 
JOIN dbs_samples ON batches.id = batch_id 
	SET date_PCR_testing_completed = '2015-12-17' ,
		PCR_results_released = 'YES' 
	WHERE date_results_entered = '2015-12-17';