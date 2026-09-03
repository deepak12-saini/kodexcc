<div class="hrms-panel">
	<h2>Upload Document</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2', 'type' => 'file']); ?>
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
			<label>Document Type</label>
			<select name="doc_type" required>
				<?php foreach (['ID Proof', 'Address Proof', 'Offer Letter', 'Contract', 'Certificate', 'Other'] as $t): ?>
					<option value="<?php echo h($t); ?>"><?php echo h($t); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full">
			<label>Title</label>
			<input type="text" name="title" required>
		</div>
		<div class="full">
			<label>File</label>
			<input type="file" name="file" required>
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Upload</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/documents">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
