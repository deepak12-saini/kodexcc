<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet kx-overview"><div class="kx-card">
		<h1>Banking</h1>
		<p class="kx-lead"><?php echo (int)$unreconciled; ?> payment(s) are not reconciled.</p>
		<div class="kx-actions">
			<a class="kx-btn kx-btn-primary" href="<?php echo SITEURL; ?>admin/erp/payments">Payments</a>
			<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/reconcile">Reconcile</a>
		</div>
		<table class="kx-table">
			<thead><tr><th>Bank</th><th>Account no</th><th>Opening</th></tr></thead>
			<tbody>
				<?php foreach ($banks as $bank): ?>
					<tr>
						<td><?php echo h($bank->name); ?></td>
						<td><?php echo h((string)$bank->account_no); ?></td>
						<td><?php echo h(number_format((float)$bank->opening_balance, 2)); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div></div>
</div>
