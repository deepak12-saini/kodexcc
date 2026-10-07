<div class="hrms-panel">
	<h2>Return <?php echo h($row->it_asset->asset_code ?? ''); ?></h2>
	<p>From <?php echo h($row->hr_employee->full_name ?? ''); ?>. This record stays in the history.</p>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Return date</label>
			<input type="date" name="return_date" value="<?php echo date('Y-m-d'); ?>" required>
		</div>
		<div>
			<label>Condition at return</label>
			<input type="text" name="condition_on_return" value="Good" required>
		</div>
		<div>
			<label>Asset status after return</label>
			<select name="asset_status">
				<option value="available">Available</option>
				<option value="faulty">Faulty</option>
				<option value="reserved">Reserved</option>
			</select>
		</div>
		<div class="full">
			<label>Notes</label>
			<textarea name="notes" rows="3"></textarea>
		</div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Return asset</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>assets/view/<?php echo (int)$row->asset_id; ?>">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
