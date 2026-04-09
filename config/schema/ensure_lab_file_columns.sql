-- Lab price PDF metadata (used with `lab_assign` → `lab_file` on staff list).
-- Run each line once; skip lines that error with "Duplicate column".
-- Then: `bin/cake schema_cache clear`

ALTER TABLE `lab_file` ADD COLUMN `label` VARCHAR(255) NULL DEFAULT NULL;
-- Stub schemas may already have a `type` column (different meaning); skip if duplicate.
ALTER TABLE `lab_file` ADD COLUMN `type` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `lab_file` ADD COLUMN `dir` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `lab_file` ADD COLUMN `filename` VARCHAR(255) NULL DEFAULT NULL;
