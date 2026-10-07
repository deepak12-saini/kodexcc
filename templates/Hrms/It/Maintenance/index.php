<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>maintenance/add">Schedule maintenance</a>
	<form method="get" style="display:flex;gap:.5rem;margin:0;">
		<select name="status">
			<option value="">All</option>
			<?php foreach ($maintenanceStatuses as $s): ?>
				<option value="<?php echo h($s); ?>" <?php echo ($status ?? '') === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
			<?php endforeach; ?>
		</select>
		<button class="hrms-btn hrms-btn-ghost" type="submit">Filter</button>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead><tr><th>Asset</th><th>Type</th><th>Scheduled</th><th>Completed</th><th>Cost</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>assets/view/<?php echo (int)$item->asset_id; ?>"><?php echo h($item->it_asset->asset_code ?? '—'); ?></a></td>
				<td><?php echo h($item->maintenance_type); ?></td>
				<td><?php echo $this->It->date($item->scheduled_date); ?></td>
				<td><?php echo $this->It->date($item->completed_date); ?></td>
				<td><?php echo $this->It->money($item->cost); ?></td>
				<td><?php echo $this->It->badge($item->status); ?></td>
				<td><a href="<?php echo $itBase; ?>maintenance/edit/<?php echo (int)$item->id; ?>">Edit</a></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="7">No maintenance records yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
