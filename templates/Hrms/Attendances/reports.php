<div class="hrms-panel">
	<h2>Attendance Reports</h2>
	<form method="get" class="hrms-form grid-2">
		<div>
			<label>From</label>
			<input type="date" name="from" value="<?php echo h($from); ?>">
		</div>
		<div>
			<label>To</label>
			<input type="date" name="to" value="<?php echo h($to); ?>">
		</div>
		<div>
			<label>Employee</label>
			<select name="employee_id">
				<option value="">All</option>
				<?php foreach ($employees as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (string)$employeeId === (string)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Department</label>
			<select name="department_id">
				<option value="">All</option>
				<?php foreach ($departments as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (string)$departmentId === (string)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full" style="display:flex;gap:.5rem;">
			<button type="submit" class="hrms-btn hrms-btn-primary">Run Report</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/attendances/reports?from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>&employee_id=<?php echo urlencode((string)$employeeId); ?>&department_id=<?php echo urlencode((string)$departmentId); ?>&export=csv">Export CSV</a>
		</div>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Date</th>
				<th>Employee</th>
				<th>Dept</th>
				<th>In</th>
				<th>Out</th>
				<th>Status</th>
				<th>Late</th>
				<th>OT</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $a): ?>
			<tr>
				<td><?php echo h(is_object($a->attendance_date) ? $a->attendance_date->format('Y-m-d') : $a->attendance_date); ?></td>
				<td><?php echo h($a->hr_employee->full_name ?? ''); ?></td>
				<td><?php echo h($a->hr_employee->hr_department->name ?? ''); ?></td>
				<td><?php echo $a->clock_in ? h(is_object($a->clock_in) ? $a->clock_in->format('H:i') : $a->clock_in) : '—'; ?></td>
				<td><?php echo $a->clock_out ? h(is_object($a->clock_out) ? $a->clock_out->format('H:i') : $a->clock_out) : '—'; ?></td>
				<td><?php echo h($a->status); ?></td>
				<td><?php echo (int)$a->late_minutes; ?></td>
				<td><?php echo (int)$a->overtime_minutes; ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="8">No records in range.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
