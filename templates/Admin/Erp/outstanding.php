<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet"><div class="kx-card">
		<h1>Customer outstanding</h1>
		<table class="kx-table">
			<thead><tr><th>Invoice</th><th>Customer</th><th>Date</th><th>Due</th></tr></thead>
			<tbody>
				<?php foreach ($rows as $row): ?>
					<tr>
						<td><?php echo h($row['invoice']); ?></td>
						<td><?php echo h($row['customer']); ?></td>
						<td><?php echo h($row['date']); ?></td>
						<td><?php echo h(number_format($row['due'], 2)); ?></td>
					</tr>
				<?php endforeach; ?>
				<?php if ($rows === []): ?><tr><td colspan="4">Nothing outstanding.</td></tr><?php endif; ?>
			</tbody>
		</table>
		<div class="kx-actions"><a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/sales">Back</a></div>
	</div></div>
</div>
