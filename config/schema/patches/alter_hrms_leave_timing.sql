-- Add leave timing / duration fields (safe to re-run)
ALTER TABLE `hr_leave_requests`
  ADD COLUMN IF NOT EXISTS `duration_type` VARCHAR(20) NOT NULL DEFAULT 'full_day' AFTER `days`,
  ADD COLUMN IF NOT EXISTS `half_day_session` VARCHAR(20) NULL AFTER `duration_type`,
  ADD COLUMN IF NOT EXISTS `start_time` TIME NULL AFTER `half_day_session`,
  ADD COLUMN IF NOT EXISTS `end_time` TIME NULL AFTER `start_time`;
