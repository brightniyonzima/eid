/* 
	Fixes bug where results were not being printed for EID, yet results were available. 
*/
update dbs_samples set accepted_result = test_1_result  where accepted_result is null and testing_completed = 'YES';
update dbs_samples set testing_completed = 'NO'  where accepted_result is null and testing_completed = 'YES';
update dbs_samples set PCR_results_ReleasedBy = 56 where accepted_result is not null and test_1_result is not null and PCR_results_ReleasedBy is null and testing_completed = 'YES';
