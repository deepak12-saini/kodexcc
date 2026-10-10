<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>assets/add<?php echo $type ? '?type=' . urlencode($type) : ''; ?>">Add asset</a>
	<form method="get" style="display:flex;gap:.5rem;flex:1;margin:0;flex-wrap:wrap;">
		<?php if ($type): ?><input type="hidden" name="type" value="<?php echo h($type); ?>"><?php endif; ?>
		<input type="search" name="q" value="<?php echo h($q ?? ''); ?>" placeholder="Code, brand, model, serial">
		<select name="status">
			<option value="">All statuses</option>
			<?php foreach ($assetStatuses as $s): ?>
				<option value="<?php echo h($s); ?>" <?php echo ($status ?? '') === $s ? 'selected' : ''; ?>><?php echo h($this->It->label($s)); ?></option>
			<?php endforeach; ?>
		</select>
		<button class="hrms-btn hrms-btn-ghost" type="submit">Filter</button>
	</form>
</div>
<div class="hrms-tabs">
	<a class="<?php echo $type === '' ? 'active' : ''; ?>" href="<?php echo $itBase; ?>assets">All</a>
	<?php foreach ($assetTypes as $key => $label): ?>
		<a class="<?php echo $type === $key ? 'active' : ''; ?>" href="<?php echo $itBase; ?>assets?type=<?php echo h($key); ?>"><?php echo h($label); ?></a>
	<?php endforeach; ?>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Asset ID</th><th>Type</th><th>Brand / model</th><th>Serial</th><th>Status</th><th>Holder</th><th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<?php
			$holder = null;
			foreach ($item->it_asset_assignments ?? [] as $asg) {
				if ($asg->status === 'assigned') {
					$holder = $asg;
					break;
				}
			}
			?>
			<tr>
				<td><a href="<?php echo $itBase; ?>assets/view/<?php echo (int)$item->id; ?>"><?php echo h($item->asset_code); ?></a></td>
				<td><?php echo h($assetTypes[$item->asset_type] ?? $item->asset_type); ?></td>
				<td><?php echo h(trim(($item->brand ?? '') . ' ' . ($item->model ?? ''))) ?: '—'; ?></td>
				<td><?php echo h($item->serial_number ?: '—'); ?></td>
				<td><?php echo $this->It->badge($item->status); ?></td>
				<td><?php echo h($holder->hr_employee->full_name ?? '—'); ?></td>
				<td>
					<a href="<?php echo $itBase; ?>assets/edit/<?php echo (int)$item->id; ?>">Edit</a>
					· <?php echo $this->element('hrms_delete', ['id' => (int)$item->id, 'confirm' => 'Delete ' . $item->asset_code . '? Its assignments, tickets, and repairs will be removed too.']); ?>
					<?php if ($item->status === 'available' || $item->status === 'reserved'): ?>
						· <a href="<?php echo $itBase; ?>assignments/assign/<?php echo (int)$item->id; ?>">Assign</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="7">No assets yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
