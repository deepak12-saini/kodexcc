-- Columns expected by admin staff list, login, and staff forms (legacy `napp_user` is often a stub).
-- Run each line once in MySQL; skip lines that error with "Duplicate column".
-- Then: `bin/cake schema_cache clear`

ALTER TABLE `napp_user` ADD COLUMN `is_staff_id` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `napp_user` ADD COLUMN `mobile_number` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `napp_user` ADD COLUMN `designation` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `napp_user` ADD COLUMN `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `napp_user` ADD COLUMN `is_approved` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `napp_user` ADD COLUMN `insert_date` DATETIME NULL DEFAULT NULL;
ALTER TABLE `napp_user` ADD COLUMN `is_natspec_presentation` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `napp_user` ADD COLUMN `is_cpd_presentation` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `napp_user` ADD COLUMN `dept_id` INT UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `napp_user` ADD COLUMN `emp_id` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `napp_user` ADD COLUMN `productionlogin_key` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `napp_user` ADD COLUMN `is_active_otp` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `napp_user` ADD COLUMN `otp` VARCHAR(32) NULL DEFAULT NULL;
