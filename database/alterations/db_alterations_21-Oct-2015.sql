/* 	Remove negative samples that are wrongly marked for repeat. 
	Rare user error that has occurs about 1/200 times a worksheet is created. 
	Investigate cause... 
*/

START TRANSACTION;

/* step 1: release the NEGATIVEs for printing...*/
update  dbs_samples set testing_completed = 'YES',  accepted_result = 'NEGATIVE' 
	where id in ( 
					select 	sample_id 
					from 	worksheet_index 
					where 	worksheet_number  in ('100543', '100544') 
					and 	test_1_result  = 'NEGATIVE' 
					and 	accepted_result is NULL 
				);

/* step 2: prepare the non-NEGATIVEs to be added to the next worksheet... */
update  dbs_samples set in_workSheet = 'NO' , testing_completed = 'NO'
	where id in ( 
					select 	sample_id 
					from 	worksheet_index 
					where 	worksheet_number  in ('100543', '100544') 
					and 	test_1_result  != 'NEGATIVE' 
					and 	accepted_result is NULL 
				);


delete from worksheet_index where worksheet_number  in ('100543', '100544');
delete from lab_worksheets where id  in ('100543', '100544');

UPDATE batches SET all_samples_tested = 'YES', date_testing_completed = '2015-10-21' 
	WHERE id IN ( 
					SELECT distinct batch_id FROM dbs_samples 
					WHERE dbs_samples.id IN 
						( 
							select sample_id from worksheet_index 
							where worksheet_number  in ('100507', '100509') 
						) 
					GROUP BY batch_id  
					HAVING sum(accepted_result is null) = 0
				);

COMMIT;

