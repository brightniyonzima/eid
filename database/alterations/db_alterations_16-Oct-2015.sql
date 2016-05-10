ALTER TABLE dbs_samples MODIFY SCD_test_result varchar(32); /* make it big enough to hold new value 'SAMPLE_WAS_REJECTED' */

-- 	The goal of the first SQL statement is to avoid 'invalid-date' errors in the second SQL statement caused by rows where
--	infant_dob = '0000-00-00'. However, we cant use infant_dob='0000-00-00' in the where clause because it causes warnings that 
--	kill the script. 18700101 is an arbitrary date that has the same effect because it is so long ago. It has no special meaning.
-- 	Similarly, 1st Jan 1927 is not special in any way. 
--	I just use it to identify these rows because I dont expect any infant in our database to have been born on that date.
UPDATE dbs_samples set infant_dob = '1927-01-01' where infant_dob < 18700101;
UPDATE dbs_samples set date_dbs_taken = '1927-01-01' where date_dbs_taken < 18700101;
UPDATE dbs_samples SET SCD_test_result = 'FAILED' WHERE SCD_test_result = 'INVALID';/* INVALID vs FAIL: see next multi-line comment */
update dbs_samples set SCD_test_result = 'SAMPLE_WAS_REJECTED' WHERE sample_rejected = 'YES' AND SCD_test_result = 'FAILED';
UPDATE dbs_samples SET SCD_test_result = NULL WHERE SCD_test_result = ''; /* RARE CASE: 16 occurrences */

-- This is needed because CPHL wants to show "sample rejected" instead of "invalid", if the samples was rejected.
ALTER TABLE dbs_samples MODIFY SCD_test_result ENUM('NORMAL', 'VARIANT', 'CARRIER', 'SICKLER', 'FAILED', 'SAMPLE_WAS_REJECTED') DEFAULT NULL;
ALTER TABLE dbs_samples MODIFY accepted_result ENUM('POSITIVE', 'NEGATIVE', 'INVALID', 'SAMPLE_WAS_REJECTED') DEFAULT NULL;

/* 	SC Lab and EID Lab use opposite terminology to represent case where a test was inconclusive and needs to be repeated:
	EID Lab calls that test a FAIL, while SC Lab calls that an INVALID.

	To add to the confusion, they use opposite terminology for the final result:
	EID Lab calls a sample that they have given up on (because it remains inconclusive after multiple tests) an INVALID, 
	while SC Lab calls it a FAIL.
*/
UPDATE dbs_samples SET accepted_result = 'SAMPLE_WAS_REJECTED'  WHERE sample_rejected = 'YES' AND accepted_result = 'INVALID';

