<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>reports">All reports</a>
	<a class="hrms-btn hrms-btn-primary" href="?export=csv">Download CSV</a>
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
