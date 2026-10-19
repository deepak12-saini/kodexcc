<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet kx-overview">
		<div class="kx-card">
			<h1>Sales</h1>
			<p class="kx-lead"><?php echo (int)$orders; ?> order(s) waiting for delivery. <?php echo (int)$unpaid; ?> invoice(s) still have a balance.</p>
			<div class="kx-modules">
				<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/sales-quotations"><span><strong>Quotations</strong><small>Customer prices</small></span></a>
				<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/sales-orders"><span><strong>Sales orders</strong><small>Approved before delivery</small></span></a>
				<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/deliveries"><span><strong>Deliveries</strong><small>Reduces finished stock</small></span></a>
				<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/sales-invoices"><span><strong>Sales invoices</strong><small>GST invoice from a delivery</small></span></a>
				<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/outstanding"><span><strong>Outstanding</strong><small>What customers still owe</small></span></a>
			</div>
		</div>
	</div>
</div>
