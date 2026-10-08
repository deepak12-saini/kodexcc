<?php
$reportFilters = $reportFilters ?? [];
$reportFilterValues = $reportFilterValues ?? [];
$csvQuery = $this->request->getQueryParams();
unset($csvQuery['page']);
$csvQuery['export'] = 'csv';
?>
<?php if ($reportFilters): ?>
<form method="get" class="hrms-actions" action="">
	<?php foreach ($reportFilters as $key => $def): ?>
		<?php
		$val = (string)($reportFilterValues[$key] ?? '');
		$type = (string)($def['type'] ?? 'select');
		?>
		<?php if ($type === 'select'): ?>
			<select name="<?php echo h($key); ?>" aria-label="<?php echo h($def['label']); ?>">
				<option value=""><?php echo h($def['label']); ?>: All</option>
				<?php foreach ($def['options'] as $optionKey => $optionLabel): ?>
					<option value="<?php echo h((string)$optionKey); ?>" <?php echo $val === (string)$optionKey ? 'selected' : ''; ?>><?php echo h($optionLabel); ?></option>
				<?php endforeach; ?>
			</select>
		<?php elseif ($type === 'date'): ?>
			<label style="display:flex;align-items:center;gap:.35rem;font-size:.82rem;color:#5c6b7a;">
				<?php echo h($def['label']); ?>
				<input type="date" name="<?php echo h($key); ?>" value="<?php echo h($val); ?>">
			</label>
		<?php else: ?>
			<input type="search" name="<?php echo h($key); ?>" value="<?php echo h($val); ?>" placeholder="<?php echo h($def['label']); ?>">
		<?php endif; ?>
	<?php endforeach; ?>
	<button class="hrms-btn hrms-btn-ghost" type="submit">Filter</button>
	<a class="hrms-btn hrms-btn-ghost" href="?">Clear</a>
</form>
<?php endif; ?>
<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>reports">All reports</a>
	<a class="hrms-btn hrms-btn-primary" href="?<?php echo h(http_build_query($csvQuery)); ?>">Download CSV</a>
</div>
<?php if (isset($costTotal)): ?>
	<div class="hrms-cards"><div class="hrms-card"><div class="label">Total actual repair cost</div><div class="value" style="font-size:1.2rem;"><?php echo $this->It->money($costTotal); ?></div></div></div>
<?php endif; ?>
<div class="hrms-panel">
	<h2><?php echo h($title); ?></h2>
	<table class="hrms-table">
		<thead>
			<tr>
				<?php foreach ($header as $col): ?><th><?php echo h($col); ?></th><?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<?php foreach ($map($item) as $cell): ?>
					<td><?php echo h((string)$cell); ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="<?php echo count($header); ?>">No rows.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
