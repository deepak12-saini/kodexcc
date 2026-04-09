-- Links staff/customers to lab files. Run lines once if missing; then `bin/cake schema_cache clear`
-- Table name is `lab_assign` (singular).

ALTER TABLE `lab_assign` ADD COLUMN `customer_id` INT UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `lab_assign` ADD COLUMN `lab_id` INT UNSIGNED NULL DEFAULT NULL;
