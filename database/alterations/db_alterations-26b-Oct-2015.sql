/* create default value for the is_admin field: this prevents user-creation errors */
ALTER TABLE users CHANGE COLUMN is_admin is_admin TINYINT UNSIGNED DEFAULT 0;



/* 	Reverse the damage caused by rare off-by-1 error in EID results. 
	Use git diff on WorksheetManager to see the bug & its fix */

UPDATE dbs_samples 
		SET 	in_workSheet='NO', 
				testing_completed='NO', 
				accepted_result=null, 
				test_1_result='POSITIVE' 
				
		WHERE id='714513';