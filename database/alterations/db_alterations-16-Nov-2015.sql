/* force these sickle cell samples to be re-tested */
update dbs_samples set repeated_SC_test='YES' , ready_for_SCD_test = 'YES' where id in (select sample_id from sc_worksheet_index where  worksheet_number >= 400531 and worksheet_number <= 400540 and tie_break_result = 'SICKLER.TEST_AGAIN') ;

/*
	This SQL fixes a weird edge-case:
	It appears the sample was initially marked for EID+SCD test.
	However, after the EID worksheet was created, the approver cancelled the request
	for an EID test (this meant the sample was now to be tested for Sickle Cell only). 

	But SCD test could not be started until the EID module released the sample,
	yet EID module was ignoring the sample (because: EID test no longer needed) 
	so the sample was caught in a deadlock: waiting for an action by EID module
	before starting SC test, yet no EID module was ignoring it.
	See: eid-16-nov.sql.tgz - it has the original data

Warning: 
Unless the approvals + EID modules are updated, this could happen again.
*/
UPDATE dbs_samples SET sickle_cell_release_code = 'CYRX' , ready_for_SCD_test = 'YES' WHERE id = 716289;