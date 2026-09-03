<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit' : 'Add'; ?> Holiday</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Name</label>
			<input type="text" name="name" value="<?php echo h($entity->name ?? ''); ?>" required>
		</div>
		<div>
			<label>Date</label>
			<input type="date" name="holiday_date" value="<?php echo h(is_object($entity->holiday_date ?? null) ? $entity->holiday_date->format('Y-m-d') : ($entity->holiday_date ?? '')); ?>" required>
		</div>
		<div>
			<label>Type</label>
			<select name="type">
				<option value="public" <?php echo ($entity->type ?? '') === 'public' ? 'selected' : ''; ?>>Public</option>
				<option value="company" <?php echo ($entity->type ?? '') === 'company' ? 'selected' : ''; ?>>Company</option>
			</select>
		</div>
		<div>
			<label>Optional</label>
			<select name="is_optional">
				<option value="0" <?php echo (int)($entity->is_optional ?? 0) === 0 ? 'selected' : ''; ?>>No</option>
				<option value="1" <?php echo (int)($entity->is_optional ?? 0) === 1 ? 'selected' : ''; ?>>Yes</option>
			</select>
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
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/holidays">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
