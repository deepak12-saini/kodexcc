CREATE TABLE IF NOT EXISTS `iso_containers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `container_no` VARCHAR(64) NOT NULL,
  `customer` VARCHAR(191) NULL,
  `destination` VARCHAR(191) NOT NULL,
  `dispatched_on` DATE NOT NULL,
  `shipping_line` VARCHAR(191) NULL,
  `reference_no` VARCHAR(191) NULL,
  `notes` TEXT NULL,
  `status` VARCHAR(32) NOT NULL DEFAULT 'in_transit',
  `delivered_on` DATE NULL,
  `days_taken` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iso_containers_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
