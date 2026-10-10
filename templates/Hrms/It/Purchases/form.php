<?php
$isEdit = !$entity->isNew();
$selected = static function ($current, $id): string {
	return (string)$current !== '' && (int)$current === (int)$id ? 'selected' : '';
};
?>
<div class="hrms-panel">
	<h2><?php echo $isEdit ? 'Edit ' . h($entity->request_no) : 'New purchase request'; ?></h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Requested by</label>
			<select name="requested_by">
				<option value="">—</option>
				<?php foreach ($employees as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo $selected($entity->requested_by ?? '', $id); ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Department</label>
			<select name="department_id">
				<option value="">—</option>
				<?php foreach ($departments as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo $selected($entity->department_id ?? '', $id); ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Item</label>
			<input type="text" name="item_name" required value="<?php echo h($entity->item_name ?? ''); ?>">
		</div>
		<div>
			<label>Quantity</label>
			<input type="number" name="quantity" min="1" value="<?php echo (int)($entity->quantity ?: 1); ?>" required>
		</div>
		<div>
			<label>Estimated cost</label>
			<input type="number" step="0.01" min="0" name="estimated_cost" value="<?php echo h($entity->estimated_cost ?? ''); ?>">
		</div>
		<div>
			<label>Preferred vendor</label>
			<select name="vendor_id">
				<option value="">—</option>
				<?php foreach ($vendors as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo $selected($entity->vendor_id ?? '', $id); ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full"><label>Purpose</label><textarea name="purpose" rows="2"><?php echo h($entity->purpose ?? ''); ?></textarea></div>
		<div class="full"><label>Specifications</label><textarea name="specifications" rows="3"><?php echo h($entity->specifications ?? ''); ?></textarea></div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit"><?php echo $isEdit ? 'Save changes' : 'Submit request'; ?></button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $isEdit ? $itBase . 'purchases/view/' . (int)$entity->id : $itBase . 'purchases'; ?>">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
