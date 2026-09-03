<div class="hrms-panel">
	<h2>Mark Attendance</h2>
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
			<label>Date</label>
			<input type="date" name="attendance_date" value="<?php echo date('Y-m-d'); ?>" required>
		</div>
		<div>
			<label>Status</label>
			<select name="status" required>
				<option value="present">Present</option>
				<option value="absent">Absent</option>
				<option value="half_day">Half Day</option>
				<option value="late">Late</option>
				<option value="on_leave">On Leave</option>
			</select>
		</div>
		<div class="full">
			<label>Notes</label>
			<textarea name="notes" rows="3"></textarea>
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Save</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/attendances">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
