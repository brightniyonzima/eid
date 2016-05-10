/*
	Goal = Add a new rejection reason (Missing Age). This is done in the INSERT statement.
			The statements before prepare the table for the INSERT.
*/
UPDATE appendices SET created = '1921-01-01';/* MySQL 5.7 no longer accepts '0000-00-00' */
ALTER TABLE appendices CHANGE created created DATETIME DEFAULT CURRENT_TIMESTAMP; /* set valid default */
ALTER TABLE appendices CHANGE code code TINYINT UNSIGNED; /* do not store int as varchar */
INSERT INTO appendices (id, code, appendix, categoryID, inactive, created, createdby) 
				VALUES (null, 17, 'Age Missing = No EID test allowed', 6, 0, null, 'system');

