-- Cancel worksheet No. 400400 and return its samples so they can be added to a new worksheet
update dbs_samples set ready_for_SCD_test = 'YES' where id in (select sample_id from sc_worksheet_index where worksheet_number > '400420' and worksheet_number < '400465');
delete from sc_worksheet_index where worksheet_number > '400420' and worksheet_number < '400465';
delete from sc_worksheets where id > '400420' and id < '400465';

