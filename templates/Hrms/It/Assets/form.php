<?php
$dateVal = function ($v): string {
	if ($v === null || $v === '') {
		return '';
	}
	if (is_object($v) && method_exists($v, 'format')) {
		return $v->format('Y-m-d');
	}
	return substr((string)$v, 0, 10);
};
?>
<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit asset' : 'Add asset'; ?></h2>
	<?php echo $this->Form->create($entity, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Asset ID</label>
			<input type="text" name="asset_code" value="<?php echo h($entity->asset_code ?? ''); ?>" placeholder="Auto if blank">
		</div>
		<div>
			<label>Asset type</label>
			<select name="asset_type" required>
				<?php foreach ($assetTypes as $key => $label): ?>
					<option value="<?php echo h($key); ?>" <?php echo ($entity->asset_type ?? 'other') === $key ? 'selected' : ''; ?>><?php echo h($label); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Brand</label>
			<input type="text" name="brand" value="<?php echo h($entity->brand ?? ''); ?>">
		</div>
		<div>
			<label>Model</label>
			<input type="text" name="model" value="<?php echo h($entity->model ?? ''); ?>">
		</div>
		<div>
			<label>Serial number</label>
			<input type="text" name="serial_number" value="<?php echo h($entity->serial_number ?? ''); ?>">
		</div>
		<div>
			<label>Processor</label>
			<input type="text" name="processor" value="<?php echo h($entity->processor ?? ''); ?>">
		</div>
		<div>
			<label>RAM</label>
			<input type="text" name="ram" value="<?php echo h($entity->ram ?? ''); ?>">
		</div>
		<div>
			<label>Storage</label>
			<input type="text" name="storage" value="<?php echo h($entity->storage ?? ''); ?>">
		</div>
		<div>
			<label>Purchase date</label>
			<input type="date" name="purchase_date" value="<?php echo h($dateVal($entity->purchase_date ?? null)); ?>">
		</div>
		<div>
			<label>Purchase cost</label>
			<input type="number" step="0.01" min="0" name="purchase_cost" value="<?php echo h($entity->purchase_cost ?? ''); ?>">
		</div>
		<div>
			<label>Vendor</label>
			<select name="vendor_id">
				<option value="">—</option>
				<?php foreach ($vendors as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)($entity->vendor_id ?? 0) === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Warranty until</label>
			<input type="date" name="warranty_until" value="<?php echo h($dateVal($entity->warranty_until ?? null)); ?>">
		</div>
		<div>
			<label>Warranty notes</label>
			<input type="text" name="warranty_notes" value="<?php echo h($entity->warranty_notes ?? ''); ?>">
		</div>
		<div>
			<label>Location</label>
			<input type="text" name="location" value="<?php echo h($entity->location ?? ''); ?>">
		</div>
		<div>
			<label>Status</label>
			<select name="status">
				<?php foreach ($assetStatuses as $s): ?>
					<option value="<?php echo h($s); ?>" <?php echo ($entity->status ?? 'available') === $s ? 'selected' : ''; ?>><?php echo h($this->It->label($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full">
			<label>Notes</label>
			<textarea name="notes" rows="3"><?php echo h($entity->notes ?? ''); ?></textarea>
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Save</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>assets">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
