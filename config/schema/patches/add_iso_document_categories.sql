CREATE TABLE IF NOT EXISTS `iso_document_categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_iso_doc_cat_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `iso_document_categories` (`name`, `created`) VALUES
('SOP', NOW()),
('Work instruction', NOW()),
('Form', NOW()),
('Record', NOW());

ALTER TABLE `iso_documents` MODIFY `doc_type` VARCHAR(120) NOT NULL DEFAULT 'sop';
