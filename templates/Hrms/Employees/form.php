<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit Employee' : 'Employee Registration'; ?></h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2', 'type' => 'file']); ?>
		<div>
			<label>Employee ID / Code</label>
			<input type="text" name="employee_code" value="<?php echo h($entity->employee_code ?? ''); ?>" placeholder="Auto if blank">
		</div>
		<div>
			<label>Full Name*</label>
			<input type="text" name="full_name" value="<?php echo h($entity->full_name ?? ''); ?>" required>
		</div>
		<div>
			<label>Email</label>
			<input type="email" name="email" value="<?php echo h($entity->email ?? ''); ?>">
		</div>
		<div>
			<label>Mobile</label>
			<input type="text" name="mobile" value="<?php echo h($entity->mobile ?? ''); ?>">
		</div>
		<div>
			<label>Department</label>
			<select name="department_id">
				<option value="">—</option>
				<?php foreach ($departments as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->department_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Designation</label>
			<select name="designation_id">
				<option value="">—</option>
				<?php foreach ($designations as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->designation_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Joining Date</label>
			<input type="date" name="joining_date" value="<?php echo h($entity->joining_date ? (is_object($entity->joining_date) ? $entity->joining_date->format('Y-m-d') : $entity->joining_date) : ''); ?>">
		</div>
		<div>
			<label>Date of Birth</label>
			<input type="date" name="date_of_birth" value="<?php echo h($entity->date_of_birth ? (is_object($entity->date_of_birth) ? $entity->date_of_birth->format('Y-m-d') : $entity->date_of_birth) : ''); ?>">
		</div>
		<div>
			<label>Employment Type</label>
			<select name="employment_type">
				<?php foreach (['Full-time', 'Part-time', 'Contract', 'Intern'] as $t): ?>
					<option value="<?php echo $t; ?>" <?php echo ($entity->employment_type ?? 'Full-time') === $t ? 'selected' : ''; ?>><?php echo $t; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Reporting Manager</label>
			<select name="manager_id">
				<option value="">—</option>
				<?php foreach ($managers as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->manager_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Shift</label>
			<select name="shift_id">
				<option value="">—</option>
				<?php foreach ($shifts as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->shift_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Work Location</label>
			<input type="text" name="work_location" value="<?php echo h($entity->work_location ?? ''); ?>">
		</div>
		<div class="full">
			<label>Address</label>
			<textarea name="address" rows="2"><?php echo h($entity->address ?? ''); ?></textarea>
		</div>
		<div>
			<label>Emergency Contact Name</label>
			<input type="text" name="emergency_contact_name" value="<?php echo h($entity->emergency_contact_name ?? ''); ?>">
		</div>
		<div>
			<label>Emergency Contact Phone</label>
			<input type="text" name="emergency_contact_phone" value="<?php echo h($entity->emergency_contact_phone ?? ''); ?>">
		</div>
		<div>
			<label>Bank Name</label>
			<input type="text" name="bank_name" value="<?php echo h($entity->bank_name ?? ''); ?>">
		</div>
		<div>
			<label>Bank Account</label>
			<input type="text" name="bank_account" value="<?php echo h($entity->bank_account ?? ''); ?>">
		</div>
		<div>
			<label>IFSC</label>
			<input type="text" name="bank_ifsc" value="<?php echo h($entity->bank_ifsc ?? ''); ?>">
		</div>
		<div>
			<label>Status</label>
			<select name="status">
				<option value="active" <?php echo ($entity->status ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
				<option value="inactive" <?php echo ($entity->status ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
			</select>
		</div>

		<?php if (empty($entity->id)): ?>
		<div class="full"><h3 style="margin:0;">Login Access</h3></div>
		<div>
			<label>Username</label>
			<input type="text" name="username" placeholder="optional">
		</div>
		<div>
			<label>Temp Password</label>
			<input type="text" name="password" value="welcome123">
		</div>
		<div>
			<label>Role</label>
			<select name="login_role">
				<option value="employee">Employee</option>
				<option value="manager">Manager</option>
				<option value="hr">HR</option>
				<option value="admin">Admin</option>
			</select>
		</div>
		<?php endif; ?>

		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Save Employee</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/employees">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
