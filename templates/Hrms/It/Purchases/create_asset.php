<div class="hrms-panel">
	<h2>Create asset from <?php echo h($purchase->request_no); ?></h2>
	<p><?php echo h($purchase->item_name); ?> · quantity <?php echo (int)$purchase->quantity; ?>. Each unit becomes its own asset and starts as Available.</p>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Asset type</label>
			<select name="asset_type" required>
				<?php foreach ($assetTypes as $key => $label): ?>
					<option value="<?php echo h($key); ?>"><?php echo h($label); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div><label>Brand</label><input type="text" name="brand"></div>
		<div><label>Model</label><input type="text" name="model" value="<?php echo h($purchase->item_name); ?>"></div>
		<div><label>Serial number <?php echo (int)$purchase->quantity > 1 ? '(first unit)' : ''; ?></label><input type="text" name="serial_number"></div>
		<div><label>Processor</label><input type="text" name="processor"></div>
		<div><label>RAM</label><input type="text" name="ram"></div>
		<div><label>Storage</label><input type="text" name="storage"></div>
		<div><label>Location</label><input type="text" name="location"></div>
		<div><label>Warranty until</label><input type="date" name="warranty_until"></div>
		<div class="full"><label>Warranty notes</label><input type="text" name="warranty_notes"></div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Create <?php echo (int)$purchase->quantity; ?> asset<?php echo (int)$purchase->quantity > 1 ? 's' : ''; ?></button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>purchases/view/<?php echo (int)$purchase->id; ?>">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
