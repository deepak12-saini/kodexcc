<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1>Bank reconciliation</h1>
				<p class="kx-lead">Book balance <?php echo h(number_format((float)$bookBalance, 2)); ?>. Tick the rows that appear on the bank statement.</p>
				<table class="kx-table">
					<thead><tr><th></th><th>Payment</th><th>Date</th><th>Direction</th><th>Amount</th><th>Reference</th></tr></thead>
					<tbody>
						<?php foreach ($rows as $row): ?>
							<tr>
								<td><input type="checkbox" name="payment_id[]" value="<?php echo (int)$row->id; ?>"></td>
								<td><?php echo h($row->payment_no); ?></td>
								<td><?php echo h((string)$row->paid_on); ?></td>
								<td><?php echo h($row->direction); ?></td>
								<td><?php echo h(number_format((float)$row->amount, 2)); ?></td>
								<td><?php echo h((string)$row->reference); ?></td>
							</tr>
						<?php endforeach; ?>
						<?php if (!count($rows)): ?><tr><td colspan="6">Everything is reconciled.</td></tr><?php endif; ?>
					</tbody>
				</table>
				<div class="kx-actions">
					<?php if (!empty($canEdit) && count($rows)): ?><button class="kx-btn kx-btn-primary" type="submit">Mark reconciled</button><?php endif; ?>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/banking">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
