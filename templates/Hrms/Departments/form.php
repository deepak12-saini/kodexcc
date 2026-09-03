<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit' : 'Add'; ?> Department</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Name</label>
			<input type="text" name="name" value="<?php echo h($entity->name ?? ''); ?>" required>
		</div>
		<div>
			<label>Code</label>
			<input type="text" name="code" value="<?php echo h($entity->code ?? ''); ?>">
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
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/departments">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
