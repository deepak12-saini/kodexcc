-- HRMS Phase 1 core schema (safe to re-run)
-- Internal portal tables — separate from public website / legacy attendances

CREATE TABLE IF NOT EXISTS `hr_departments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `code` VARCHAR(32) NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_departments_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_designations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `department_id` INT UNSIGNED NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_hr_designations_dept` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_shifts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(80) NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `grace_minutes` INT NOT NULL DEFAULT 15,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_employees` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_code` VARCHAR(40) NOT NULL,
  `full_name` VARCHAR(160) NOT NULL,
  `photo` VARCHAR(255) NULL,
  `email` VARCHAR(160) NULL,
  `mobile` VARCHAR(40) NULL,
  `department_id` INT UNSIGNED NULL,
  `designation_id` INT UNSIGNED NULL,
  `joining_date` DATE NULL,
  `date_of_birth` DATE NULL,
  `employment_type` VARCHAR(40) NULL DEFAULT 'Full-time',
  `manager_id` INT UNSIGNED NULL,
  `work_location` VARCHAR(160) NULL,
  `address` TEXT NULL,
  `emergency_contact_name` VARCHAR(120) NULL,
  `emergency_contact_phone` VARCHAR(40) NULL,
  `bank_name` VARCHAR(120) NULL,
  `bank_account` VARCHAR(80) NULL,
  `bank_ifsc` VARCHAR(40) NULL,
  `shift_id` INT UNSIGNED NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_employees_code` (`employee_code`),
  KEY `idx_hr_employees_dept` (`department_id`),
  KEY `idx_hr_employees_manager` (`manager_id`),
  KEY `idx_hr_employees_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` INT UNSIGNED NULL,
  `username` VARCHAR(80) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'employee',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login` DATETIME NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_users_username` (`username`),
  KEY `idx_hr_users_employee` (`employee_id`),
  KEY `idx_hr_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_attendances` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` INT UNSIGNED NOT NULL,
  `attendance_date` DATE NOT NULL,
  `clock_in` DATETIME NULL,
  `clock_out` DATETIME NULL,
  `status` VARCHAR(30) NOT NULL DEFAULT 'present',
  `late_minutes` INT NOT NULL DEFAULT 0,
  `early_leave_minutes` INT NOT NULL DEFAULT 0,
  `overtime_minutes` INT NOT NULL DEFAULT 0,
  `is_half_day` TINYINT(1) NOT NULL DEFAULT 0,
  `correction_note` TEXT NULL,
  `correction_status` VARCHAR(20) NULL,
  `notes` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_att_emp_date` (`employee_id`, `attendance_date`),
  KEY `idx_hr_att_date` (`attendance_date`),
  KEY `idx_hr_att_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_leave_types` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(80) NOT NULL,
  `code` VARCHAR(20) NOT NULL,
  `is_paid` TINYINT(1) NOT NULL DEFAULT 1,
  `annual_quota` DECIMAL(6,1) NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_leave_types_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_leave_balances` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` INT UNSIGNED NOT NULL,
  `leave_type_id` INT UNSIGNED NOT NULL,
  `year` SMALLINT NOT NULL,
  `allocated` DECIMAL(6,1) NOT NULL DEFAULT 0,
  `used` DECIMAL(6,1) NOT NULL DEFAULT 0,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_leave_bal` (`employee_id`, `leave_type_id`, `year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_leave_requests` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` INT UNSIGNED NOT NULL,
  `leave_type_id` INT UNSIGNED NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `days` DECIMAL(6,1) NOT NULL DEFAULT 1,
  `duration_type` VARCHAR(20) NOT NULL DEFAULT 'full_day',
  `half_day_session` VARCHAR(20) NULL,
  `start_time` TIME NULL,
  `end_time` TIME NULL,
  `reason` TEXT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `manager_status` VARCHAR(20) NULL,
  `manager_id` INT UNSIGNED NULL,
  `manager_remark` TEXT NULL,
  `hr_status` VARCHAR(20) NULL,
  `hr_remark` TEXT NULL,
  `approved_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_hr_leave_emp` (`employee_id`),
  KEY `idx_hr_leave_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_assets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_code` VARCHAR(60) NOT NULL,
  `name` VARCHAR(160) NOT NULL,
  `serial_number` VARCHAR(120) NULL,
  `purchase_date` DATE NULL,
  `condition_label` VARCHAR(40) NULL DEFAULT 'Good',
  `status` VARCHAR(20) NOT NULL DEFAULT 'available',
  `notes` TEXT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_hr_assets_code` (`asset_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_asset_assignments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` INT UNSIGNED NOT NULL,
  `employee_id` INT UNSIGNED NOT NULL,
  `issue_date` DATE NOT NULL,
  `return_date` DATE NULL,
  `condition_on_issue` VARCHAR(40) NULL,
  `condition_on_return` VARCHAR(40) NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'assigned',
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_hr_asset_assign_emp` (`employee_id`),
  KEY `idx_hr_asset_assign_asset` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_documents` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` INT UNSIGNED NOT NULL,
  `doc_type` VARCHAR(80) NOT NULL,
  `title` VARCHAR(160) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `uploaded_by` INT UNSIGNED NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_hr_docs_emp` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed masters
INSERT IGNORE INTO `hr_departments` (`id`, `name`, `code`, `status`, `created`, `modified`) VALUES
(1, 'HR', 'HR', 1, NOW(), NOW()),
(2, 'Finance', 'FIN', 1, NOW(), NOW()),
(3, 'Sales', 'SAL', 1, NOW(), NOW()),
(4, 'Marketing', 'MKT', 1, NOW(), NOW()),
(5, 'Production', 'PRD', 1, NOW(), NOW()),
(6, 'R&D', 'RND', 1, NOW(), NOW()),
(7, 'Quality', 'QA', 1, NOW(), NOW()),
(8, 'Operations', 'OPS', 1, NOW(), NOW()),
(9, 'IT', 'IT', 1, NOW(), NOW()),
(10, 'Administration', 'ADM', 1, NOW(), NOW());

INSERT IGNORE INTO `hr_designations` (`id`, `name`, `department_id`, `status`, `created`, `modified`) VALUES
(1, 'Director', NULL, 1, NOW(), NOW()),
(2, 'Manager', NULL, 1, NOW(), NOW()),
(3, 'HR Executive', 1, 1, NOW(), NOW()),
(4, 'Developer', 9, 1, NOW(), NOW()),
(5, 'Accountant', 2, 1, NOW(), NOW()),
(6, 'Production Manager', 5, 1, NOW(), NOW()),
(7, 'Sales Executive', 3, 1, NOW(), NOW()),
(8, 'Quality Analyst', 7, 1, NOW(), NOW());

INSERT IGNORE INTO `hr_shifts` (`id`, `name`, `start_time`, `end_time`, `grace_minutes`, `status`, `created`, `modified`) VALUES
(1, 'General', '09:00:00', '18:00:00', 15, 1, NOW(), NOW()),
(2, 'Morning', '07:00:00', '16:00:00', 10, 1, NOW(), NOW()),
(3, 'Evening', '14:00:00', '23:00:00', 10, 1, NOW(), NOW());

INSERT IGNORE INTO `hr_leave_types` (`id`, `name`, `code`, `is_paid`, `annual_quota`, `status`, `created`, `modified`) VALUES
(1, 'Casual Leave', 'CL', 1, 12, 1, NOW(), NOW()),
(2, 'Sick Leave', 'SL', 1, 12, 1, NOW(), NOW()),
(3, 'Annual/Earned Leave', 'EL', 1, 18, 1, NOW(), NOW()),
(4, 'Unpaid Leave', 'UL', 0, 0, 1, NOW(), NOW()),
(5, 'Other Leave', 'OL', 1, 5, 1, NOW(), NOW());

-- Default admin user (password: admin123 — SHA2 hashed)
INSERT IGNORE INTO `hr_users` (`id`, `employee_id`, `username`, `password`, `role`, `is_active`, `created`, `modified`) VALUES
(1, NULL, 'admin', SHA2('admin123', 256), 'admin', 1, NOW(), NOW());

-- Sample HR employee + login (password: hr123)
INSERT IGNORE INTO `hr_employees` (`id`, `employee_code`, `full_name`, `email`, `mobile`, `department_id`, `designation_id`, `joining_date`, `date_of_birth`, `employment_type`, `shift_id`, `status`, `created`, `modified`) VALUES
(1, 'KDX-EMP-001', 'HR Admin User', 'hr@kodexcc.com', '1800418495', 1, 3, CURDATE(), '1990-01-15', 'Full-time', 1, 'active', NOW(), NOW());

INSERT IGNORE INTO `hr_users` (`id`, `employee_id`, `username`, `password`, `role`, `is_active`, `created`, `modified`) VALUES
(2, 1, 'hr', SHA2('hr123', 256), 'hr', 1, NOW(), NOW());

-- Sample manager + employee for ESS / approval smoke tests
INSERT IGNORE INTO `hr_employees` (`id`, `employee_code`, `full_name`, `email`, `mobile`, `department_id`, `designation_id`, `joining_date`, `date_of_birth`, `employment_type`, `manager_id`, `shift_id`, `status`, `created`, `modified`) VALUES
(2, 'KDX-EMP-002', 'Alex Manager', 'manager@kodexcc.com', '1800418496', 3, 2, CURDATE(), '1988-06-20', 'Full-time', NULL, 1, 'active', NOW(), NOW()),
(3, 'KDX-EMP-003', 'Sam Employee', 'employee@kodexcc.com', '1800418497', 3, 7, CURDATE(), '1995-03-10', 'Full-time', 2, 1, 'active', NOW(), NOW());

INSERT IGNORE INTO `hr_users` (`id`, `employee_id`, `username`, `password`, `role`, `is_active`, `created`, `modified`) VALUES
(3, 2, 'manager', SHA2('manager123', 256), 'manager', 1, NOW(), NOW()),
(4, 3, 'employee', SHA2('employee123', 256), 'employee', 1, NOW(), NOW());
