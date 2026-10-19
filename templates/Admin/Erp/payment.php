<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1>Payment</h1>
				<p class="kx-lead">Money in settles a sales invoice. Money out settles a supplier bill. The journal is posted with the payment.</p>
				<div class="kx-grid">
					<label class="kx-field"><span>Direction<span class="kx-req">*</span></span>
						<select name="direction" id="pay-direction"><option value="in">Money in</option><option value="out">Money out</option></select>
					</label>
					<label class="kx-field"><span>Bank<span class="kx-req">*</span></span>
						<select name="bank_account_id"><?php foreach ($banks as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
					</label>
					<label class="kx-field pay-ar"><span>Sales invoice<span class="kx-req">*</span></span>
						<select class="pay-invoice"><?php foreach ($arInvoices as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
					</label>
					<label class="kx-field pay-ap" style="display:none;"><span>Supplier bill<span class="kx-req">*</span></span>
						<select class="pay-invoice"><?php foreach ($apInvoices as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
					</label>
					<input type="hidden" name="invoice_id" id="pay-invoice-id">
					<label class="kx-field"><span>Amount<span class="kx-req">*</span></span><input type="number" step="0.01" min="0" name="amount"></label>
					<label class="kx-field"><span>Date<span class="kx-req">*</span></span><input type="date" name="paid_on" value="<?php echo date('Y-m-d'); ?>"></label>
					<label class="kx-field"><span>Reference</span><input type="text" name="reference" placeholder="Cheque or UTR"></label>
				</div>
				<div class="kx-actions">
					<button class="kx-btn kx-btn-primary" type="submit">Post</button>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/payments">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<script>
var direction = document.getElementById('pay-direction');
var hidden = document.getElementById('pay-invoice-id');
function syncPay() {
	var inbound = direction.value === 'in';
	document.querySelector('.pay-ar').style.display = inbound ? '' : 'none';
	document.querySelector('.pay-ap').style.display = inbound ? 'none' : '';
	var active = document.querySelector(inbound ? '.pay-ar .pay-invoice' : '.pay-ap .pay-invoice');
	hidden.value = active ? active.value : '';
}
direction.addEventListener('change', syncPay);
document.querySelectorAll('.pay-invoice').forEach(function (select) { select.addEventListener('change', syncPay); });
syncPay();
</script>
