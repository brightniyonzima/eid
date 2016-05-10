/* create a dummy Hub for facilities with no hubs */
INSERT INTO hubs 
	(hub, email, ipID, coordinator, coordinator_contact , created, createdby ) 
VALUES 
	('Hub Unknown', 'unknown@hubs.cphl', 0, 'System Admin', '+256 776 11 11 86', '2015-11-02', 1);


/* make sure all sickle cell results are copied into dbs_samples */
UPDATE dbs_samples 
	JOIN sc_worksheet_index ON sample_id = dbs_samples.id 
	SET SCD_test_result = tie_break_result, 
		date_results_entered = if(date_results_entered, date_results_entered, '2015-11-02' )
	WHERE  tie_break_result not IN ('INVALID', 'SICKLER.TEST_AGAIN') ;

/* fixes Eron issue: if SCD_results_ReleasedBy is null, result wont print. */
UPDATE dbs_samples 
	SET SCD_results_ReleasedBy = 53,  /* 53 = Madam Mercy, head of Sickle Cell Lab */
		date_results_entered = if(date_results_entered, date_results_entered, '2015-11-02' )
	WHERE 	SCD_results_ReleasedBy is null 
	AND 	SCD_test_result is not null 
	AND 	PCR_test_requested = 'YES'; 
