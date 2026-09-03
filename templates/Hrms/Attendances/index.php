<div class="hrms-actions">
	<form method="get" class="hrms-form" style="display:flex;gap:.75rem;align-items:end;flex-wrap:wrap;margin:0;">
		<div>
			<label>Date</label>
			<input type="date" name="date" value="<?php echo h($date); ?>">
		</div>
		<button type="submit" class="hrms-btn hrms-btn-ghost">Filter</button>
	</form>
	<?php if (in_array((string)$hrRole, ['admin', 'hr'], true)): ?>
		<a class="hrms-btn hrms-btn-primary" href="<?php echo SITEURL; ?>hrms/attendances/mark">Mark Attendance</a>
		<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/attendances/reports">Reports</a>
	<?php endif; ?>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Employee</th>
				<th>Department</th>
				<th>In</th>
				<th>Out</th>
				<th>Status</th>
				<th>Late</th>
				<th>Correction</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $a): ?>
			<tr>
				<td><?php echo h($a->hr_employee->full_name ?? ''); ?></td>
				<td><?php echo h($a->hr_employee->hr_department->name ?? ''); ?></td>
				<td><?php echo $a->clock_in ? h(is_object($a->clock_in) ? $a->clock_in->format('H:i') : $a->clock_in) : '—'; ?></td>
				<td><?php echo $a->clock_out ? h(is_object($a->clock_out) ? $a->clock_out->format('H:i') : $a->clock_out) : '—'; ?></td>
				<td><span class="badge badge-muted"><?php echo h($a->status); ?></span></td>
				<td><?php echo (int)$a->late_minutes; ?>m</td>
				<td>
					<?php if ($a->correction_status === 'pending'): ?>
						<span class="badge badge-warn">Pending</span>
						<?php if (!empty($a->correction_note)): ?>
							<div style="font-size:.8rem;color:#667085;"><?php echo h($a->correction_note); ?></div>
						<?php endif; ?>
					<?php else: ?>
						<?php echo h($a->correction_status ?: '—'); ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($a->correction_status === 'pending' && in_array((string)$hrRole, ['admin', 'hr'], true)): ?>
						<a href="<?php echo SITEURL; ?>hrms/attendances/approve-correction/<?php echo (int)$a->id; ?>?decision=approve">Approve</a>
						|
						<a href="<?php echo SITEURL; ?>hrms/attendances/approve-correction/<?php echo (int)$a->id; ?>?decision=reject">Reject</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="8">No attendance records for this date.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
