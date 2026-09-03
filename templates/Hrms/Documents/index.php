<div class="hrms-actions">
	<form method="get" class="hrms-form" style="display:flex;gap:.75rem;align-items:end;margin:0;">
		<div>
			<label>Employee</label>
			<select name="employee_id">
				<option value="">All</option>
				<?php foreach ($employees as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (string)$employeeId === (string)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="hrms-btn hrms-btn-ghost">Filter</button>
	</form>
	<a class="hrms-btn hrms-btn-primary" href="<?php echo SITEURL; ?>hrms/documents/add">Upload Document</a>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Employee</th>
				<th>Type</th>
				<th>Title</th>
				<th>Uploaded</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo h($item->hr_employee->full_name ?? ''); ?></td>
				<td><?php echo h($item->doc_type); ?></td>
				<td><?php echo h($item->title); ?></td>
				<td><?php echo h(is_object($item->created) ? $item->created->format('Y-m-d') : $item->created); ?></td>
				<td>
					<?php if (!empty($item->file_path)): ?>
						<a href="<?php echo SITEURL . h($item->file_path); ?>" target="_blank" rel="noopener">Open</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="5">No documents.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
