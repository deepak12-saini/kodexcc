<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet kx-overview">
		<div class="kx-card">
			<h1>Production</h1>
			<p class="kx-lead">A production order issues raw material and receives finished goods. Completing it creates the ISO production batch.</p>
			<div class="kx-actions">
				<?php if (!empty($canEdit)): ?>
					<a class="kx-btn kx-btn-primary" href="<?php echo SITEURL; ?>admin/erp/bom-form">Bill of materials</a>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/production-form">Production order</a>
				<?php endif; ?>
			</div>
			<h2 class="kx-section">Orders</h2>
			<table class="kx-table">
				<thead><tr><th>Order</th><th>Status</th><th>Planned</th><th>Output</th></tr></thead>
				<tbody>
					<?php foreach ($orders as $order): ?>
						<tr>
							<td><a href="<?php echo SITEURL; ?>admin/erp/production-form/<?php echo (int)$order->id; ?>"><?php echo h($order->prod_no); ?></a></td>
							<td><?php echo h($order->status); ?></td>
							<td><?php echo h((string)$order->planned_qty); ?></td>
							<td><?php echo h((string)$order->output_qty); ?></td>
						</tr>
					<?php endforeach; ?>
					<?php if (!count($orders)): ?><tr><td colspan="4">No production orders yet.</td></tr><?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
