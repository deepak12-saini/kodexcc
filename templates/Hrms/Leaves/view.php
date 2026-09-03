<div class="hrms-panel">
	<h2>Leave Request #<?php echo (int)$item->id; ?></h2>
	<table class="hrms-table">
		<tr><th>Employee</th><td><?php echo h($item->hr_employee->full_name ?? ''); ?></td></tr>
		<tr><th>Type</th><td><?php echo h($item->hr_leave_type->name ?? ''); ?></td></tr>
		<tr><th>Dates</th><td><?php
			$start = is_object($item->start_date) ? $item->start_date->format('Y-m-d') : $item->start_date;
			$end = is_object($item->end_date) ? $item->end_date->format('Y-m-d') : $item->end_date;
			$dur = (string)($item->duration_type ?? 'full_day');
			$ft = function ($t) {
				if (!$t) return '';
				return is_object($t) ? $t->format('H:i') : substr((string)$t, 0, 5);
			};
			if ($dur === 'half_day') {
				$session = ($item->half_day_session ?? '') === 'second_half' ? '2nd half' : '1st half';
				echo h($start . ' · Half day (' . $session . ')');
				if ($item->start_time || $item->end_time) {
					echo h(' · ' . $ft($item->start_time) . '–' . $ft($item->end_time));
				}
			} elseif ($dur === 'hourly') {
				echo h($start . ' · ' . $ft($item->start_time) . '–' . $ft($item->end_time));
			} else {
				echo h($start . ($start === $end ? ' · Full day' : ' → ' . $end . ' · Full day'));
			}
			echo ' (' . h($item->days) . ' day' . ((float)$item->days == 1.0 ? '' : 's') . ')';
		?></td></tr>
		<tr><th>Reason</th><td><?php echo nl2br(h($item->reason)); ?></td></tr>
		<tr><th>Status</th><td><?php echo h($item->status); ?></td></tr>
		<tr><th>Manager</th><td><?php echo h($item->manager_status ?: '—'); ?> <?php echo $item->manager_remark ? '— ' . h($item->manager_remark) : ''; ?></td></tr>
		<tr><th>HR</th><td><?php echo h($item->hr_status ?: '—'); ?> <?php echo $item->hr_remark ? '— ' . h($item->hr_remark) : ''; ?></td></tr>
	</table>

	<?php
	$role = (string)$hrRole;
	$canAct = false;
	if ($role === 'manager' && in_array($item->status, ['pending'], true)) {
		$canAct = true;
	}
	if (in_array($role, ['admin', 'hr'], true) && in_array($item->status, ['pending', 'pending_hr'], true)) {
		$canAct = true;
	}
	?>
	<?php if ($canAct): ?>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form', 'style' => 'margin-top:1.25rem;']); ?>
		<div>
			<label>Remark</label>
			<textarea name="remark" rows="3"></textarea>
		</div>
		<div style="display:flex;gap:.5rem;margin-top:.75rem;">
			<button type="submit" name="decision" value="approved" class="hrms-btn hrms-btn-primary">Approve</button>
			<button type="submit" name="decision" value="rejected" class="hrms-btn hrms-btn-ghost">Reject</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/leaves">Back</a>
		</div>
	<?php echo $this->Form->end(); ?>
	<?php else: ?>
		<p style="margin-top:1rem;"><a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/leaves">Back</a></p>
	<?php endif; ?>
</div>
