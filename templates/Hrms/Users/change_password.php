<div class="hrms-panel">
	<h2>Change Password</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form', 'style' => 'max-width:420px;']); ?>
		<div>
			<label>Current Password</label>
			<input type="password" name="current_password" required>
		</div>
		<div>
			<label>New Password</label>
			<input type="password" name="new_password" required>
		</div>
		<div>
			<label>Confirm Password</label>
			<input type="password" name="confirm_password" required>
		</div>
		<button type="submit" class="hrms-btn hrms-btn-primary">Update Password</button>
	<?php echo $this->Form->end(); ?>
</div>
