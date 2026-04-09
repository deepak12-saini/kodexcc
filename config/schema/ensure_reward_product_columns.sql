-- Extend `reward_product` for reward points admin pages.
-- Run statements one at a time; skip any that report "Duplicate column name".
-- Then: `bin/cake schema_cache clear` (optional)

ALTER TABLE `reward_product` ADD COLUMN `size` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `reward_product` ADD COLUMN `applicator_points` INT NULL DEFAULT NULL;
ALTER TABLE `reward_product` ADD COLUMN `dealer_point` INT NULL DEFAULT NULL;

-- Some environments may already use plural column name.
ALTER TABLE `reward_product` ADD COLUMN `dealer_points` INT NULL DEFAULT NULL;

-- Backfill between singular/plural naming if both exist.
UPDATE `reward_product`
SET `dealer_point` = COALESCE(`dealer_point`, `dealer_points`)
WHERE `dealer_point` IS NULL;

UPDATE `reward_product`
SET `dealer_points` = COALESCE(`dealer_points`, `dealer_point`)
WHERE `dealer_points` IS NULL;

