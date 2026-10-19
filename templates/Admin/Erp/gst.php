<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet"><div class="kx-card">
		<h1>GST summary</h1>
		<p class="kx-lead">Outward tax <?php echo h(number_format($output['gst'], 2)); ?> on taxable <?php echo h(number_format($output['taxable'], 2)); ?>. Inward tax <?php echo h(number_format($input['gst'], 2)); ?>. Net payable <?php echo h(number_format($output['gst'] - $input['gst'], 2)); ?>.</p>
		<form method="get" class="form-inline" style="margin-bottom:12px;">
			<input type="date" name="from" value="<?php echo h($from); ?>" class="form-control">
			<input type="date" name="to" value="<?php echo h($to); ?>" class="form-control">
			<button class="btn btn-sm" type="submit">Filter</button>
		</form>
		<h2 class="kx-section">Outward supplies</h2>
		<table class="kx-table">
			<thead><tr><th>Invoice</th><th>Date</th><th>Customer</th><th>GSTIN</th><th>Taxable</th><th>GST</th></tr></thead>
			<tbody>
				<?php foreach ($outward as $row): ?>
					<tr>
						<td><?php echo h((string)$row['doc_no']); ?></td>
						<td><?php echo h((string)$row['doc_date']); ?></td>
						<td><?php echo h((string)$row['partner_name']); ?></td>
						<td><?php echo h((string)$row['gstin']); ?></td>
						<td><?php echo h(number_format((float)$row['taxable'], 2)); ?></td>
						<td><?php echo h(number_format((float)$row['gst'], 2)); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="kx-actions"><a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/financials">Back</a></div>
	</div></div>
</div>
