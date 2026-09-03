-- Sample manager + employee for ESS / approval smoke tests (safe to re-run)
INSERT IGNORE INTO `hr_employees` (`id`, `employee_code`, `full_name`, `email`, `mobile`, `department_id`, `designation_id`, `joining_date`, `date_of_birth`, `employment_type`, `manager_id`, `shift_id`, `status`, `created`, `modified`) VALUES
(2, 'KDX-EMP-002', 'Alex Manager', 'manager@kodexcc.com', '1800418496', 3, 2, CURDATE(), '1988-06-20', 'Full-time', NULL, 1, 'active', NOW(), NOW()),
(3, 'KDX-EMP-003', 'Sam Employee', 'employee@kodexcc.com', '1800418497', 3, 7, CURDATE(), '1995-03-10', 'Full-time', 2, 1, 'active', NOW(), NOW());

INSERT IGNORE INTO `hr_users` (`id`, `employee_id`, `username`, `password`, `role`, `is_active`, `created`, `modified`) VALUES
(3, 2, 'manager', SHA2('manager123', 256), 'manager', 1, NOW(), NOW()),
(4, 3, 'employee', SHA2('employee123', 256), 'employee', 1, NOW(), NOW());
