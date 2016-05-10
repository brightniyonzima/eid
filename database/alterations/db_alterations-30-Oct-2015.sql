
/* 	Bring back sickle cell samples for re-testing 
	(regardless of earlier result) 
*/
UPDATE  dbs_samples 

	SET repeated_SC_test='YES',
		ready_for_SCD_test = 'YES', 		 
			SCD_test_result = NULL, 
			SCD_results_ReleasedBy = NULL		

	WHERE id in (708996, 708999, 703972);


/*	Cancel the results in a worksheet so that they can be entered again.
    (e.g. if an error was made in the original worksheet)

    -- Based on db_alterations-29-Oct-2015.sql but it's better because...
			1) Its also updates the db_samples table
			2) It runs in a transaction
*/
START TRANSACTION;
	UPDATE 	sc_worksheets 		
		SET Examiner1_ResultsReady = 'NO' , 
			Examiner2_ResultsReady = 'NO', 
			TieBreaker_ResultsReady = 'NO' 
		WHERE id = 400477;


	UPDATE  sc_worksheet_index 
		SET result1 = NULL, 
			result2 = NULL, 
			tie_break_result = NULL 
		WHERE worksheet_number = 400477;


	UPDATE  dbs_samples 
		SET SCD_test_result = NULL,
			SCD_results_ReleasedBy = NULL
		WHERE id in (
						SELECT sample_id FROM sc_worksheet_index WHERE worksheet_number = 400477
					);
COMMIT;


/*
	Change the results of a sickle cell test. 
	Unlike EID, these results are entered by hand which sometimes introduces errors.
	That's why this transaction is needed (to fix such errors).
*/
START TRANSACTION;
	UPDATE sc_worksheet_index SET tie_break_result = 'CARRIER' WHERE sample_id in (704075, 704947);

	UPDATE  dbs_samples 
		SET SCD_test_result = 'CARRIER',
			SCD_results_ReleasedBy = 53 /* 53 = Madam Mercy */
		WHERE id IN ( 704075, 704947 );
COMMIT;
