<?php
$st = $entity->start_time ?? '09:00';
$et = $entity->end_time ?? '18:00';
if (is_object($st)) { $st = $st->format('H:i'); }
if (is_object($et)) { $et = $et->format('H:i'); }
?>
<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit' : 'Add'; ?> Shift</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Name</label>
			<input type="text" name="name" value="<?php echo h($entity->name ?? ''); ?>" required>
		</div>
		<div>
			<label>Grace Minutes</label>
			<input type="number" name="grace_minutes" value="<?php echo (int)($entity->grace_minutes ?? 15); ?>">
		</div>
		<div>
			<label>Start Time</label>
			<input type="time" name="start_time" value="<?php echo h(substr((string)$st, 0, 5)); ?>" required>
		</div>
		<div>
			<label>End Time</label>
			<input type="time" name="end_time" value="<?php echo h(substr((string)$et, 0, 5)); ?>" required>
		</div>
		<div>
			<label>Status</label>
			<select name="status">
				<option value="1" <?php echo (int)($entity->status ?? 1) === 1 ? 'selected' : ''; ?>>Active</option>
				<option value="0" <?php echo (int)($entity->status ?? 1) === 0 ? 'selected' : ''; ?>>Inactive</option>
			</select>
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Save</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/shifts">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
