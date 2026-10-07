<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>purchases/add">New request</a>
	<form method="get" style="display:flex;gap:.5rem;margin:0;">
		<select name="status">
			<option value="">All stages</option>
			<?php foreach ($purchaseStatuses as $s): ?>
				<option value="<?php echo h($s); ?>" <?php echo ($status ?? '') === $s ? 'selected' : ''; ?>><?php echo h($this->It->label($s)); ?></option>
			<?php endforeach; ?>
		</select>
		<button class="hrms-btn hrms-btn-ghost" type="submit">Filter</button>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead><tr><th>Request</th><th>Item</th><th>Qty</th><th>Requester</th><th>Stage</th><th>Estimated</th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>purchases/view/<?php echo (int)$item->id; ?>"><?php echo h($item->request_no); ?></a></td>
				<td><?php echo h($item->item_name); ?></td>
				<td><?php echo (int)$item->quantity; ?></td>
				<td><?php echo h($item->requester->full_name ?? '—'); ?></td>
				<td><?php echo $this->It->badge($item->approval_status); ?></td>
				<td><?php echo $this->It->money($item->estimated_cost); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="6">No purchase requests yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
