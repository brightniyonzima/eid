/* Assign results to worksheet 100438 */


/** Sickle Cell Changes - bring back for retest 


====> This SQL Has already been run. Do not run it again <====

UPDATE  dbs_samples 
    SET repeated_SC_test='YES',
            ready_for_SCD_test = 'YES',              
                    SCD_test_result = NULL, 
                    SCD_results_ReleasedBy = NULL           

    WHERE id in ( 	717165, 717166, 717182, 717607, 716982, 716990, 716994, 717002, 717003, 717004, 717006, 
    				717007, 717026, 714883, 714586, 714590, 714591, 714592, 716737, 717936, 717559, 717575, 
    				717589, 717597, 717516, 717146, 717161, 715442, 715443, 715444
				);

**/

/* EID Lab issues: */
update dbs_samples set testing_completed = 'NO', in_workSheet = 'NO', test_1_result = 'POSITIVE', accepted_result = null where id = 715658;
