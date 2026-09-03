<div class="hrms-panel">
	<h2>My Profile</h2>
	<table class="hrms-table" style="margin-bottom:1.25rem;">
		<tr><th>Employee ID</th><td><?php echo h($employee->employee_code); ?></td></tr>
		<tr><th>Name</th><td><?php echo h($employee->full_name); ?></td></tr>
		<tr><th>Email</th><td><?php echo h($employee->email); ?></td></tr>
		<tr><th>Department</th><td><?php echo h($employee->hr_department->name ?? '—'); ?></td></tr>
		<tr><th>Designation</th><td><?php echo h($employee->hr_designation->name ?? '—'); ?></td></tr>
		<tr><th>Shift</th><td><?php echo h($employee->hr_shift->name ?? '—'); ?></td></tr>
		<tr><th>Manager</th><td><?php echo h($employee->manager->full_name ?? '—'); ?></td></tr>
		<tr><th>Joining Date</th><td><?php echo h($employee->joining_date); ?></td></tr>
	</table>

	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Mobile</label>
			<input type="text" name="mobile" value="<?php echo h($employee->mobile ?? ''); ?>">
		</div>
		<div class="full">
			<label>Address</label>
			<textarea name="address" rows="3"><?php echo h($employee->address ?? ''); ?></textarea>
		</div>
		<div>
			<label>Emergency Contact Name</label>
			<input type="text" name="emergency_contact_name" value="<?php echo h($employee->emergency_contact_name ?? ''); ?>">
		</div>
		<div>
			<label>Emergency Contact Phone</label>
			<input type="text" name="emergency_contact_phone" value="<?php echo h($employee->emergency_contact_phone ?? ''); ?>">
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Update Profile</button>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
