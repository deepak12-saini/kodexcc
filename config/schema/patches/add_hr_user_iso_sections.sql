ALTER TABLE `hr_users`
  ADD COLUMN IF NOT EXISTS `iso_sections` TEXT NULL AFTER `iso_role`;
