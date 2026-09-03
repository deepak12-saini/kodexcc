<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo SITEURL; ?>hrms/shifts/add">Add Shift</a>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead><tr><th>Name</th><th>Start</th><th>End</th><th>Grace (min)</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo h($item->name); ?></td>
				<td><?php echo h(is_object($item->start_time) ? $item->start_time->format('H:i') : $item->start_time); ?></td>
				<td><?php echo h(is_object($item->end_time) ? $item->end_time->format('H:i') : $item->end_time); ?></td>
				<td><?php echo (int)$item->grace_minutes; ?></td>
				<td><a href="<?php echo SITEURL; ?>hrms/shifts/edit/<?php echo (int)$item->id; ?>">Edit</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
