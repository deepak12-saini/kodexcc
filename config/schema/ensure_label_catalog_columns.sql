-- Optional: columns the label admin expects (file name, sort weight). Skip lines that duplicate.
-- `label` already uses `category_id` to reference `label_category` (not `label_cate_id`).

ALTER TABLE `label` ADD COLUMN `url` VARCHAR(512) NULL DEFAULT NULL;
ALTER TABLE `label` ADD COLUMN `weight` VARCHAR(64) NULL DEFAULT NULL;

-- If your label_category uses `name` instead of `category` for the title, templates fall back to `name`.
