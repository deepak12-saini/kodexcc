<?php
$dateVal = function ($v): string {
	if ($v === null || $v === '') return '';
	if (is_object($v) && method_exists($v, 'format')) return $v->format('Y-m-d');
	return substr((string)$v, 0, 10);
};
?>
<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>repairs">All repairs</a>
	<?php if ($repair->it_asset): ?>
		<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>assets/view/<?php echo (int)$repair->asset_id; ?>"><?php echo h($repair->it_asset->asset_code); ?></a>
	<?php endif; ?>
</div>
<div class="hrms-panel">
	<h2><?php echo h($repair->repair_no); ?> <?php echo $this->It->badge($repair->status); ?></h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2', 'type' => 'file']); ?>
		<div>
			<label>Vendor / service centre</label>
			<select name="vendor_id">
				<option value="">—</option>
				<?php foreach ($vendors as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)$repair->vendor_id === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Status</label>
			<select name="status">
				<?php foreach ($repairStatuses as $s): ?>
					<option value="<?php echo h($s); ?>" <?php echo $repair->status === $s ? 'selected' : ''; ?>><?php echo h($this->It->label($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full"><label>Problem</label><textarea name="problem" rows="2"><?php echo h($repair->problem ?? ''); ?></textarea></div>
		<div class="full"><label>Diagnosis</label><textarea name="diagnosis" rows="2"><?php echo h($repair->diagnosis ?? ''); ?></textarea></div>
		<div class="full"><label>Required parts</label><textarea name="required_parts" rows="2"><?php echo h($repair->required_parts ?? ''); ?></textarea></div>
		<div><label>Estimated cost</label><input type="number" step="0.01" min="0" name="estimated_cost" value="<?php echo h($repair->estimated_cost ?? ''); ?>"></div>
		<div><label>Actual cost</label><input type="number" step="0.01" min="0" name="actual_cost" value="<?php echo h($repair->actual_cost ?? ''); ?>"></div>
		<div><label>Sent date</label><input type="date" name="sent_date" value="<?php echo h($dateVal($repair->sent_date)); ?>"></div>
		<div><label>Expected return</label><input type="date" name="expected_return_date" value="<?php echo h($dateVal($repair->expected_return_date)); ?>"></div>
		<div><label>Returned date</label><input type="date" name="returned_date" value="<?php echo h($dateVal($repair->returned_date)); ?>"></div>
		<div>
			<label>Under warranty</label>
			<select name="under_warranty">
				<option value="0" <?php echo empty($repair->under_warranty) ? 'selected' : ''; ?>>No</option>
				<option value="1" <?php echo !empty($repair->under_warranty) ? 'selected' : ''; ?>>Yes</option>
			</select>
		</div>
		<div class="full"><label>Notes</label><textarea name="notes" rows="2"><?php echo h($repair->notes ?? ''); ?></textarea></div>
		<div><label>Attachment title</label><input type="text" name="attachment_title"></div>
		<div><label>Add attachment</label><input type="file" name="attachment"></div>
		<div class="full"><button class="hrms-btn hrms-btn-primary" type="submit">Save</button></div>
	<?php echo $this->Form->end(); ?>
</div>
<div class="hrms-panel">
	<h2>Attachments</h2>
	<ul>
		<?php foreach ($repair->it_repair_files ?? [] as $file): ?>
			<li><a href="<?php echo SITEURL . h($file->file_path); ?>" target="_blank"><?php echo h($file->title ?: 'File'); ?></a></li>
		<?php endforeach; ?>
		<?php if (!count($repair->it_repair_files ?? [])): ?><li>No files yet.</li><?php endif; ?>
	</ul>
</div>
