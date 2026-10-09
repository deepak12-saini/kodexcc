-- ISO role on the employee login. Separate from the HR role column.
ALTER TABLE `hr_users` ADD COLUMN `iso_role` VARCHAR(40) NULL DEFAULT NULL AFTER `role`;
