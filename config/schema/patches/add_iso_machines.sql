CREATE TABLE IF NOT EXISTS `iso_machines` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_iso_machine_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `iso_batches` MODIFY `machine` VARCHAR(120) NULL;
ALTER TABLE `iso_maintenance` MODIFY `machine` VARCHAR(120) NOT NULL;
