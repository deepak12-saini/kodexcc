-- VOC certificate admin form fields. CakePHP only persists columns that exist in MySQL.
-- Run each line once; skip duplicates. Then: `bin/cake schema_cache clear`

ALTER TABLE `voc_certificate` ADD COLUMN `certificate_number` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `date` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `simple_description` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `date_tested` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `test_method` TEXT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `sepecification` TEXT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `product_name` TEXT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `sepecification_2` TEXT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `weight` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `voc_certificate` ADD COLUMN `description` TEXT NULL;
