/* 	Results available for EID but not SCD because columns below were NULL.
	Why were they NULL? I don't know, yet. 
*/
update dbs_samples set SCD_test_result = 'CARRIER', SCD_results_ReleasedBy = 55 where id = 718938;

/* Add columns to be used if lab wished to retain results for further tests */
ALTER TABLE batches ADD COLUMN SCD_results_released ENUM ('YES', 'NO') NOT NULL DEFAULT 'NO' after date_testing_completed ;
ALTER TABLE batches ADD COLUMN PCR_results_released ENUM ('YES', 'NO') NOT NULL DEFAULT 'NO' after date_testing_completed ;
