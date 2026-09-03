<div class="hrms-actions">
	<form method="get" style="display:flex;gap:.5rem;align-items:end;margin:0;">
		<div>
			<label>Status</label>
			<select name="status">
				<option value="">All</option>
				<?php foreach (['pending', 'approved', 'rejected', 'cancelled'] as $s): ?>
					<option value="<?php echo $s; ?>" <?php echo $status === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="hrms-btn hrms-btn-ghost">Filter</button>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>No.</th>
				<th>Employee</th>
				<th>Type</th>
				<th>Title</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><code><?php echo h($item->request_no); ?></code></td>
				<td><?php echo h($item->hr_employee->full_name ?? ''); ?></td>
				<td><?php echo h($item->hr_request_type->name ?? ''); ?></td>
				<td><?php echo h($item->title); ?></td>
				<td><span class="badge badge-muted"><?php echo h($item->status); ?></span></td>
				<td><a href="<?php echo SITEURL; ?>hrms/requests/view/<?php echo (int)$item->id; ?>">Review</a></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="6">No requests.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
