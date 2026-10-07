<div class="hrms-panel">
	<h2><?php echo !empty($entity->id) ? 'Edit vendor' : 'Add vendor'; ?></h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div><label>Vendor name</label><input type="text" name="name" required value="<?php echo h($entity->name ?? ''); ?>"></div>
		<div><label>Contact person</label><input type="text" name="contact_person" value="<?php echo h($entity->contact_person ?? ''); ?>"></div>
		<div><label>Phone</label><input type="text" name="phone" value="<?php echo h($entity->phone ?? ''); ?>"></div>
		<div><label>Email</label><input type="email" name="email" value="<?php echo h($entity->email ?? ''); ?>"></div>
		<div><label>GST</label><input type="text" name="gst_number" value="<?php echo h($entity->gst_number ?? ''); ?>"></div>
		<div>
			<label>Status</label>
			<select name="status">
				<option value="1" <?php echo !isset($entity->status) || (int)$entity->status === 1 ? 'selected' : ''; ?>>Active</option>
				<option value="0" <?php echo isset($entity->status) && (int)$entity->status === 0 ? 'selected' : ''; ?>>Inactive</option>
			</select>
		</div>
		<div class="full"><label>Address</label><textarea name="address" rows="2"><?php echo h($entity->address ?? ''); ?></textarea></div>
		<div class="full"><label>Warranty information</label><textarea name="warranty_notes" rows="2"><?php echo h($entity->warranty_notes ?? ''); ?></textarea></div>
		<div class="full"><label>Notes</label><textarea name="notes" rows="2"><?php echo h($entity->notes ?? ''); ?></textarea></div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Save</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>vendors">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
