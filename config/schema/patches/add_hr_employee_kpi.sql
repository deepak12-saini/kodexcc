ALTER TABLE `hr_employees`
  ADD COLUMN IF NOT EXISTS `kpi` TEXT NULL AFTER `status`;
