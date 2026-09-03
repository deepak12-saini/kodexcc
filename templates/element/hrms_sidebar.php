<?php
$role = (string)($hrRole ?? '');
$isAdminHr = in_array($role, ['admin', 'hr'], true);
$isManager = $role === 'manager';
$base = SITEURL . 'hrms/';
$path = trim((string)$this->request->getPath(), '/');
// e.g. kodexcc/hrms/employees or hrms/employees
$here = preg_replace('#.*?hrms/?#', '', $path);
$here = trim((string)$here, '/');

$isActive = function (string $needle) use ($here): bool {
	if ($needle === 'dashboard') {
		return $here === '' || $here === 'dashboard' || str_starts_with($here, 'dashboard/');
	}
	// Prefer exact section matches so nested routes don't light two items
	if ($needle === 'attendances') {
		return $here === 'attendances' || preg_match('#^attendances/(?!reports)#', $here) === 1;
	}
	if ($needle === 'leaves') {
		return $here === 'leaves' || preg_match('#^leaves/(?!calendar)#', $here) === 1;
	}
	if ($needle === 'holidays') {
		return $here === 'holidays' || preg_match('#^holidays/(?!calendar)#', $here) === 1;
	}
	return $here === $needle || str_starts_with($here, $needle . '/');
};

$link = function (string $href, string $label, string $icon, string $match) use ($base, $isActive): void {
	$active = $isActive($match) ? ' is-active' : '';
	echo '<a class="hrms-nav-link' . $active . '" href="' . $base . $href . '">';
	echo '<span class="hrms-nav-ico" aria-hidden="true">' . $icon . '</span>';
	echo '<span>' . h($label) . '</span></a>';
};

// Compact inline SVGs
$ico = [
	'dash' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>',
	'users' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
	'dept' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/></svg>',
	'badge' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 15a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>',
	'clock' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
	'cal' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>',
	'leave' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 7V3m8 4V3M4 11h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/><path d="m9 16 2 2 4-4"/></svg>',
	'chart' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19V5M4 19h16"/><path d="M8 16V10M12 16V7M16 16v-4"/></svg>',
	'box' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z"/><path d="M12 12 4.5 7.5M12 12v9M12 12l7.5-4.5"/></svg>',
	'doc' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/><path d="M14 3v5h5M9 13h6M9 17h4"/></svg>',
	'user' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20a8 8 0 0 1 16 0"/></svg>',
	'key' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="8" cy="15" r="4"/><path d="m11.5 12.5 8-8M17 5l2 2"/></svg>',
	'out' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 17H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/><path d="M15 16l5-5-5-5M20 11H9"/></svg>',
	'shift' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/><circle cx="12" cy="12" r="3.5"/></svg>',
];
?>
<div class="hrms-brand">
	<span class="hrms-mark">K</span>
	<div>
		<strong>KodexCC</strong>
		<small>HRMS · Internal</small>
	</div>
</div>
<nav class="hrms-nav">
	<?php $link('dashboard', 'Dashboard', $ico['dash'], 'dashboard'); ?>

	<?php if ($isAdminHr): ?>
		<div class="hrms-nav-label">People</div>
		<?php
		$link('employees', 'Employees', $ico['users'], 'employees');
		$link('departments', 'Departments', $ico['dept'], 'departments');
		$link('designations', 'Designations', $ico['badge'], 'designations');
		$link('shifts', 'Shifts', $ico['shift'], 'shifts');
		?>

		<div class="hrms-nav-label">Time</div>
		<?php
		$link('attendances', 'Attendance', $ico['clock'], 'attendances');
		$link('attendances/reports', 'Reports', $ico['chart'], 'attendances/reports');
		$link('leaves', 'Leave Requests', $ico['leave'], 'leaves');
		$link('leaves/calendar', 'Leave Calendar', $ico['cal'], 'leaves/calendar');
		$link('leave-types', 'Leave Types', $ico['cal'], 'leave-types');
		?>

		<div class="hrms-nav-label">Resources</div>
		<?php
		$link('assets', 'Assets', $ico['box'], 'assets');
		$link('documents', 'Documents', $ico['doc'], 'documents');
		$link('requests', 'Requests', $ico['doc'], 'requests');
		$link('holidays', 'Holidays', $ico['cal'], 'holidays');
		$link('holidays/calendar', 'Calendar', $ico['cal'], 'holidays/calendar');
		$link('audit-logs', 'Audit Log', $ico['chart'], 'audit-logs');
		?>
	<?php elseif ($isManager): ?>
		<div class="hrms-nav-label">Team</div>
		<?php
		$link('employees', 'My Team', $ico['users'], 'employees');
		$link('attendances', 'Team Attendance', $ico['clock'], 'attendances');
		$link('leaves', 'Leave Approvals', $ico['leave'], 'leaves');
		?>
		<div class="hrms-nav-label">Self</div>
		<?php
		$link('my/attendance', 'My Attendance', $ico['clock'], 'my/attendance');
		$link('my/leaves', 'My Leaves', $ico['leave'], 'my/leaves');
		$link('my/requests', 'My Requests', $ico['doc'], 'my/requests');
		$link('my/add-request', 'Submit Request', $ico['doc'], 'my/add-request');
		$link('holidays/calendar', 'Calendar', $ico['cal'], 'holidays/calendar');
		$link('my/profile', 'My Profile', $ico['user'], 'my/profile');
		?>
	<?php else: ?>
		<div class="hrms-nav-label">Self Service</div>
		<?php
		$link('my/attendance', 'Clock In / Out', $ico['clock'], 'my/attendance');
		$link('my/leaves', 'My Leaves', $ico['leave'], 'my/leaves');
		$link('my/requests', 'My Requests', $ico['doc'], 'my/requests');
		$link('my/add-request', 'Submit Request', $ico['doc'], 'my/add-request');
		$link('my/documents', 'My Documents', $ico['doc'], 'my/documents');
		$link('my/assets', 'My Assets', $ico['box'], 'my/assets');
		$link('holidays/calendar', 'Calendar', $ico['cal'], 'holidays/calendar');
		$link('my/profile', 'My Profile', $ico['user'], 'my/profile');
		?>
	<?php endif; ?>

	<div class="hrms-nav-label">Account</div>
	<?php
	$link('users/change-password', 'Change Password', $ico['key'], 'users/change-password');
	$link('users/logout', 'Logout', $ico['out'], 'users/logout');
	?>
</nav>
