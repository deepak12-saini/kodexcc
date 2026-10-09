<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=1">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1><?php echo h($pageTitle); ?></h1>
				<p class="kx-lead"><?php echo $label === 'Designation' ? 'Name this designation and the department it belongs to.' : 'Name this department and an optional short code.'; ?></p>
				<div class="kx-grid">
					<label class="kx-field"><span>Name<span class="kx-req">*</span></span><input name="name" required value="<?php echo h($entity->name ?? ''); ?>"></label>
					<?php if ($label === 'Designation'): ?>
						<label class="kx-field"><span>Department</span>
							<select name="department_id"><option value="">Select</option>
							<?php foreach ($departments as $id => $name): ?>
								<option value="<?php echo (int)$id; ?>" <?php echo (string)($entity->department_id ?? '') === (string)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
							<?php endforeach; ?>
							</select>
						</label>
					<?php else: ?>
						<label class="kx-field"><span>Code</span><input name="code" value="<?php echo h($entity->code ?? ''); ?>"></label>
					<?php endif; ?>
					<label class="kx-field"><span>Status</span>
						<select name="status">
							<option value="1" <?php echo (string)($entity->status ?? '1') === '1' ? 'selected' : ''; ?>>Active</option>
							<option value="0" <?php echo (string)($entity->status ?? '') === '0' ? 'selected' : ''; ?>>Inactive</option>
						</select>
					</label>
				</div>
				<div class="kx-actions">
					<button class="kx-btn kx-btn-primary" type="submit">Save</button>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . 'admin/people/' . h($listAction); ?>">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
