<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo SITEURL; ?>hrms/designations/add">Add Designation</a>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead><tr><th>Name</th><th>Department</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo h($item->name); ?></td>
				<td><?php echo h($item->hr_department->name ?? '—'); ?></td>
				<td><?php echo $item->status ? 'Active' : 'Inactive'; ?></td>
				<td><a href="<?php echo SITEURL; ?>hrms/designations/edit/<?php echo (int)$item->id; ?>">Edit</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
