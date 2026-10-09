<div class="page-content">
	<div class="page-header">
		<h1>Designations <a class="btn btn-primary btn-sm pull-right" href="<?php echo SITEURL; ?>admin/people/designation-form">Add</a></h1>
	</div>
	<table class="table table-striped table-bordered table-hover">
		<thead><tr><th style="width:70px;">S.No</th><th>Name</th><th>Department</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php $sno = ((max(1, (int)$this->request->getQuery('page', 1)) - 1) * (int)($this->Paginator->param('perPage') ?: 20)) + 1; ?>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo $sno++; ?></td>
				<td><?php echo h($item->name); ?></td>
				<td><?php echo h($item->hr_department->name ?? ''); ?></td>
				<td><?php echo $item->status ? 'Active' : 'Inactive'; ?></td>
				<td style="white-space:nowrap;">
					<a class="btn btn-mini btn-info" href="<?php echo SITEURL; ?>admin/people/designation-form/<?php echo (int)$item->id; ?>">Edit</a>
					<?php echo $this->Form->create(null, ['url' => ['action' => 'designationRemove', $item->id], 'style' => 'display:inline;']); ?>
						<button class="btn btn-mini btn-danger" type="submit" onclick="return confirm('Delete this designation? This cannot be undone.');">Delete</button>
					<?php echo $this->Form->end(); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="5">No designations yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('admin_pagination'); ?>
</div>
