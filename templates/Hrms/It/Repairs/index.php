<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>repairs/add">New repair</a>
	<form method="get" style="display:flex;gap:.5rem;margin:0;">
		<select name="status">
			<option value="">All statuses</option>
			<?php foreach ($repairStatuses as $s): ?>
				<option value="<?php echo h($s); ?>" <?php echo ($status ?? '') === $s ? 'selected' : ''; ?>><?php echo h($this->It->label($s)); ?></option>
			<?php endforeach; ?>
		</select>
		<button class="hrms-btn hrms-btn-ghost" type="submit">Filter</button>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead><tr><th>Repair</th><th>Asset</th><th>Vendor</th><th>Status</th><th>Sent</th><th>Actual cost</th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>repairs/view/<?php echo (int)$item->id; ?>"><?php echo h($item->repair_no); ?></a></td>
				<td><?php echo h($item->it_asset->asset_code ?? '—'); ?></td>
				<td><?php echo h($item->it_vendor->name ?? '—'); ?></td>
				<td><?php echo $this->It->badge($item->status); ?></td>
				<td><?php echo $this->It->date($item->sent_date); ?></td>
				<td><?php echo $this->It->money($item->actual_cost); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="6">No repairs yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
