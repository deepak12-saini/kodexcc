<div class="page-content">
	<div class="page-header">
		<h1>Employees <a class="btn btn-primary btn-sm pull-right" href="<?php echo SITEURL; ?>admin/people/employee-form">Add</a></h1>
	</div>
	<form method="get" class="form-inline" style="margin-bottom:12px;">
		<input type="search" name="q" value="<?php echo h($q ?? ''); ?>" class="form-control" placeholder="Name, code, or email">
		<button class="btn btn-sm" type="submit">Filter</button>
	</form>
	<table class="table table-striped table-bordered table-hover">
		<thead><tr><th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>ISO role</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo h($item->employee_code); ?></td>
				<td><?php echo h($item->full_name); ?></td>
				<td><?php echo h($item->hr_department->name ?? ''); ?></td>
				<td><?php echo h($item->hr_designation->name ?? ''); ?></td>
				<td><?php echo h($isoRoles[$item->hr_user->iso_role ?? ''] ?? ''); ?></td>
				<td><?php echo h($item->status); ?></td>
				<td><a class="btn btn-mini btn-info" href="<?php echo SITEURL; ?>admin/people/employee-form/<?php echo (int)$item->id; ?>">Edit</a></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="7">No employees yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('admin_pagination'); ?>
</div>
