<div class="hrms-panel">
	<h2>Assign <?php echo h($asset->asset_code); ?></h2>
	<p><?php echo h(trim(($asset->brand ?? '') . ' ' . ($asset->model ?? ''))); ?></p>
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
			<label>Assignment date</label>
			<input type="date" name="assigned_date" value="<?php echo date('Y-m-d'); ?>" required>
		</div>
		<div>
			<label>Condition at assignment</label>
			<input type="text" name="condition_on_assign" value="Good">
		</div>
		<div class="full">
			<label>Notes</label>
			<textarea name="notes" rows="3"></textarea>
		</div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Assign</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>assets/view/<?php echo (int)$asset->id; ?>">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
