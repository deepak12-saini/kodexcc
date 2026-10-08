<div class="hrms-actions">
	<form method="get" style="display:flex;gap:.5rem;flex:1;margin:0;flex-wrap:wrap;">
		<input type="search" name="q" value="<?php echo h($q ?? ''); ?>" placeholder="Asset or employee">
		<select name="type">
			<option value="">All hardware</option>
			<?php foreach ($assetTypes as $typeKey => $typeLabel): ?>
				<option value="<?php echo h($typeKey); ?>" <?php echo ($type ?? '') === $typeKey ? 'selected' : ''; ?>><?php echo h($typeLabel); ?></option>
			<?php endforeach; ?>
		</select>
		<select name="status">
			<option value="">All</option>
			<?php foreach (['assigned', 'returned', 'transferred'] as $s): ?>
				<option value="<?php echo $s; ?>" <?php echo ($status ?? '') === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
			<?php endforeach; ?>
		</select>
		<button class="hrms-btn hrms-btn-ghost" type="submit">Filter</button>
	</form>
</div>
<div class="hrms-panel hrms-table-scroll">
	<table class="hrms-table">
		<thead><tr><th>Asset</th><th>Hardware</th><th>Brand</th><th>Model</th><th>Serial</th><th>Employee</th><th>Assigned</th><th>Returned</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>assets/view/<?php echo (int)$item->asset_id; ?>"><?php echo h($item->it_asset->asset_code ?? '—'); ?></a></td>
				<td><?php echo h($assetTypes[$item->it_asset->asset_type ?? ''] ?? ($item->it_asset->asset_type ?? '—')); ?></td>
				<td><?php echo h($item->it_asset->brand ?? '—'); ?></td>
				<td><?php echo h($item->it_asset->model ?? '—'); ?></td>
				<td><?php echo h($item->it_asset->serial_number ?? '—'); ?></td>
				<td><?php echo h($item->hr_employee->full_name ?? '—'); ?></td>
				<td><?php echo $this->It->date($item->assigned_date); ?></td>
				<td><?php echo $this->It->date($item->return_date); ?></td>
				<td><?php echo $this->It->badge($item->status); ?></td>
				<td>
					<?php if ($item->status === 'assigned'): ?>
						<a href="<?php echo $itBase; ?>assignments/return-asset/<?php echo (int)$item->id; ?>">Return</a>
						· <a href="<?php echo $itBase; ?>assignments/transfer/<?php echo (int)$item->id; ?>">Transfer</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="10">No assignment records yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
