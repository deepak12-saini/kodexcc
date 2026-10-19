<?php
$basePath = $basePath ?? 'admin/erp/';
$saved = !empty($entity->id);
?>
<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<div class="kx-card">
			<?php if ($saved): ?>
				<h1><?php echo h($entity->grn_no); ?></h1>
				<p class="kx-lead"><?php echo h($orderNo); ?> &middot; <?php echo h($supplierName); ?> &middot; <?php echo h((string)$entity->received_on); ?> &middot; <?php echo h((string)$entity->received_by); ?></p>
				<div class="kx-table-wrap">
					<table class="kx-table">
						<thead><tr><th>Item</th><th>Lot</th><th>Qty</th><th>ISO incoming</th></tr></thead>
						<tbody>
							<?php foreach ($lines as $line): ?>
								<tr>
									<td><?php echo h($line->item_name); ?></td>
									<td><?php echo h((string)$line->lot_no); ?></td>
									<td><?php echo h((string)$line->quantity); ?></td>
									<td>
										<?php if (!empty($line->iso_receipt_id)): ?>
											<a href="<?php echo SITEURL; ?>admin/iso/receipt-form/<?php echo (int)$line->iso_receipt_id; ?>"><?php echo h($isoNos[(int)$line->id] ?? 'Open'); ?></a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="kx-actions">
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath; ?>goods-receipts">Back</a>
					<?php if (empty($billExists) && !empty($canEdit)): ?>
						<a class="kx-btn kx-btn-primary" href="<?php echo SITEURL . $basePath; ?>bill-form?receipt=<?php echo (int)$entity->id; ?>">Enter supplier bill</a>
					<?php elseif (!empty($billExists)): ?>
						<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath; ?>bills">Supplier bill entered</a>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<h1>Goods receipt</h1>
				<p class="kx-lead">Receive against an approved purchase order. Each line is sent to ISO incoming material for QA. Stock is not increased here.</p>
				<?php if (!$order): ?>
					<form method="get" class="kx-form">
						<label class="kx-field">
							<span>Purchase order<span class="kx-req">*</span></span>
							<select name="order">
								<option value="">Select an approved order</option>
								<?php foreach ($openOrders as $ok => $ol): ?>
									<option value="<?php echo h((string)$ok); ?>"><?php echo h($ol); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
						<div class="kx-actions">
							<button class="kx-btn kx-btn-primary" type="submit">Continue</button>
							<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath; ?>goods-receipts">Back</a>
						</div>
					</form>
				<?php elseif ($pending === []): ?>
					<p class="kx-lead">Everything on this order has already been received.</p>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath; ?>goods-receipts">Back</a>
				<?php else: ?>
					<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
						<input type="hidden" name="order_id" value="<?php echo (int)$order->id; ?>">
						<div class="kx-grid">
							<label class="kx-field"><span>Order</span><input type="text" value="<?php echo h($order->po_no); ?>" readonly></label>
							<label class="kx-field"><span>Received on<span class="kx-req">*</span></span><input type="date" name="received_on" value="<?php echo date('Y-m-d'); ?>"></label>
							<label class="kx-field">
								<span>Received by<span class="kx-req">*</span></span>
								<select name="received_by">
									<option value="">Select</option>
									<?php foreach ($isoPeople as $person): ?>
										<option value="<?php echo h($person); ?>"><?php echo h($person); ?></option>
									<?php endforeach; ?>
								</select>
							</label>
							<label class="kx-field kx-span"><span>Notes</span><textarea name="notes" rows="2" placeholder="Vehicle number or supplier delivery note"></textarea></label>
						</div>
						<div class="kx-table-wrap" style="margin-top:16px;">
							<table class="kx-table">
								<thead><tr><th>Item</th><th>Ordered</th><th>Still open</th><th>Received now</th><th>Lot</th></tr></thead>
								<tbody>
									<?php foreach ($pending as $line): ?>
										<tr>
											<td><?php echo h($line->item_name); ?></td>
											<td><?php echo h((string)$line->quantity); ?> <?php echo h($unitNames[(int)$line->unit_id] ?? ''); ?></td>
											<td><?php echo h((string)$line->remain); ?></td>
											<td><input type="number" step="0.001" min="0" name="lines[<?php echo (int)$line->id; ?>][quantity]" placeholder="0"></td>
											<td><input type="text" name="lines[<?php echo (int)$line->id; ?>][lot_no]" placeholder="Supplier lot"></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<div class="kx-actions">
							<button class="kx-btn kx-btn-primary" type="submit">Save receipt</button>
							<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath; ?>goods-receipts">Back</a>
						</div>
					<?php echo $this->Form->end(); ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
