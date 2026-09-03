<div class="hrms-panel">
	<h2><?php echo h($item->request_no); ?></h2>
	<table class="hrms-table">
		<tr><th>Employee</th><td><?php echo h($item->hr_employee->full_name ?? ''); ?></td></tr>
		<tr><th>Type</th><td><?php echo h($item->hr_request_type->name ?? ''); ?></td></tr>
		<tr><th>Title</th><td><?php echo h($item->title); ?></td></tr>
		<tr><th>Description</th><td><?php echo nl2br(h($item->description)); ?></td></tr>
		<tr><th>Priority</th><td><?php echo h($item->priority); ?></td></tr>
		<?php if ($item->asset_category): ?>
			<tr><th>Asset Category</th><td><?php echo h($item->asset_category); ?></td></tr>
		<?php endif; ?>
		<tr><th>Status</th><td><?php echo h($item->status); ?></td></tr>
		<?php if (!empty($item->linked_asset)): ?>
			<tr><th>Assigned Asset</th><td><?php echo h($item->linked_asset->asset_code . ' — ' . $item->linked_asset->name); ?></td></tr>
		<?php endif; ?>
		<?php if ($item->hr_remark): ?>
			<tr><th>HR Remark</th><td><?php echo nl2br(h($item->hr_remark)); ?></td></tr>
		<?php endif; ?>
	</table>

	<?php if ($item->status === 'pending'): ?>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form', 'style' => 'margin-top:1.25rem;']); ?>
		<?php if (!empty($item->hr_request_type->needs_asset)): ?>
		<div>
			<label>Assign Available Asset</label>
			<select name="linked_asset_id">
				<option value="">Select asset…</option>
				<?php foreach ($availableAssets as $id => $label): ?>
					<option value="<?php echo (int)$id; ?>"><?php echo h($label); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php endif; ?>
		<div>
			<label>Remark</label>
			<textarea name="hr_remark" rows="3"></textarea>
		</div>
		<div style="display:flex;gap:.5rem;margin-top:.75rem;">
			<button type="submit" name="decision" value="approved" class="hrms-btn hrms-btn-primary">Approve</button>
			<button type="submit" name="decision" value="rejected" class="hrms-btn hrms-btn-ghost">Reject</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/requests">Back</a>
		</div>
	<?php echo $this->Form->end(); ?>
	<?php else: ?>
		<p style="margin-top:1rem;"><a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/requests">Back</a></p>
	<?php endif; ?>
</div>
