-- Patch existing DBs where lab_assign was created from an older init_schema (placeholder columns only).
-- Apply: mysql -u USER -p DATABASE < config/schema/patches/add_lab_assign_customer_lab_id.sql
-- Skip any line that errors with "Duplicate column name".

ALTER TABLE `lab_assign` ADD COLUMN `customer_id` INT UNSIGNED NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `lab_assign` ADD COLUMN `lab_id` INT UNSIGNED NULL DEFAULT NULL AFTER `customer_id`;
