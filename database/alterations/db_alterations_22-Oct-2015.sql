/* 
	Result columns were too small, thereby truncating SICKLER.TEST_AGAIN to SICKLER.TEST_AG 
	This caused various bugs in the repeat-test algorithm
*/

alter table sc_worksheet_index change column result1 result1 varchar(30);
alter table sc_worksheet_index change column result2 result2 varchar(30);
alter table sc_worksheet_index change column tie_break_result tie_break_result  varchar(30);

update sc_worksheet_index set result1 = 'SICKLER.TEST_AGAIN' where result1 = 'SICKLER.TEST_AG';
update sc_worksheet_index set result2 = 'SICKLER.TEST_AGAIN' where result2 = 'SICKLER.TEST_AG';
update sc_worksheet_index set tie_break_result = 'SICKLER.TEST_AGAIN' where tie_break_result  = 'SICKLER.TEST_AG';

update  dbs_samples SET ready_for_SCD_test = 'YES', repeated_SC_test='YES'  WHERE id IN 
	(
		select  sample_id  from  sc_worksheet_index  WHERE tie_break_result = 'SICKLER.TEST_AGAIN'
	);

update  dbs_samples SET ready_for_SCD_test = 'YES', repeated_SC_test='YES'  WHERE id IN 
	(
		select  sample_id  from  sc_worksheet_index  WHERE tie_break_result = 'invalid'
	);
