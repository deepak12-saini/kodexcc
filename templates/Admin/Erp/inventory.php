<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet kx-overview">
		<div class="kx-card">
			<div class="kx-overview-head">
				<div>
					<h1>Inventory</h1>
					<p class="kx-lead">Stock increases when QA marks an ISO incoming receipt as Pass. <?php echo (int)$low; ?> item(s) are below reorder level.</p>
				</div>
				<span class="kx-role"><?php echo h($erpRoleLabel); ?></span>
			</div>
			<div class="kx-actions">
				<a class="kx-btn kx-btn-primary" href="<?php echo SITEURL; ?>admin/erp/items">Items</a>
				<?php if (!empty($canEdit)): ?>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/transfer">Transfer</a>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/adjustment">Adjustment</a>
				<?php endif; ?>
			</div>
			<div class="kx-table-wrap">
				<table class="kx-table">
					<thead><tr><th>Item</th><th>Godown</th><th>Lot</th><th>Qty</th></tr></thead>
					<tbody>
						<?php foreach ($rows as $row): ?>
							<tr>
								<td><?php echo h($row['item']); ?></td>
								<td><?php echo h($row['godown']); ?></td>
								<td><?php echo h($row['lot']); ?></td>
								<td><?php echo h((string)$row['qty']); ?></td>
							</tr>
						<?php endforeach; ?>
						<?php if ($rows === []): ?><tr><td colspan="4">No stock yet.</td></tr><?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
