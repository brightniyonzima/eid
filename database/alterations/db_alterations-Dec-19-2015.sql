/* 
	create a new field to store IDs from old database.
	By cross-referencing them with eid.old_id with old_eid.id we can find the columns that
	were not migrated

*/
ALTER TABLE dbs_samples ADD COLUMN old_id INT UNSIGNED AFTER id;

/*
	datedispatched in old database was stored as a string (!)
	Also, some of the values for datedispatched were empty strings.
	The 2 SQL statements below attempt to correct this.
*/
UPDATE samples SET datedispatched = NULL WHERE datedispatched = '';
ALTER TABLE old_eid.samples CHANGE datedispatched datedispatched DATE;


mysql> alter table dbs_samples change infant_contact_phone infant_contact_phone varchar(64);
mysql> alter table dbs_samples change f_ART_number f_ART_number varchar(60);

