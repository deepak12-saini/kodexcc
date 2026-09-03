<div class="hrms-actions">
	<form method="get" class="hrms-form" style="display:flex;gap:.5rem;align-items:end;margin:0;">
		<div>
			<label>Status</label>
			<select name="status">
				<option value="">All</option>
				<?php foreach (['pending', 'pending_hr', 'approved', 'rejected', 'cancelled'] as $s): ?>
					<option value="<?php echo $s; ?>" <?php echo $status === $s ? 'selected' : ''; ?>><?php echo h(ucfirst(str_replace('_', ' ', $s))); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="hrms-btn hrms-btn-ghost">Filter</button>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Employee</th>
				<th>Type</th>
				<th>When</th>
				<th>Days</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<?php
			$start = is_object($item->start_date) ? $item->start_date->format('Y-m-d') : (string)$item->start_date;
			$end = is_object($item->end_date) ? $item->end_date->format('Y-m-d') : (string)$item->end_date;
			$dur = (string)($item->duration_type ?? 'full_day');
			$ft = function ($t) {
				if (!$t) return '';
				return is_object($t) ? $t->format('H:i') : substr((string)$t, 0, 5);
			};
			if ($dur === 'half_day') {
				$session = ($item->half_day_session ?? '') === 'second_half' ? '2nd half' : '1st half';
				$when = $start . ' · Half (' . $session . ')';
				if ($item->start_time || $item->end_time) {
					$when .= ' ' . $ft($item->start_time) . '–' . $ft($item->end_time);
				}
			} elseif ($dur === 'hourly') {
				$when = $start . ' · ' . $ft($item->start_time) . '–' . $ft($item->end_time);
			} else {
				$when = $start === $end ? $start . ' · Full day' : $start . ' → ' . $end;
			}
			?>
			<tr>
				<td><?php echo h($item->hr_employee->full_name ?? ''); ?></td>
				<td><?php echo h($item->hr_leave_type->name ?? ''); ?></td>
				<td><?php echo h($when); ?></td>
				<td><?php echo h($item->days); ?></td>
				<td><span class="badge badge-muted"><?php echo h($item->status); ?></span></td>
				<td><a href="<?php echo SITEURL; ?>hrms/leaves/view/<?php echo (int)$item->id; ?>">Review</a></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="6">No leave requests.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
