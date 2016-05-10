ALTER TABLE dbs_samples ADD COLUMN sample_verified_on DATE DEFAULT NULL AFTER sample_verified_by;
UPDATE dbs_samples SET sample_verified_on = '1971-01-01' WHERE sample_verified_on IS NULL;
