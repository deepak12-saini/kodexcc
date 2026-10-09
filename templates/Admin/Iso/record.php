<?php
$values = $values ?? [];
$csv = $this->request->getQueryParams();
unset($csv['page']);
$csv['export'] = 'csv';
$dashed = function (string $action): string {
	return strtolower((string)preg_replace('/([a-z])([A-Z])/', '$1-$2', $action));
};
?>
<div class="page-content">
	<div class="page-header">
		<h1>
			<?php echo h($title); ?>
			<?php if (!empty($canEdit)): ?>
				<a class="btn btn-primary btn-sm pull-right" href="<?php echo SITEURL . 'admin/iso/' . h($dashed($addAction)); ?>">Add</a>
			<?php endif; ?>
		</h1>
	</div>
	<form method="get" class="form-inline" style="margin-bottom:12px;">
		<input type="search" name="q" value="<?php echo h($values['q'] ?? ''); ?>" class="form-control" placeholder="Search" style="margin-right:6px;">
		<?php foreach ($filter['selects'] ?? [] as $key => $options): ?>
			<select name="<?php echo h($key); ?>" class="form-control" style="margin-right:6px;">
				<option value=""><?php echo h($key === 'doc_type' ? 'Document Category' : ucwords(str_replace('_', ' ', $key))); ?>: All</option>
				<?php foreach ($options as $ok => $ol): ?>
					<option value="<?php echo h((string)$ok); ?>" <?php echo (string)($values[$key] ?? '') === (string)$ok ? 'selected' : ''; ?>><?php echo h($ol); ?></option>
				<?php endforeach; ?>
			</select>
		<?php endforeach; ?>
		<?php if (!empty($showDates)): ?>
			<input type="date" name="from" value="<?php echo h($values['from'] ?? ''); ?>" class="form-control" style="margin-right:6px;">
			<input type="date" name="to" value="<?php echo h($values['to'] ?? ''); ?>" class="form-control" style="margin-right:6px;">
		<?php endif; ?>
		<button class="btn btn-sm" type="submit">Filter</button>
		<a class="btn btn-sm" href="<?php echo SITEURL . 'admin/iso/' . h($dashed($listAction)); ?>">Clear</a>
		<a class="btn btn-sm btn-success" href="?<?php echo h(http_build_query($csv)); ?>">Download CSV</a>
	</form>
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th style="width:70px;">S.No</th>
					<?php foreach ($header as $col): ?><th><?php echo h($col); ?></th><?php endforeach; ?>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php $sno = ((max(1, (int)$this->request->getQuery('page', 1)) - 1) * (int)($this->Paginator->param('perPage') ?: 20)) + 1; ?>
				<?php foreach ($items as $item): ?>
					<tr>
						<td><?php echo $sno++; ?></td>
						<?php foreach ($map($item) as $cell): ?>
							<td><?php echo h((string)$cell); ?></td>
						<?php endforeach; ?>
						<td style="white-space:nowrap;">
							<?php if (!empty($canEdit)): ?>
								<a class="btn btn-mini btn-info" href="<?php echo SITEURL . 'admin/iso/' . h($dashed($addAction)) . '/' . (int)$item->id; ?>">Edit</a>
								<?php echo $this->Form->create(null, ['url' => ['action' => $listAction], 'style' => 'display:inline;']); ?>
									<input type="hidden" name="delete_id" value="<?php echo (int)$item->id; ?>">
									<button class="btn btn-mini btn-danger" type="submit" onclick="return confirm('Delete this record? This cannot be undone.');">Delete</button>
								<?php echo $this->Form->end(); ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php if (!count($items)): ?>
					<tr><td colspan="<?php echo count($header) + 2; ?>">No records yet.</td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php echo $this->element('admin_pagination'); ?>
</div>
