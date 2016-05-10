
/*
	Cancel results uploaded to a sickle cell worksheet so that fresh results can be uploaded.
	(Error was made in the original worksheet)
*/
update  sc_worksheet_index set result1 = null, result2 = null, tie_break_result = null where worksheet_number = 400479;
update 	sc_worksheets set Examiner1_ResultsReady = 'NO' , Examiner2_ResultsReady = 'NO', TieBreaker_ResultsReady = 'NO' where id = 400479;

/* 
	=> we need to change dbs_samples too

Question:
	=> delete  worksheets so that samples go onto new worksheet? [YES]
			-- OR ---
	=> delete results in the worksheet, so you can upload fresh results [NO. This can be done via edit results ]
*/

/* 	We recently upgraded to the faster (and stricter!) MySQL 5.7
	This code modifies the database to prevent user-creation errors when default values are not provided.
*/
ALTER TABLE users CHANGE COLUMN is_admin is_admin TINYINT UNSIGNED DEFAULT 0; /* provide default value (default = not admin) */
ALTER TABLE users change facilityID facilityID mediumint unsigned; /* tell MySQL that NULLs are OK */
ALTER TABLE users change hubID hubID mediumint unsigned; /* tell MySQL that NULLs are OK */
ALTER TABLE users change ipID ipID mediumint unsigned; /* tell MySQL that NULLs are OK */

