<?php
$dateVal = function ($v): string {
	if ($v === null || $v === '') return '';
	if (is_object($v) && method_exists($v, 'format')) return $v->format('Y-m-d');
	return substr((string)$v, 0, 10);
};
?>
<div class="hrms-panel">
	<h2>New repair</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2', 'type' => 'file']); ?>
		<div>
			<label>Asset</label>
			<select name="asset_id" required>
				<option value="">Select</option>
				<?php foreach ($assets as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->asset_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>IT ticket</label>
			<select name="ticket_id">
				<option value="">—</option>
				<?php foreach ($tickets as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->ticket_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Vendor / service centre</label>
			<select name="vendor_id">
				<option value="">—</option>
				<?php foreach ($vendors as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>"><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Status</label>
			<select name="status">
				<?php foreach ($repairStatuses as $s): ?>
					<option value="<?php echo h($s); ?>"><?php echo h($this->It->label($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full">
			<label>Problem</label>
			<textarea name="problem" rows="3"><?php echo h($entity->problem ?? ''); ?></textarea>
		</div>
		<div class="full">
			<label>Diagnosis</label>
			<textarea name="diagnosis" rows="2"></textarea>
		</div>
		<div class="full">
			<label>Required parts</label>
			<textarea name="required_parts" rows="2"></textarea>
		</div>
		<div>
			<label>Estimated cost</label>
			<input type="number" step="0.01" min="0" name="estimated_cost">
		</div>
		<div>
			<label>Actual cost</label>
			<input type="number" step="0.01" min="0" name="actual_cost">
		</div>
		<div>
			<label>Sent date</label>
			<input type="date" name="sent_date" value="<?php echo h($dateVal($entity->sent_date ?? null)); ?>">
		</div>
		<div>
			<label>Expected return</label>
			<input type="date" name="expected_return_date">
		</div>
		<div>
			<label>Returned date</label>
			<input type="date" name="returned_date">
		</div>
		<div>
			<label>Under warranty</label>
			<select name="under_warranty"><option value="0">No</option><option value="1">Yes</option></select>
		</div>
		<div class="full">
			<label>Notes</label>
			<textarea name="notes" rows="2"></textarea>
		</div>
		<div>
			<label>Attachment title</label>
			<input type="text" name="attachment_title">
		</div>
		<div>
			<label>Attachment</label>
			<input type="file" name="attachment">
		</div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Save repair</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>repairs">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
