-- NATA module columns (legacy app expects these; init_schema had placeholder tables).
-- If a column already exists, skip that line or ignore "Duplicate column" errors.

ALTER TABLE `nata_category`
  ADD COLUMN `parent_id` INT UNSIGNED NOT NULL DEFAULT 0,
  ADD COLUMN `unique_id` INT UNSIGNED NULL DEFAULT NULL,
  ADD COLUMN `make_model_type` VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE `nata_event`
  ADD COLUMN `cate_id` INT UNSIGNED NULL DEFAULT NULL,
  ADD COLUMN `month` VARCHAR(32) NULL DEFAULT NULL,
  ADD COLUMN `year` VARCHAR(16) NULL DEFAULT NULL,
  ADD COLUMN `date` DATE NULL DEFAULT NULL,
  ADD COLUMN `admin_id` INT UNSIGNED NULL DEFAULT NULL;
