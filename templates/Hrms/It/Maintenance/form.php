<?php
$dateVal = function ($v): string {
	if ($v === null || $v === '') return '';
	if (is_object($v) && method_exists($v, 'format')) return $v->format('Y-m-d');
	return substr((string)$v, 0, 10);
};
?>
<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit maintenance' : 'Schedule maintenance'; ?></h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Asset</label>
			<select name="asset_id" required>
				<option value="">Select</option>
				<?php foreach ($assets as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->asset_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div><label>Maintenance type</label><input type="text" name="maintenance_type" required value="<?php echo h($entity->maintenance_type ?? ''); ?>" placeholder="Cleaning, OS update, AMC visit"></div>
		<div><label>Scheduled date</label><input type="date" name="scheduled_date" value="<?php echo h($dateVal($entity->scheduled_date ?? null)); ?>"></div>
		<div><label>Completed date</label><input type="date" name="completed_date" value="<?php echo h($dateVal($entity->completed_date ?? null)); ?>"></div>
		<div><label>Cost</label><input type="number" step="0.01" min="0" name="cost" value="<?php echo h($entity->cost ?? ''); ?>"></div>
		<div>
			<label>Vendor</label>
			<select name="vendor_id">
				<option value="">—</option>
				<?php foreach ($vendors as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->vendor_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div><label>Person</label><input type="text" name="performed_by" value="<?php echo h($entity->performed_by ?? ''); ?>"></div>
		<div>
			<label>Status</label>
			<select name="status">
				<?php foreach ($maintenanceStatuses as $s): ?>
					<option value="<?php echo h($s); ?>" <?php echo ($entity->status ?? 'scheduled') === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full"><label>Description</label><textarea name="description" rows="2"><?php echo h($entity->description ?? ''); ?></textarea></div>
		<div class="full"><label>Notes</label><textarea name="notes" rows="2"><?php echo h($entity->notes ?? ''); ?></textarea></div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Save</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>maintenance">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
