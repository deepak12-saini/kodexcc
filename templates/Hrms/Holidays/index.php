<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo SITEURL; ?>hrms/holidays/add">Add Holiday</a>
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/holidays/calendar">Calendar</a>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead><tr><th>Date</th><th>Name</th><th>Type</th><th>Optional</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo h(is_object($item->holiday_date) ? $item->holiday_date->format('Y-m-d') : $item->holiday_date); ?></td>
				<td><?php echo h($item->name); ?></td>
				<td><?php echo h($item->type); ?></td>
				<td><?php echo $item->is_optional ? 'Yes' : 'No'; ?></td>
				<td><?php echo $item->status ? 'Active' : 'Inactive'; ?></td>
				<td><a href="<?php echo SITEURL; ?>hrms/holidays/edit/<?php echo (int)$item->id; ?>">Edit</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
