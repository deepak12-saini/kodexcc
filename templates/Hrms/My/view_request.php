<div class="hrms-panel">
	<h2><?php echo h($item->request_no); ?></h2>
	<table class="hrms-table">
		<tr><th>Type</th><td><?php echo h($item->hr_request_type->name ?? ''); ?></td></tr>
		<tr><th>Title</th><td><?php echo h($item->title); ?></td></tr>
		<tr><th>Description</th><td><?php echo nl2br(h($item->description)); ?></td></tr>
		<tr><th>Priority</th><td><?php echo h($item->priority); ?></td></tr>
		<?php if ($item->asset_category): ?>
			<tr><th>Asset Category</th><td><?php echo h($item->asset_category); ?></td></tr>
		<?php endif; ?>
		<tr><th>Status</th><td><span class="badge badge-muted"><?php echo h($item->status); ?></span></td></tr>
		<?php if (!empty($item->linked_asset)): ?>
			<tr><th>Assigned Asset</th><td><?php echo h($item->linked_asset->asset_code . ' — ' . $item->linked_asset->name); ?></td></tr>
		<?php endif; ?>
		<?php if ($item->hr_remark): ?>
			<tr><th>HR Remark</th><td><?php echo nl2br(h($item->hr_remark)); ?></td></tr>
		<?php endif; ?>
	</table>
	<p style="margin-top:1rem;"><a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/my/requests">Back</a></p>
</div>
