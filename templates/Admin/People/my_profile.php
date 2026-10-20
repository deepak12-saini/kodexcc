<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1>My profile</h1>
				<p class="kx-lead">Update your details, choose who you report to, and write your KPI for that role.</p>
				<div class="kx-grid">
					<label class="kx-field"><span>Full name<span class="kx-req">*</span></span><input name="full_name" required value="<?php echo h($entity->full_name ?? ''); ?>"></label>
					<label class="kx-field"><span>Employee code</span><input value="<?php echo h($entity->employee_code ?? ''); ?>" readonly></label>
					<label class="kx-field"><span>Email</span><input name="email" value="<?php echo h($entity->email ?? ''); ?>"></label>
					<label class="kx-field"><span>Mobile</span><input name="mobile" value="<?php echo h($entity->mobile ?? ''); ?>"></label>
					<label class="kx-field"><span>Department</span><input value="<?php echo h($entity->hr_department->name ?? ''); ?>" readonly></label>
					<label class="kx-field"><span>Designation</span><input value="<?php echo h($entity->hr_designation->name ?? ''); ?>" readonly></label>
					<label class="kx-field"><span>Reporting manager</span>
						<select name="manager_id"><option value="">Select</option>
						<?php foreach ($managers as $id => $name): ?>
							<option value="<?php echo (int)$id; ?>" <?php echo (string)($entity->manager_id ?? '') === (string)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
						<?php endforeach; ?>
						</select>
					</label>
					<label class="kx-field kx-span"><span>KPI</span>
						<textarea name="kpi" rows="4" placeholder="Targets you are measured on"><?php echo h($entity->kpi ?? ''); ?></textarea>
					</label>
				</div>
				<div class="kx-actions">
					<button class="kx-btn kx-btn-primary" type="submit">Save</button>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
