<div class="hrms-panel">
	<h2>New IT ticket</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Employee</label>
			<select name="employee_id">
				<option value="">—</option>
				<?php foreach ($employees as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>"><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Asset</label>
			<select name="asset_id">
				<option value="">—</option>
				<?php foreach ($assets as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->asset_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Category</label>
			<select name="category">
				<?php foreach ($categories as $key => $label): ?>
					<option value="<?php echo h($key); ?>"><?php echo h($label); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Priority</label>
			<select name="priority">
				<?php foreach ($ticketPriorities as $s): ?>
					<option value="<?php echo h($s); ?>" <?php echo $s === 'medium' ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Assigned IT person</label>
			<select name="assigned_user_id">
				<option value="">Unassigned</option>
				<?php foreach ($itUsers as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>"><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full">
			<label>Problem</label>
			<textarea name="problem" rows="4" required></textarea>
		</div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Open ticket</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>tickets">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
