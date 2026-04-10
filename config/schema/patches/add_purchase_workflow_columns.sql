-- One-time patch for databases created from an older init_schema where `purchase`
-- was a generic placeholder and line items used the wrong table name.
-- Apply via phpMyAdmin or: mysql -u USER -p DATABASE < config/schema/patches/add_purchase_workflow_columns.sql
--
-- If a statement fails with "Duplicate column name", remove or comment out that statement and re-run.

ALTER TABLE `purchase` ADD COLUMN `purchase_type` INT NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `purchase` ADD COLUMN `permitted_by` INT UNSIGNED NULL AFTER `purchase_type`;
ALTER TABLE `purchase` ADD COLUMN `prepared_by` INT UNSIGNED NULL AFTER `permitted_by`;
ALTER TABLE `purchase` ADD COLUMN `authorized_by` INT UNSIGNED NULL AFTER `prepared_by`;
ALTER TABLE `purchase` ADD COLUMN `unique_id` VARCHAR(255) NULL AFTER `authorized_by`;
ALTER TABLE `purchase` ADD COLUMN `item_details` TEXT NULL AFTER `unique_id`;
ALTER TABLE `purchase` ADD COLUMN `requisitioner_name` VARCHAR(255) NULL AFTER `item_details`;
ALTER TABLE `purchase` ADD COLUMN `date` DATE NULL AFTER `requisitioner_name`;
ALTER TABLE `purchase` ADD COLUMN `name_of_receiver` VARCHAR(255) NULL AFTER `date`;
ALTER TABLE `purchase` ADD COLUMN `final_result` TEXT NULL AFTER `name_of_receiver`;
ALTER TABLE `purchase` ADD COLUMN `ignore_id` INT NOT NULL DEFAULT 0 AFTER `final_result`;

CREATE TABLE IF NOT EXISTS `purchase_requirements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` INT UNSIGNED NULL,
  `item_name` VARCHAR(255) NULL,
  `comments` TEXT NULL,
  `quantity` VARCHAR(50) NULL,
  `description_item` TEXT NULL,
  `resource_requirement` TEXT NULL,
  `purpose_project` VARCHAR(255) NULL,
  `time` VARCHAR(255) NULL,
  `budget` VARCHAR(255) NULL,
  `remark` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_purchase_requirements_purchase_id` (`purchase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If you still have the old singular table `purchase_requirement` from a legacy schema
-- and it contains rows you need, migrate then drop:
-- INSERT INTO purchase_requirements (...) SELECT ... FROM purchase_requirement;
-- DROP TABLE purchase_requirement;
