<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>vendors/add">Add vendor</a>
	<form method="get" style="display:flex;gap:.5rem;margin:0;">
		<input type="search" name="q" value="<?php echo h($q ?? ''); ?>" placeholder="Name, contact, GST">
		<button class="hrms-btn hrms-btn-ghost" type="submit">Search</button>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead><tr><th>Vendor</th><th>Contact</th><th>Phone</th><th>Email</th><th>GST</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>vendors/view/<?php echo (int)$item->id; ?>"><?php echo h($item->name); ?></a></td>
				<td><?php echo h($item->contact_person ?: '—'); ?></td>
				<td><?php echo h($item->phone ?: '—'); ?></td>
				<td><?php echo h($item->email ?: '—'); ?></td>
				<td><?php echo h($item->gst_number ?: '—'); ?></td>
				<td><a href="<?php echo $itBase; ?>vendors/edit/<?php echo (int)$item->id; ?>">Edit</a></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="6">No vendors yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
