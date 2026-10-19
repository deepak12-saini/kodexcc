ALTER TABLE `iso_actions`
  ADD COLUMN IF NOT EXISTS `evidence_files` TEXT NULL AFTER `evidence`;
