<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=1">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1><?php echo h($pageTitle); ?></h1>
				<p class="kx-lead">Employee details used on people lists and ISO records.</p>
				<div class="kx-grid">
					<label class="kx-field"><span>Full name<span class="kx-req">*</span></span><input name="full_name" required value="<?php echo h($entity->full_name ?? ''); ?>"></label>
					<label class="kx-field"><span>Employee code</span><input name="employee_code" value="<?php echo h($entity->employee_code ?? ''); ?>" placeholder="Leave blank to generate"></label>
					<label class="kx-field"><span>Email</span><input name="email" value="<?php echo h($entity->email ?? ''); ?>"></label>
					<label class="kx-field"><span>Mobile</span><input name="mobile" value="<?php echo h($entity->mobile ?? ''); ?>"></label>
					<label class="kx-field"><span>Department</span>
						<select name="department_id"><option value="">Select</option>
						<?php foreach ($departments as $id => $name): ?>
							<option value="<?php echo (int)$id; ?>" <?php echo (string)($entity->department_id ?? '') === (string)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
						<?php endforeach; ?>
						</select>
					</label>
					<label class="kx-field"><span>Designation</span>
						<select name="designation_id"><option value="">Select</option>
						<?php foreach ($designations as $id => $name): ?>
							<option value="<?php echo (int)$id; ?>" <?php echo (string)($entity->designation_id ?? '') === (string)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
						<?php endforeach; ?>
						</select>
					</label>
					<label class="kx-field"><span>Status</span>
						<select name="status">
							<?php foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $key => $label): ?>
								<option value="<?php echo h($key); ?>" <?php echo ($entity->status ?? 'active') === $key ? 'selected' : ''; ?>><?php echo h($label); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>
			</div>
			<div class="kx-card">
				<p class="kx-section">Login and ISO access</p>
				<div class="kx-grid">
					<label class="kx-field"><span>Username</span><input name="username" value="<?php echo h($entity->hr_user->username ?? ''); ?>"></label>
					<label class="kx-field"><span>Password</span><input name="password" type="text" placeholder="<?php echo empty($entity->hr_user) ? 'Set a password to create the login' : 'Leave blank to keep the current password'; ?>"></label>
					<label class="kx-field kx-span"><span>ISO role</span>
						<select name="iso_role">
							<option value="">No ISO access</option>
							<?php foreach ($isoRoles as $key => $label): ?>
								<?php if ($key === 'super_admin') { continue; } ?>
								<option value="<?php echo h($key); ?>" <?php echo ($entity->hr_user->iso_role ?? '') === $key ? 'selected' : ''; ?>><?php echo h($label); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="kx-hint">Super Admin is the existing admin login. This role controls which ISO screens the employee can open.</p>
					</label>
				</div>
				<div class="kx-actions">
					<button class="kx-btn kx-btn-primary" type="submit">Save</button>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/people/employees">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
