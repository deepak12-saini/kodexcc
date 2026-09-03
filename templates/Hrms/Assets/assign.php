<div class="hrms-panel">
	<h2>Assign Asset — <?php echo h($asset->name); ?> (<?php echo h($asset->asset_code); ?>)</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Employee</label>
			<select name="employee_id" required>
				<option value="">Select</option>
				<?php foreach ($employees as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>"><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Issue Date</label>
			<input type="date" name="issue_date" value="<?php echo date('Y-m-d'); ?>" required>
		</div>
		<div>
			<label>Condition on Issue</label>
			<input type="text" name="condition_on_issue" value="<?php echo h($asset->condition_label ?? 'Good'); ?>">
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Assign</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/assets">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
