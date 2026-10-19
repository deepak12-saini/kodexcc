<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1>Opening balance</h1>
				<p class="kx-lead">Enter one side only. The other side goes to Capital, so the trial balance still matches.</p>
				<div class="kx-grid">
					<label class="kx-field"><span>Account<span class="kx-req">*</span></span>
						<select name="account_id"><?php foreach ($accounts as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
					</label>
					<label class="kx-field"><span>Date<span class="kx-req">*</span></span><input type="date" name="entry_date" value="<?php echo date('Y-m-d'); ?>"></label>
					<label class="kx-field"><span>Debit</span><input type="number" step="0.01" min="0" name="debit" value="0"></label>
					<label class="kx-field"><span>Credit</span><input type="number" step="0.01" min="0" name="credit" value="0"></label>
				</div>
				<div class="kx-actions">
					<button class="kx-btn kx-btn-primary" type="submit">Post</button>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/financials">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
