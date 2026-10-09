<div class="page-content">
	<div class="page-header">
		<h1>Employees <a class="btn btn-primary btn-sm pull-right" href="<?php echo SITEURL; ?>admin/people/employee-form">Add</a></h1>
	</div>
	<form method="get" class="form-inline" style="margin-bottom:12px;">
		<input type="search" name="q" value="<?php echo h($q ?? ''); ?>" class="form-control" placeholder="Name, code, or email">
		<?php if (!empty($removed)): ?><input type="hidden" name="removed" value="1"><?php endif; ?>
		<button class="btn btn-sm" type="submit">Filter</button>
		<?php if (!empty($removed)): ?>
			<a class="btn btn-sm" href="<?php echo SITEURL; ?>admin/people/employees">Active employees</a>
		<?php else: ?>
			<a class="btn btn-sm" href="<?php echo SITEURL; ?>admin/people/employees?removed=1">Removed employees</a>
		<?php endif; ?>
	</form>
	<table class="table table-striped table-bordered table-hover">
		<thead><tr><th style="width:70px;">S.No</th><th>Code</th><th>Name</th><th>Department</th><th>Designation</th><th>ISO role</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php $sno = ((max(1, (int)$this->request->getQuery('page', 1)) - 1) * (int)($this->Paginator->param('perPage') ?: 20)) + 1; ?>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo $sno++; ?></td>
				<td><?php echo h($item->employee_code); ?></td>
				<td><?php echo h($item->full_name); ?></td>
				<td><?php echo h($item->hr_department->name ?? ''); ?></td>
				<td><?php echo h($item->hr_designation->name ?? ''); ?></td>
				<td><?php echo h($isoRoles[$item->hr_user->iso_role ?? ''] ?? ''); ?></td>
				<td><?php echo h($item->status); ?></td>
				<td style="white-space:nowrap;">
					<?php if (empty($removed)): ?>
						<a class="btn btn-mini btn-info" href="<?php echo SITEURL; ?>admin/people/employee-form/<?php echo (int)$item->id; ?>">Edit</a>
						<?php echo $this->Form->create(null, ['url' => ['action' => 'employeeRemove', $item->id], 'style' => 'display:inline;']); ?>
							<button class="btn btn-mini btn-danger" type="submit" onclick="return confirm('Remove this employee? You can recover them from Removed employees.');">Delete</button>
						<?php echo $this->Form->end(); ?>
					<?php else: ?>
						<?php echo $this->Form->create(null, ['url' => ['action' => 'employeeRecover', $item->id], 'style' => 'display:inline;']); ?>
							<button class="btn btn-mini btn-success" type="submit">Recover</button>
						<?php echo $this->Form->end(); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="8"><?php echo !empty($removed) ? 'No removed employees.' : 'No employees yet.'; ?></td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('admin_pagination'); ?>
</div>
