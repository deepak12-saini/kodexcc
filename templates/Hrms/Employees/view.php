<?php
$base = SITEURL . 'hrms/employees/view/' . (int)$employee->id;
$tabs = [
	'personal' => 'Personal',
	'employment' => 'Employment',
	'attendance' => 'Attendance',
	'leave' => 'Leave',
	'documents' => 'Documents',
	'assets' => 'Assets',
];
?>
<div class="hrms-actions">
	<?php if (in_array($hrRole, ['admin', 'hr'], true)): ?>
		<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/employees/edit/<?php echo (int)$employee->id; ?>">Edit</a>
	<?php endif; ?>
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/employees">Back</a>
</div>

<div class="hrms-panel">
	<h2><?php echo h($employee->full_name); ?> <small style="color:#667085;font-weight:400;"><?php echo h($employee->employee_code); ?></small></h2>
	<div class="hrms-tabs">
		<?php foreach ($tabs as $key => $label): ?>
			<a class="<?php echo $tab === $key ? 'active' : ''; ?>" href="<?php echo $base; ?>?tab=<?php echo $key; ?>"><?php echo $label; ?></a>
		<?php endforeach; ?>
		<span class="hrms-tabs"><a style="opacity:.45;pointer-events:none;">Salary (Phase 2)</a><a style="opacity:.45;pointer-events:none;">Performance (Later)</a></span>
	</div>

	<?php if ($tab === 'personal'): ?>
		<table class="hrms-table">
			<tr><th>Email</th><td><?php echo h($employee->email); ?></td></tr>
			<tr><th>Mobile</th><td><?php echo h($employee->mobile); ?></td></tr>
			<tr><th>DOB</th><td><?php echo $employee->date_of_birth ? h(is_object($employee->date_of_birth) ? $employee->date_of_birth->format('d M Y') : $employee->date_of_birth) : '—'; ?></td></tr>
			<tr><th>Address</th><td><?php echo nl2br(h((string)$employee->address)); ?></td></tr>
			<tr><th>Emergency</th><td><?php echo h($employee->emergency_contact_name); ?> · <?php echo h($employee->emergency_contact_phone); ?></td></tr>
			<tr><th>Bank</th><td><?php echo h($employee->bank_name); ?> · <?php echo h($employee->bank_account); ?> · <?php echo h($employee->bank_ifsc); ?></td></tr>
		</table>
	<?php elseif ($tab === 'employment'): ?>
		<table class="hrms-table">
			<tr><th>Department</th><td><?php echo h($employee->hr_department->name ?? '—'); ?></td></tr>
			<tr><th>Designation</th><td><?php echo h($employee->hr_designation->name ?? '—'); ?></td></tr>
			<tr><th>Joining</th><td><?php echo $employee->joining_date ? h(is_object($employee->joining_date) ? $employee->joining_date->format('d M Y') : $employee->joining_date) : '—'; ?></td></tr>
			<tr><th>Type</th><td><?php echo h($employee->employment_type); ?></td></tr>
			<tr><th>Manager</th><td><?php echo h($employee->manager->full_name ?? '—'); ?></td></tr>
			<tr><th>Location</th><td><?php echo h($employee->work_location); ?></td></tr>
			<tr><th>Shift</th><td><?php echo h($employee->hr_shift->name ?? '—'); ?></td></tr>
			<tr><th>Status</th><td><?php echo h($employee->status); ?></td></tr>
			<tr><th>Login</th><td><?php echo h($employee->hr_user->username ?? 'No login'); ?> (<?php echo h($employee->hr_user->role ?? '—'); ?>)</td></tr>
		</table>
	<?php elseif ($tab === 'attendance'): ?>
		<table class="hrms-table">
			<thead><tr><th>Date</th><th>In</th><th>Out</th><th>Status</th></tr></thead>
			<tbody>
			<?php foreach ($attendances as $a): ?>
				<tr>
					<td><?php echo h(is_object($a->attendance_date) ? $a->attendance_date->format('Y-m-d') : $a->attendance_date); ?></td>
					<td><?php echo $a->clock_in ? h(is_object($a->clock_in) ? $a->clock_in->format('H:i') : $a->clock_in) : '—'; ?></td>
					<td><?php echo $a->clock_out ? h(is_object($a->clock_out) ? $a->clock_out->format('H:i') : $a->clock_out) : '—'; ?></td>
					<td><?php echo h($a->status); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php elseif ($tab === 'leave'): ?>
		<h3>Balances</h3>
		<table class="hrms-table">
			<thead><tr><th>Type</th><th>Allocated</th><th>Used</th><th>Left</th></tr></thead>
			<tbody>
			<?php foreach ($balances as $b): ?>
				<tr>
					<td><?php echo h($b->hr_leave_type->name ?? ''); ?></td>
					<td><?php echo h($b->allocated); ?></td>
					<td><?php echo h($b->used); ?></td>
					<td><?php echo h((float)$b->allocated - (float)$b->used); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<h3 style="margin-top:1rem;">Requests</h3>
		<table class="hrms-table">
			<thead><tr><th>Type</th><th>Dates</th><th>Days</th><th>Status</th></tr></thead>
			<tbody>
			<?php foreach ($leaveRequests as $l): ?>
				<tr>
					<td><?php echo h($l->hr_leave_type->name ?? ''); ?></td>
					<td><?php echo h($l->start_date); ?> → <?php echo h($l->end_date); ?></td>
					<td><?php echo h($l->days); ?></td>
					<td><?php echo h($l->status); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php elseif ($tab === 'documents'): ?>
		<table class="hrms-table">
			<thead><tr><th>Type</th><th>Title</th><th>Uploaded</th></tr></thead>
			<tbody>
			<?php foreach ($documents as $d): ?>
				<tr>
					<td><?php echo h($d->doc_type); ?></td>
					<td><a href="<?php echo SITEURL . h($d->file_path); ?>" target="_blank"><?php echo h($d->title); ?></a></td>
					<td><?php echo h($d->created); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php elseif ($tab === 'assets'): ?>
		<table class="hrms-table">
			<thead><tr><th>Asset</th><th>Code</th><th>Issued</th><th>Status</th></tr></thead>
			<tbody>
			<?php foreach ($assets as $as): ?>
				<tr>
					<td><?php echo h($as->hr_asset->name ?? ''); ?></td>
					<td><?php echo h($as->hr_asset->asset_code ?? ''); ?></td>
					<td><?php echo h($as->issue_date); ?></td>
					<td><?php echo h($as->status); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
