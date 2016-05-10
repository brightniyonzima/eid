
/* add columns to batches table that track samples whose results have been printed */
ALTER TABLE batches ADD COLUMN `printed_SCD_results` JSON AFTER SCD_results_released ;
ALTER TABLE batches ADD COLUMN `printed_PCR_results` JSON AFTER SCD_results_released ;

/* 
	Make sure all missing sickle cell results are copied into dbs_samples from sc_worksheet_index 
	We can identify them later based on their `date_results_entered`
*/
UPDATE dbs_samples JOIN sc_worksheet_index ON sample_id = dbs_samples.id 
	SET date_results_entered = '1905-06-16', 
		SCD_test_result = tie_break_result, 
		SCD_results_ReleasedBy = coalesce(SCD_results_ReleasedBy, 53) 
	WHERE  	SCD_test_result is null 
	AND 	tie_break_result not IN ('INVALID', 'SICKLER.TEST_AGAIN', 'LEFT_BLANK');
