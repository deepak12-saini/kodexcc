<div class="hrms-panel">
	<h2>My Assets</h2>
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Code</th>
				<th>Hardware</th>
				<th>Brand</th>
				<th>Model</th>
				<th>Serial</th>
				<th>Assigned</th>
				<th>Returned</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<?php
			$asset = $item->it_asset;
			$assigned = $item->assigned_date;
			$returned = $item->return_date;
			$date = function ($value): string {
				if ($value === null || $value === '') {
					return '—';
				}
				if (is_object($value) && method_exists($value, 'format')) {
					return $value->format('Y-m-d');
				}

				return (string)$value;
			};
			?>
			<tr>
				<td><?php echo h($asset->asset_code ?? '—'); ?></td>
				<td><?php echo h($assetTypes[$asset->asset_type ?? ''] ?? ($asset->asset_type ?? '—')); ?></td>
				<td><?php echo h($asset->brand ?? '—'); ?></td>
				<td><?php echo h($asset->model ?? '—'); ?></td>
				<td><?php echo h($asset->serial_number ?? '—'); ?></td>
				<td><?php echo h($date($assigned)); ?></td>
				<td><?php echo h($date($returned)); ?></td>
				<td><?php echo h(ucfirst((string)$item->status)); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="8">No assets assigned.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
