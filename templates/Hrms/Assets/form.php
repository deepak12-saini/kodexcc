<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit' : 'Add'; ?> Asset</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Asset Code</label>
			<input type="text" name="asset_code" value="<?php echo h($entity->asset_code ?? ''); ?>" placeholder="Auto if blank">
		</div>
		<div>
			<label>Name</label>
			<input type="text" name="name" value="<?php echo h($entity->name ?? ''); ?>" required>
		</div>
		<div>
			<label>Serial Number</label>
			<input type="text" name="serial_number" value="<?php echo h($entity->serial_number ?? ''); ?>">
		</div>
		<div>
			<label>Purchase Date</label>
			<input type="date" name="purchase_date" value="<?php echo h(is_object($entity->purchase_date ?? null) ? $entity->purchase_date->format('Y-m-d') : ($entity->purchase_date ?? '')); ?>">
		</div>
		<div>
			<label>Condition</label>
			<input type="text" name="condition_label" value="<?php echo h($entity->condition_label ?? 'Good'); ?>">
		</div>
		<div>
			<label>Status</label>
			<select name="status">
				<?php foreach (['available', 'assigned', 'maintenance', 'retired'] as $s): ?>
					<option value="<?php echo $s; ?>" <?php echo ($entity->status ?? 'available') === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full">
			<label>Notes</label>
			<textarea name="notes" rows="3"><?php echo h($entity->notes ?? ''); ?></textarea>
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Save</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/assets">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
