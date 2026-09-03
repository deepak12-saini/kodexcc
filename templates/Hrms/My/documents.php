<div class="hrms-panel">
	<h2>My Documents</h2>
	<table class="hrms-table">
		<thead><tr><th>Type</th><th>Title</th><th>Date</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
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
			<tr><td colspan="4">No documents on file.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
