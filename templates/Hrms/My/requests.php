<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo SITEURL; ?>hrms/my/add-request">Submit Request</a>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Ref</th>
				<th>Type</th>
				<th>Title</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row): ?>
			<tr>
				<td><code><?php echo h($row['ref']); ?></code></td>
				<td><?php echo h($row['type']); ?></td>
				<td><?php echo h($row['title']); ?></td>
				<td><span class="badge badge-muted"><?php echo h($row['status']); ?></span></td>
				<td><a href="<?php echo h($row['url']); ?>">Open</a></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($rows)): ?>
			<tr><td colspan="5">No requests yet.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
</div>
