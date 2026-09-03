<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit' : 'Add'; ?> Designation</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Name</label>
			<input type="text" name="name" value="<?php echo h($entity->name ?? ''); ?>" required>
		</div>
		<div>
			<label>Department</label>
			<select name="department_id">
				<option value="">— Any —</option>
				<?php foreach ($departments as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->department_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
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
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/designations">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
