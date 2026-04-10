-- Links NappUser (staff/customer) rows to lab_file via lab_id. Required for /admin/users/staff contain.
-- Run each statement once; if "Duplicate column", skip that line and continue.
-- Then: bin/cake schema_cache clear

ALTER TABLE `lab_assign` ADD COLUMN `customer_id` INT UNSIGNED NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `lab_assign` ADD COLUMN `lab_id` INT UNSIGNED NULL DEFAULT NULL AFTER `customer_id`;
