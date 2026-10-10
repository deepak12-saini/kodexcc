<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>assets">All assets</a>
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>assets/edit/<?php echo (int)$asset->id; ?>">Edit</a>
	<?php echo $this->element('hrms_delete', ['id' => (int)$asset->id, 'confirm' => 'Delete ' . $asset->asset_code . '? Its assignments, tickets, and repairs will be removed too.']); ?>
	<?php if (in_array($asset->status, ['available', 'reserved', 'faulty'], true)): ?>
		<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>assignments/assign/<?php echo (int)$asset->id; ?>">Assign</a>
	<?php endif; ?>
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>repairs/add?asset_id=<?php echo (int)$asset->id; ?>">Send for repair</a>
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>maintenance/add?asset_id=<?php echo (int)$asset->id; ?>">Maintenance</a>
</div>
<div class="hrms-cards">
	<div class="hrms-card"><div class="label">Status</div><div class="value" style="font-size:1.1rem;"><?php echo $this->It->badge($asset->status); ?></div></div>
	<div class="hrms-card"><div class="label">Type</div><div class="value" style="font-size:1.1rem;"><?php echo h(\App\Controller\Hrms\It\ItController::ASSET_TYPES[$asset->asset_type] ?? $asset->asset_type); ?></div></div>
	<div class="hrms-card"><div class="label">Purchase cost</div><div class="value" style="font-size:1.1rem;"><?php echo $this->It->money($asset->purchase_cost); ?></div></div>
	<div class="hrms-card"><div class="label">Warranty until</div><div class="value" style="font-size:1.1rem;"><?php echo $this->It->date($asset->warranty_until); ?></div></div>
</div>
<div class="hrms-panel">
	<h2><?php echo h(trim(($asset->brand ?? '') . ' ' . ($asset->model ?? '')) ?: $asset->asset_code); ?></h2>
	<table class="hrms-table">
		<tbody>
			<tr><th>Asset ID</th><td><?php echo h($asset->asset_code); ?></td><th>Serial</th><td><?php echo h($asset->serial_number ?: '—'); ?></td></tr>
			<tr><th>Processor</th><td><?php echo h($asset->processor ?: '—'); ?></td><th>RAM</th><td><?php echo h($asset->ram ?: '—'); ?></td></tr>
			<tr><th>Storage</th><td><?php echo h($asset->storage ?: '—'); ?></td><th>Location</th><td><?php echo h($asset->location ?: '—'); ?></td></tr>
			<tr><th>Vendor</th><td><?php echo h($asset->it_vendor->name ?? '—'); ?></td><th>Purchased</th><td><?php echo $this->It->date($asset->purchase_date); ?></td></tr>
			<tr><th>Warranty</th><td colspan="3"><?php echo h($asset->warranty_notes ?: '—'); ?></td></tr>
			<tr><th>Notes</th><td colspan="3"><?php echo nl2br(h($asset->notes ?: '—')); ?></td></tr>
		</tbody>
	</table>
</div>
<div class="hrms-panel">
	<h2>Assignment history</h2>
	<table class="hrms-table">
		<thead><tr><th>Employee</th><th>Assigned</th><th>Returned</th><th>Out</th><th>In</th><th>Status</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($assignments as $row): ?>
			<tr>
				<td><?php echo h($row->hr_employee->full_name ?? '—'); ?></td>
				<td><?php echo $this->It->date($row->assigned_date); ?></td>
				<td><?php echo $this->It->date($row->return_date); ?></td>
				<td><?php echo h($row->condition_on_assign ?: '—'); ?></td>
				<td><?php echo h($row->condition_on_return ?: '—'); ?></td>
				<td><?php echo $this->It->badge($row->status); ?></td>
				<td>
					<?php if ($row->status === 'assigned'): ?>
						<a href="<?php echo $itBase; ?>assignments/return-asset/<?php echo (int)$row->id; ?>">Return</a>
						· <a href="<?php echo $itBase; ?>assignments/transfer/<?php echo (int)$row->id; ?>">Transfer</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!$assignments): ?><tr><td colspan="7">No assignments yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
</div>
<div class="hrms-panel">
	<h2>Asset history</h2>
	<table class="hrms-table">
		<thead><tr><th>When</th><th>Event</th><th>Summary</th></tr></thead>
		<tbody>
		<?php foreach ($events as $event): ?>
			<tr>
				<td><?php echo $this->It->date($event->created, true); ?></td>
				<td><?php echo h($this->It->label($event->event_type)); ?></td>
				<td><?php echo h($event->summary); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!$events): ?><tr><td colspan="3">No history yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
</div>
