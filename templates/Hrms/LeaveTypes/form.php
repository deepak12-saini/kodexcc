<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit' : 'Add'; ?> Leave Type</h2>
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
			<label>Annual Quota</label>
			<input type="number" step="0.5" name="annual_quota" value="<?php echo h($entity->annual_quota ?? 12); ?>" required>
		</div>
		<div>
			<label>Paid</label>
			<select name="is_paid">
				<option value="1" <?php echo (int)($entity->is_paid ?? 1) === 1 ? 'selected' : ''; ?>>Yes</option>
				<option value="0" <?php echo (int)($entity->is_paid ?? 1) === 0 ? 'selected' : ''; ?>>No</option>
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
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/leave-types">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
