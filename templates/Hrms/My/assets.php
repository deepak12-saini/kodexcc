<div class="hrms-panel">
	<h2>My Assets</h2>
	<table class="hrms-table">
		<thead><tr><th>Asset</th><th>Code</th><th>Issued</th><th>Returned</th><th>Status</th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo h($item->hr_asset->name ?? ''); ?></td>
				<td><?php echo h($item->hr_asset->asset_code ?? ''); ?></td>
				<td><?php echo h($item->issue_date); ?></td>
				<td><?php echo h($item->return_date ?: '—'); ?></td>
				<td><?php echo h($item->status); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="5">No assets assigned.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
