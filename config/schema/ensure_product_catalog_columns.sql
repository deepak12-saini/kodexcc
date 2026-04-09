-- Align `product` with admin + storefront. CakePHP only persists columns that exist in MySQL.
-- Run statements one at a time; skip any that report "Duplicate column name".
-- Then: `bin/cake schema_cache clear` (optional)

ALTER TABLE `product` ADD COLUMN `product_code` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `brief_description` TEXT NULL;
ALTER TABLE `product` ADD COLUMN `feature` TEXT NULL;
ALTER TABLE `product` ADD COLUMN `userarea` TEXT NULL;
ALTER TABLE `product` ADD COLUMN `instruction` TEXT NULL;
ALTER TABLE `product` ADD COLUMN `price_dollar` DECIMAL(12,2) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `stock` INT NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `datasheet` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `msds` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `voc_pdf` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `tds_image` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `sizes` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `meta_title` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `keyword` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `meta_description` TEXT NULL;
ALTER TABLE `product` ADD COLUMN `video_url` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `product` ADD COLUMN `is_featured` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `product` ADD COLUMN `is_image` TINYINT(1) NOT NULL DEFAULT 1;

-- Backfill created when missing (admin list date column)
UPDATE `product` SET `created` = COALESCE(`created`, `modified`, NOW()) WHERE `created` IS NULL;
