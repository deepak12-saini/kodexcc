<div class="page-content">
	<div class="page-header">
		<h1>Departments <a class="btn btn-primary btn-sm pull-right" href="<?php echo SITEURL; ?>admin/people/department-form">Add</a></h1>
	</div>
	<table class="table table-striped table-bordered table-hover">
		<thead><tr><th>Name</th><th>Code</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo h($item->name); ?></td>
				<td><?php echo h($item->code); ?></td>
				<td><?php echo $item->status ? 'Active' : 'Inactive'; ?></td>
				<td><a class="btn btn-mini btn-info" href="<?php echo SITEURL; ?>admin/people/department-form/<?php echo (int)$item->id; ?>">Edit</a></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="4">No departments yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('admin_pagination'); ?>
</div>
