<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>vendors">All vendors</a>
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>vendors/edit/<?php echo (int)$vendor->id; ?>">Edit</a>
</div>
<div class="hrms-panel">
	<h2><?php echo h($vendor->name); ?></h2>
	<table class="hrms-table">
		<tbody>
			<tr><th>Contact</th><td><?php echo h($vendor->contact_person ?: '—'); ?></td><th>Phone</th><td><?php echo h($vendor->phone ?: '—'); ?></td></tr>
			<tr><th>Email</th><td><?php echo h($vendor->email ?: '—'); ?></td><th>GST</th><td><?php echo h($vendor->gst_number ?: '—'); ?></td></tr>
			<tr><th>Address</th><td colspan="3"><?php echo nl2br(h($vendor->address ?: '—')); ?></td></tr>
			<tr><th>Warranty</th><td colspan="3"><?php echo nl2br(h($vendor->warranty_notes ?: '—')); ?></td></tr>
		</tbody>
	</table>
</div>
<div class="hrms-panel">
	<h2>Assets purchased</h2>
	<table class="hrms-table"><thead><tr><th>Code</th><th>Brand / model</th><th>Warranty until</th><th>Status</th></tr></thead><tbody>
		<?php foreach ($vendor->it_assets ?? [] as $row): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>assets/view/<?php echo (int)$row->id; ?>"><?php echo h($row->asset_code); ?></a></td>
				<td><?php echo h(trim(($row->brand ?? '') . ' ' . ($row->model ?? ''))); ?></td>
				<td><?php echo $this->It->date($row->warranty_until); ?></td>
				<td><?php echo $this->It->badge($row->status); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($vendor->it_assets ?? [])): ?><tr><td colspan="4">None.</td></tr><?php endif; ?>
	</tbody></table>
</div>
<div class="hrms-panel">
	<h2>Repairs</h2>
	<table class="hrms-table"><thead><tr><th>Repair</th><th>Asset</th><th>Status</th><th>Cost</th></tr></thead><tbody>
		<?php foreach ($vendor->it_repairs ?? [] as $row): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>repairs/view/<?php echo (int)$row->id; ?>"><?php echo h($row->repair_no); ?></a></td>
				<td><?php echo h($row->it_asset->asset_code ?? '—'); ?></td>
				<td><?php echo $this->It->badge($row->status); ?></td>
				<td><?php echo $this->It->money($row->actual_cost); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($vendor->it_repairs ?? [])): ?><tr><td colspan="4">None.</td></tr><?php endif; ?>
	</tbody></table>
</div>
<div class="hrms-panel">
	<h2>Quotations and purchases</h2>
	<table class="hrms-table"><thead><tr><th>Request</th><th>Item</th><th>Status</th><th>Quotation</th></tr></thead><tbody>
		<?php foreach ($vendor->it_purchase_requests ?? [] as $row): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>purchases/view/<?php echo (int)$row->id; ?>"><?php echo h($row->request_no); ?></a></td>
				<td><?php echo h($row->item_name); ?></td>
				<td><?php echo $this->It->badge($row->approval_status); ?></td>
				<td><?php echo $row->quotation_file ? '<a href="' . SITEURL . h($row->quotation_file) . '" target="_blank">File</a>' : h($row->quotation_notes ?: '—'); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($vendor->it_purchase_requests ?? [])): ?><tr><td colspan="4">None.</td></tr><?php endif; ?>
	</tbody></table>
</div>
