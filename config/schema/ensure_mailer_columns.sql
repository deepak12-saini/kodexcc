-- Extend `mailer` for DuroEzy spec mailers (admin + staff).
-- Run statements one at a time; skip any that report "Duplicate column name".
-- Then: `bin/cake schema_cache clear` (optional)

ALTER TABLE `mailer` ADD COLUMN `admin_id` INT UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `mailer` ADD COLUMN `tracknumber` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `mailer` ADD COLUMN `address` TEXT NULL;
ALTER TABLE `mailer` ADD COLUMN `specification` TEXT NULL;
ALTER TABLE `mailer` ADD COLUMN `date` DATE NULL;
ALTER TABLE `mailer` ADD COLUMN `company` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `mailer` ADD COLUMN `project` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `mailer` ADD COLUMN `subject` VARCHAR(512) NULL DEFAULT NULL;
