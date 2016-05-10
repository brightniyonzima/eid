
/* 	Eron final solution 
	Selects sickle cell samples that were not ready for print+dispatch 
	when their batches's EID samples were printed & dispatched
	
	During EID printing, I set date_testing_completed as a poor man's 
	memory aid (much like Hansel and Gretel).

	This query is optimized for accuracy, not speed.
	A slightly updated version is used in eron.blade.php and eron_envelop.blade.php (search for $new_filter)

select batch_id, dbs_samples.id as sample_id, date_results_entered  , infant_name , SCD_test_requested as doSCD, PCR_test_requested as doPCR, test_1_result , test_2_result , SCD_results_ReleasedBy SCD_by, PCR_results_ReleasedBy PCR_by, accepted_result , SCD_test_result from     dbs_samples where batch_id in ( select id from batches where id in (select batch_id from dbs_samples where PCR_test_requested = 'YES' and SCD_test_requested = 'YES' and accepted_result is not null and SCD_test_result is not null) and date_testing_completed in ('2015-11-02', '2015-10-26') )  and SCD_test_result is not null;
*/


/* this SQL command has been run. do not re-run!
update dbs_samples set test_1_result = 'FAIL', accepted_result = null, in_workSheet = 'NO', testing_completed = 'NO' where id in (716492, 716419, 716433);
*/	

/* 	EID Lab:
	These are special cases. Recorded here for posterity. 
	Caused by a user error (wrong CSV file uploaded).
	Most were negative, so no change needed but some were not, so this is needed.
	Also, a system error flagged "low_positive followed by positive" as invalid, yet it is positive.
*/
update dbs_samples set accepted_result = 'POSITIVE' where id = 704412;
update dbs_samples set in_workSheet = 'NO' where id in ( 710060, 710127 );

/*
	SC Lab:
	Change the results of a sickle cell test. 
	Unlike EID, these results are entered by hand which sometimes introduces errors.
	That's why this transaction is needed (to fix such errors).
*/
START TRANSACTION;
	UPDATE sc_worksheet_index SET tie_break_result = 'SICKLER' WHERE sample_id = 708999 and worksheet_number = 400512;

	UPDATE  dbs_samples 
		SET SCD_test_result = 'SICKLER',
			SCD_results_ReleasedBy = 53 /* 53 = Madam Mercy */
		WHERE id IN ( 708999 );

/*  change results for another sample */

	UPDATE sc_worksheet_index SET tie_break_result = 'SICKLER.TEST_AGAIN' WHERE sample_id = 711504 and worksheet_number = 400479;

	UPDATE  dbs_samples 
		SET SCD_test_result = NULL,
			SCD_results_ReleasedBy = 53 /* 53 = Madam Mercy */
		WHERE id IN ( 711504 );

/* Change the result and also make sure it is re-tested */
	
	UPDATE sc_worksheet_index SET tie_break_result = 'INVALID' WHERE sample_id = 707787 and worksheet_number = 400451;	
	update  dbs_samples SET ready_for_SCD_test = 'YES', repeated_SC_test='YES', SCD_test_result = null  WHERE id = 707787;

COMMIT;


