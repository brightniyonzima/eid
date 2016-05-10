alter table dbs_samples add column repeated_SC_test enum('YES','NO') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NO';

-- Cancel worksheet No. 400400 and return its samples so they can be added to a new worksheet
update dbs_samples set ready_for_SCD_test = 'YES' where id in (select sample_id from sc_worksheet_index where worksheet_number = '400400');
delete from sc_worksheet_index where worksheet_number = '400400';
delete from sc_worksheets where id = '400400';

