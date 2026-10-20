<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=1">
<div class="page-content">
	<div class="kx-sheet">
		<div class="kx-card">
			<h1><?php echo h($entity->full_name); ?></h1>
			<p class="kx-lead"><?php echo h($entity->employee_code); ?> · <?php echo h($entity->status); ?></p>
			<div class="kx-grid">
				<label class="kx-field"><span>Department</span><input readonly value="<?php echo h($entity->hr_department->name ?? ''); ?>"></label>
				<label class="kx-field"><span>Designation</span><input readonly value="<?php echo h($entity->hr_designation->name ?? ''); ?>"></label>
				<label class="kx-field"><span>Reporting manager</span><input readonly value="<?php echo h($entity->manager->full_name ?? ''); ?>"></label>
				<label class="kx-field"><span>Email</span><input readonly value="<?php echo h($entity->email ?? ''); ?>"></label>
				<label class="kx-field"><span>Mobile</span><input readonly value="<?php echo h($entity->mobile ?? ''); ?>"></label>
				<label class="kx-field"><span>ISO role</span><input readonly value="<?php echo h($isoRoles[$entity->hr_user->iso_role ?? ''] ?? ''); ?>"></label>
				<label class="kx-field"><span>Added</span><input readonly value="<?php echo h($this->Time->format($entity->created, 'dd MMM yyyy hh:mm a')); ?>"></label>
				<label class="kx-field"><span>Updated</span><input readonly value="<?php echo h($this->Time->format($entity->modified, 'dd MMM yyyy hh:mm a')); ?>"></label>
			</div>
			<p class="kx-section">KPI</p>
			<p style="white-space:pre-wrap;margin:0 0 16px;"><?php echo trim((string)($entity->kpi ?? '')) !== '' ? h($entity->kpi) : 'No KPI recorded yet.'; ?></p>
			<div class="kx-actions">
				<?php if (($entity->status ?? '') !== 'deleted'): ?>
					<a class="kx-btn kx-btn-primary" href="<?php echo SITEURL; ?>admin/people/employee-form/<?php echo (int)$entity->id; ?>">Edit</a>
				<?php endif; ?>
				<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/people/employees<?php echo ($entity->status ?? '') === 'deleted' ? '?removed=1' : ''; ?>">Back</a>
			</div>
		</div>
	</div>
</div>
