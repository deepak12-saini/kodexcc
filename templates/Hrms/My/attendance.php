<?php
$shift = $employee->hr_shift ?? null;
$shiftLabel = '—';
if ($shift) {
	$st = is_object($shift->start_time) ? $shift->start_time->format('H:i') : substr((string)$shift->start_time, 0, 5);
	$et = is_object($shift->end_time) ? $shift->end_time->format('H:i') : substr((string)$shift->end_time, 0, 5);
	$shiftLabel = $shift->name . ' · ' . $st . '–' . $et . ' (grace ' . (int)$shift->grace_minutes . 'm)';
}
?>
<div class="hrms-cards">
	<div class="hrms-card">
		<div class="label">Today</div>
		<div class="value" style="font-size:1rem;">
			<?php if (!empty($todayRow) && $todayRow->clock_in): ?>
				<span class="badge badge-ok"><?php echo h(strtoupper($todayRow->status)); ?></span>
				<div style="margin-top:.4rem;font-size:.85rem;color:#667085;">
					In <?php echo h(is_object($todayRow->clock_in) ? $todayRow->clock_in->format('H:i') : $todayRow->clock_in); ?>
					<?php if ($todayRow->clock_out): ?>
						· Out <?php echo h(is_object($todayRow->clock_out) ? $todayRow->clock_out->format('H:i') : $todayRow->clock_out); ?>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<span class="badge badge-muted">Not clocked in</span>
			<?php endif; ?>
		</div>
	</div>
	<div class="hrms-card">
		<div class="label">My Shift</div>
		<div class="value" style="font-size:.95rem;"><?php echo h($shiftLabel); ?></div>
	</div>
</div>

<div class="hrms-actions">
	<?php if (empty($todayRow) || empty($todayRow->clock_in)): ?>
		<?php echo $this->Form->create(null); ?>
			<input type="hidden" name="action" value="clock_in">
			<button type="submit" class="hrms-btn hrms-btn-primary">Clock In</button>
		<?php echo $this->Form->end(); ?>
	<?php elseif (empty($todayRow->clock_out)): ?>
		<?php echo $this->Form->create(null); ?>
			<input type="hidden" name="action" value="clock_out">
			<button type="submit" class="hrms-btn hrms-btn-primary">Clock Out</button>
		<?php echo $this->Form->end(); ?>
	<?php endif; ?>
</div>

<div class="hrms-panel">
	<h2>Request Correction</h2>
	<p style="color:#5b6b7c;font-size:.88rem;margin:0 0 .75rem;">Use this if you forgot to clock in/out. You can correct today or a past date.</p>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<input type="hidden" name="action" value="correction">
		<div>
			<label>Attendance Date</label>
			<input type="date" name="correction_date" value="<?php echo date('Y-m-d'); ?>" required>
		</div>
		<div>
			<label>Proposed Clock In (optional)</label>
			<input type="time" name="proposed_clock_in">
		</div>
		<div>
			<label>Proposed Clock Out (optional)</label>
			<input type="time" name="proposed_clock_out">
		</div>
		<div class="full">
			<label>Note</label>
			<textarea name="correction_note" rows="3" required placeholder="e.g. I forgot to clock out at 6:05 PM"></textarea>
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-ghost">Submit Correction</button>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

<div class="hrms-panel">
	<h2>Recent History</h2>
	<table class="hrms-table">
		<thead><tr><th>Date</th><th>In</th><th>Out</th><th>Status</th><th>Correction</th></tr></thead>
		<tbody>
		<?php foreach ($history as $a): ?>
			<tr>
				<td><?php echo h(is_object($a->attendance_date) ? $a->attendance_date->format('Y-m-d') : $a->attendance_date); ?></td>
				<td><?php echo $a->clock_in ? h(is_object($a->clock_in) ? $a->clock_in->format('H:i') : $a->clock_in) : '—'; ?></td>
				<td><?php echo $a->clock_out ? h(is_object($a->clock_out) ? $a->clock_out->format('H:i') : $a->clock_out) : '—'; ?></td>
				<td><?php echo h($a->status); ?></td>
				<td><?php echo h($a->correction_status ?: '—'); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination', ['items' => $history]); ?>
</div>
