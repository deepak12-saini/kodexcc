<div class="hrms-actions">
	<?php if (in_array($hrRole, ['admin', 'hr'], true)): ?>
		<a class="hrms-btn hrms-btn-primary" href="<?php echo SITEURL; ?>hrms/employees/add">Register Employee</a>
	<?php endif; ?>
	<form method="get" style="display:flex;gap:.5rem;flex:1;max-width:420px;margin:0;">
		<input type="search" name="q" value="<?php echo h($q ?? ''); ?>" placeholder="Search name, code, email">
		<button class="hrms-btn hrms-btn-ghost" type="submit">Search</button>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>Status</th><th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><code style="font-size:.82rem;color:#5b6b7c;"><?php echo h($item->employee_code); ?></code></td>
				<td><strong style="font-weight:600;"><?php echo h($item->full_name); ?></strong></td>
				<td><?php echo h($item->hr_department->name ?? '—'); ?></td>
				<td><?php echo h($item->hr_designation->name ?? '—'); ?></td>
				<td>
					<?php
					$st = strtolower((string)$item->status);
					$badge = $st === 'active' ? 'badge-ok' : ($st === 'inactive' ? 'badge-muted' : 'badge-warn');
					?>
					<span class="badge <?php echo $badge; ?>"><?php echo h($item->status); ?></span>
				</td>
				<td>
					<a href="<?php echo SITEURL; ?>hrms/employees/view/<?php echo (int)$item->id; ?>">Profile</a>
					<?php if (in_array($hrRole, ['admin', 'hr'], true)): ?>
						· <a href="<?php echo SITEURL; ?>hrms/employees/edit/<?php echo (int)$item->id; ?>">Edit</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
