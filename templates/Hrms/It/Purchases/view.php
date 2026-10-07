<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>purchases">All requests</a>
	<?php if (in_array($purchase->approval_status, ['received', 'asset_created'], true)): ?>
		<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>purchases/create-asset/<?php echo (int)$purchase->id; ?>">Create asset</a>
	<?php endif; ?>
</div>
<div class="hrms-panel">
	<h2><?php echo h($purchase->request_no); ?> <?php echo $this->It->badge($purchase->approval_status); ?></h2>
	<table class="hrms-table">
		<tbody>
			<tr><th>Item</th><td><?php echo h($purchase->item_name); ?></td><th>Qty</th><td><?php echo (int)$purchase->quantity; ?></td></tr>
			<tr><th>Requester</th><td><?php echo h($purchase->requester->full_name ?? '—'); ?></td><th>Department</th><td><?php echo h($purchase->hr_department->name ?? '—'); ?></td></tr>
			<tr><th>Vendor</th><td><?php echo h($purchase->it_vendor->name ?? '—'); ?></td><th>Estimated</th><td><?php echo $this->It->money($purchase->estimated_cost); ?></td></tr>
			<tr><th>Purpose</th><td colspan="3"><?php echo nl2br(h($purchase->purpose ?: '—')); ?></td></tr>
			<tr><th>Specifications</th><td colspan="3"><?php echo nl2br(h($purchase->specifications ?: '—')); ?></td></tr>
			<tr><th>Quotation</th><td colspan="3"><?php echo nl2br(h($purchase->quotation_notes ?: '—')); ?> <?php if ($purchase->quotation_file): ?><a href="<?php echo SITEURL . h($purchase->quotation_file); ?>" target="_blank">File</a><?php endif; ?></td></tr>
			<tr><th>Approved by</th><td><?php echo h($purchase->approver->hr_employee->full_name ?? $purchase->approver->username ?? '—'); ?></td><th>When</th><td><?php echo $this->It->date($purchase->approved_at, true); ?></td></tr>
			<tr><th>Purchase date</th><td><?php echo $this->It->date($purchase->purchase_date); ?></td><th>Actual cost</th><td><?php echo $this->It->money($purchase->actual_cost); ?></td></tr>
			<tr><th>Invoice</th><td colspan="3"><?php echo h($purchase->invoice_no ?: '—'); ?> <?php if ($purchase->invoice_file): ?><a href="<?php echo SITEURL . h($purchase->invoice_file); ?>" target="_blank">File</a><?php endif; ?></td></tr>
		</tbody>
	</table>
</div>
<?php if (in_array($purchase->approval_status, ['requested', 'quotation'], true)): ?>
<div class="hrms-panel">
	<h2>Quotation</h2>
	<?php echo $this->Form->create(null, ['url' => ['action' => 'quotation', $purchase->id], 'class' => 'hrms-form grid-2', 'type' => 'file']); ?>
		<div>
			<label>Vendor</label>
			<select name="vendor_id">
				<option value="">—</option>
				<?php foreach ($vendors as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)$purchase->vendor_id === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div><label>Estimated cost</label><input type="number" step="0.01" min="0" name="estimated_cost" value="<?php echo h($purchase->estimated_cost ?? ''); ?>"></div>
		<div class="full"><label>Quotation notes</label><textarea name="quotation_notes" rows="2"><?php echo h($purchase->quotation_notes ?? ''); ?></textarea></div>
		<div><label>Quotation file</label><input type="file" name="quotation_file"></div>
		<div class="full"><button class="hrms-btn hrms-btn-primary" type="submit">Save quotation</button></div>
	<?php echo $this->Form->end(); ?>
</div>
<div class="hrms-panel">
	<h2>Approval</h2>
	<?php echo $this->Form->create(null, ['url' => ['action' => 'decide', $purchase->id], 'class' => 'hrms-form']); ?>
		<label>Note</label>
		<textarea name="notes" rows="2"></textarea>
		<div style="display:flex;gap:.5rem;margin-top:.8rem;">
			<button class="hrms-btn hrms-btn-primary" name="decision" value="approved" type="submit">Approve</button>
			<button class="hrms-btn hrms-btn-ghost" name="decision" value="rejected" type="submit">Reject</button>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
<?php endif; ?>
<?php if ($purchase->approval_status === 'approved'): ?>
<div class="hrms-panel">
	<h2>Mark purchased</h2>
	<?php echo $this->Form->create(null, ['url' => ['action' => 'purchase', $purchase->id], 'class' => 'hrms-form grid-2', 'type' => 'file']); ?>
		<div><label>Purchase date</label><input type="date" name="purchase_date" value="<?php echo date('Y-m-d'); ?>"></div>
		<div><label>Actual cost</label><input type="number" step="0.01" min="0" name="actual_cost" value="<?php echo h($purchase->estimated_cost ?? ''); ?>"></div>
		<div><label>Invoice number</label><input type="text" name="invoice_no"></div>
		<div>
			<label>Vendor</label>
			<select name="vendor_id">
				<option value="">—</option>
				<?php foreach ($vendors as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)$purchase->vendor_id === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div><label>Invoice file</label><input type="file" name="invoice_file"></div>
		<div class="full"><button class="hrms-btn hrms-btn-primary" type="submit">Mark purchased</button></div>
	<?php echo $this->Form->end(); ?>
</div>
<?php endif; ?>
<?php if ($purchase->approval_status === 'purchased'): ?>
<div class="hrms-panel">
	<h2>Receive equipment</h2>
	<?php echo $this->Form->create(null, ['url' => ['action' => 'receive', $purchase->id]]); ?>
		<button class="hrms-btn hrms-btn-primary" type="submit">Mark received</button>
	<?php echo $this->Form->end(); ?>
</div>
<?php endif; ?>
<?php if (count($purchase->it_assets ?? [])): ?>
<div class="hrms-panel">
	<h2>Assets created</h2>
	<ul>
		<?php foreach ($purchase->it_assets as $asset): ?>
			<li><a href="<?php echo $itBase; ?>assets/view/<?php echo (int)$asset->id; ?>"><?php echo h($asset->asset_code); ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>
