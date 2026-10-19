<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<div class="kx-card">
			<?php if (!empty($entity->id)): ?>
				<h1><?php echo h($entity->delivery_no); ?></h1>
				<p class="kx-lead"><?php echo h((string)$entity->delivered_on); ?></p>
				<table class="kx-table">
					<thead><tr><th>Item</th><th>Godown</th><th>Lot</th><th>Qty</th></tr></thead>
					<tbody>
						<?php foreach ($lines as $line): ?>
							<tr>
								<td><?php echo (int)$line->item_id; ?></td>
								<td><?php echo (int)$line->godown_id; ?></td>
								<td><?php echo h((string)$line->lot_no); ?></td>
								<td><?php echo h((string)$line->quantity); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<div class="kx-actions">
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/deliveries">Back</a>
					<?php if (empty($billed) && !empty($canEdit)): ?>
						<a class="kx-btn kx-btn-primary" href="<?php echo SITEURL; ?>admin/erp/sales-invoice-form?delivery=<?php echo (int)$entity->id; ?>">Create sales invoice</a>
					<?php endif; ?>
				</div>
			<?php elseif (empty($order)): ?>
				<h1>Delivery</h1>
				<form method="get" class="kx-form">
					<label class="kx-field"><span>Sales order<span class="kx-req">*</span></span>
						<select name="order"><option value="">Select</option><?php foreach ($open as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
					</label>
					<div class="kx-actions">
						<button class="kx-btn kx-btn-primary" type="submit">Continue</button>
						<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/deliveries">Back</a>
					</div>
				</form>
			<?php else: ?>
				<h1>Deliver <?php echo h($order->so_no); ?></h1>
				<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
					<input type="hidden" name="order_id" value="<?php echo (int)$order->id; ?>">
					<label class="kx-field"><span>Delivered on<span class="kx-req">*</span></span><input type="date" name="delivered_on" value="<?php echo date('Y-m-d'); ?>"></label>
					<table class="kx-table">
						<thead><tr><th>Item</th><th>Open</th><th>Godown</th><th>Lot</th><th>Qty</th></tr></thead>
						<tbody>
							<?php foreach ($pending as $line): ?>
								<tr>
									<td><?php echo h($line->item_label); ?></td>
									<td><?php echo h((string)$line->remain); ?></td>
									<td><select name="lines[<?php echo (int)$line->id; ?>][godown_id]"><?php foreach ($godowns as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select></td>
									<td><input type="text" name="lines[<?php echo (int)$line->id; ?>][lot_no]"></td>
									<td><input type="number" step="0.001" min="0" name="lines[<?php echo (int)$line->id; ?>][quantity]"></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<div class="kx-actions">
						<button class="kx-btn kx-btn-primary" type="submit">Post delivery</button>
						<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/deliveries">Back</a>
					</div>
				<?php echo $this->Form->end(); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
