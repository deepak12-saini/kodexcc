<div class="hrms-panel">
	<h2>Transfer <?php echo h($row->it_asset->asset_code ?? ''); ?></h2>
	<p>Currently with <?php echo h($row->hr_employee->full_name ?? ''); ?>. The current assignment is closed and a new one is added.</p>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>New employee</label>
			<select name="employee_id" required>
				<option value="">Select</option>
				<?php foreach ($employees as $id => $name): ?>
					<?php if ((int)$id === (int)$row->employee_id) continue; ?>
					<option value="<?php echo (int)$id; ?>"><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Transfer date</label>
			<input type="date" name="assigned_date" value="<?php echo date('Y-m-d'); ?>" required>
		</div>
		<div>
			<label>Condition on return</label>
			<input type="text" name="condition_on_return" value="<?php echo h($row->condition_on_assign ?: 'Good'); ?>">
		</div>
		<div>
			<label>Condition for new holder</label>
			<input type="text" name="condition_on_assign" value="Good">
		</div>
		<div class="full">
			<label>Notes</label>
			<textarea name="notes" rows="3"></textarea>
		</div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Transfer</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>assets/view/<?php echo (int)$row->asset_id; ?>">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
