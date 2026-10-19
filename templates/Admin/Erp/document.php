<?php
$basePath = $basePath ?? 'admin/erp/';
$dashed = function (string $action): string {
	return strtolower((string)preg_replace('/([a-z])([A-Z])/', '$1-$2', $action));
};
$cell = function ($line, string $key) {
	if (is_array($line)) {
		return $line[$key] ?? '';
	}

	return $line->{$key} ?? '';
};
$priced = isset($spec['rate']);
$editable = empty($locked) && !empty($canEdit);
if ($lines === []) {
	$lines = [[]];
}
?>
<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form erp-doc']); ?>
			<div class="kx-card">
				<h1><?php echo h($title); ?><?php if (!empty($numberLabel) && $numberLabel !== 'Filled when you save'): ?> <?php echo h($numberLabel); ?><?php endif; ?></h1>
				<p class="kx-lead"><?php echo $editable ? 'Fill in the details, then save. Fields marked with * are required.' : 'This document is locked.'; ?></p>
				<div class="kx-grid">
					<label class="kx-field">
						<span>Number</span>
						<input type="text" value="<?php echo h($numberLabel); ?>" readonly>
					</label>
					<?php foreach ($fields as $key => $field): ?>
						<?php
						$type = $field['type'] ?? 'text';
						$current = $cell($entity, $key);
						if (is_object($current) && method_exists($current, 'format')) {
							$current = $current->format('Y-m-d');
						}
						if (($current === null || $current === '') && isset($field['default'])) {
							$current = $field['default'];
						}
						$placeholder = (string)($field['placeholder'] ?? '');
						?>
						<?php if ($type === 'hidden'): ?>
							<input type="hidden" name="<?php echo h($key); ?>" value="<?php echo h((string)$current); ?>">
						<?php else: ?>
							<label class="kx-field<?php echo $type === 'textarea' ? ' kx-span' : ''; ?>">
								<span><?php echo h($field['label']); ?><?php if (!empty($field['required'])): ?><span class="kx-req">*</span><?php endif; ?></span>
								<?php if ($type === 'textarea'): ?>
									<textarea name="<?php echo h($key); ?>" rows="3" <?php echo $editable ? '' : 'readonly'; ?>><?php echo h((string)$current); ?></textarea>
								<?php elseif ($type === 'select' || $type === 'person'): ?>
									<?php
									$options = $type === 'person' ? array_combine($isoPeople ?: [], $isoPeople ?: []) : ($field['options'] ?? []);
									if (!is_array($options)) {
										$options = [];
									}
									?>
									<select name="<?php echo h($key); ?>" <?php echo $editable ? '' : 'disabled'; ?>>
										<?php if (empty($field['required'])): ?><option value=""><?php echo h($placeholder !== '' ? $placeholder : 'Select'); ?></option><?php endif; ?>
										<?php if (!empty($field['required'])): ?><option value=""><?php echo h($placeholder !== '' ? $placeholder : 'Select'); ?></option><?php endif; ?>
										<?php foreach ($options as $ok => $ol): ?>
											<option value="<?php echo h((string)$ok); ?>" <?php echo (string)$current === (string)$ok ? 'selected' : ''; ?>><?php echo h($ol); ?></option>
										<?php endforeach; ?>
									</select>
								<?php elseif ($type === 'date'): ?>
									<input type="date" name="<?php echo h($key); ?>" value="<?php echo h((string)$current); ?>" <?php echo $editable ? '' : 'readonly'; ?>>
								<?php else: ?>
									<input type="text" name="<?php echo h($key); ?>" value="<?php echo h((string)$current); ?>" placeholder="<?php echo h($placeholder); ?>" <?php echo $editable ? '' : 'readonly'; ?>>
								<?php endif; ?>
							</label>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="kx-table-wrap" style="margin-top:16px;">
					<table class="kx-table erp-lines">
						<thead>
							<tr>
								<?php foreach ($spec as $column): ?><th><?php echo h($column['label']); ?></th><?php endforeach; ?>
								<?php if ($editable): ?><th></th><?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($lines as $index => $line): ?>
								<tr>
									<?php foreach ($spec as $key => $column): ?>
										<?php $value = $cell($line, $key); ?>
										<td>
											<?php if (($column['type'] ?? '') === 'select'): ?>
												<select name="lines[<?php echo (int)$index; ?>][<?php echo h($key); ?>]" <?php echo $editable ? '' : 'disabled'; ?>>
													<option value="">Select</option>
													<?php foreach ($column['options'] as $ok => $ol): ?>
														<option value="<?php echo h((string)$ok); ?>" <?php echo (string)$value === (string)$ok ? 'selected' : ''; ?>><?php echo h($ol); ?></option>
													<?php endforeach; ?>
												</select>
											<?php else: ?>
												<input type="<?php echo in_array($key, ['quantity', 'rate', 'gst_percent'], true) ? 'number' : 'text'; ?>"
													name="lines[<?php echo (int)$index; ?>][<?php echo h($key); ?>]"
													value="<?php echo h((string)$value); ?>"
													<?php echo $key === 'gst_percent' && $value === '' ? 'placeholder="18"' : ''; ?>
													<?php echo in_array($key, ['quantity', 'rate', 'gst_percent'], true) ? 'step="0.01" min="0"' : ''; ?>
													<?php echo $editable ? '' : 'readonly'; ?>>
											<?php endif; ?>
										</td>
									<?php endforeach; ?>
									<?php if ($editable): ?><td><button type="button" class="btn btn-mini erp-remove">Remove</button></td><?php endif; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php if ($priced): ?>
					<p class="kx-lead">Taxable <?php echo h(number_format((float)$totals['taxable'], 2)); ?> &middot; GST <?php echo h(number_format((float)$totals['gst'], 2)); ?> &middot; Total <?php echo h(number_format((float)$totals['total'], 2)); ?></p>
				<?php endif; ?>
				<div class="kx-actions">
					<?php if ($editable): ?><button class="kx-btn kx-btn-primary" type="submit">Save</button><?php endif; ?>
					<?php if ($editable): ?><button class="kx-btn kx-btn-ghost erp-add" type="button">Add line</button><?php endif; ?>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath . h($dashed($listAction)); ?>">Back</a>
					<?php foreach ($extraLinks ?? [] as $link): ?>
						<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath . h($link['url']); ?>"><?php echo h($link['label']); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
		<?php if (!empty($canApprove) && !empty($showRequestApprove)): ?>
			<div class="kx-card">
				<h1>Approval</h1>
				<p class="kx-lead">Approving lets purchasing record a quotation for this request.</p>
				<?php echo $this->Form->create(null, ['url' => ['action' => 'approveRequest', $entity->id], 'class' => 'kx-actions']); ?>
					<button class="kx-btn kx-btn-primary" name="decision" value="approved" type="submit">Approve</button>
					<button class="kx-btn kx-btn-ghost" name="decision" value="rejected" type="submit">Reject</button>
				<?php echo $this->Form->end(); ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($canApproveSales) && !empty($showSalesApprove)): ?>
			<div class="kx-card">
				<h1>Approval</h1>
				<p class="kx-lead">Finished goods can be delivered only after the sales order is approved.</p>
				<?php echo $this->Form->create(null, ['url' => ['action' => 'approveSalesOrder', $entity->id], 'class' => 'kx-actions']); ?>
					<button class="kx-btn kx-btn-primary" name="decision" value="approved" type="submit">Approve</button>
				<?php echo $this->Form->end(); ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($canApprove) && !empty($showOrderApprove)): ?>
			<div class="kx-card">
				<h1>Approval</h1>
				<p class="kx-lead">Goods can be received only after the order is approved.</p>
				<?php echo $this->Form->create(null, ['url' => ['action' => 'approveOrder', $entity->id], 'class' => 'kx-actions']); ?>
					<button class="kx-btn kx-btn-primary" name="decision" value="approved" type="submit">Approve</button>
					<button class="kx-btn kx-btn-ghost" name="decision" value="cancelled" type="submit">Reject</button>
				<?php echo $this->Form->end(); ?>
			</div>
		<?php endif; ?>
		<?php if (!empty($canEdit) && !empty($showCancel) && empty($showOrderApprove)): ?>
			<div class="kx-card">
				<?php echo $this->Form->create(null, ['url' => ['action' => 'cancelOrder', $entity->id]]); ?>
					<button class="kx-btn kx-btn-ghost" type="submit" onclick="return confirm('Cancel this purchase order?');">Cancel order</button>
				<?php echo $this->Form->end(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php if ($editable): ?>
<script>
document.querySelector('.erp-add').addEventListener('click', function () {
	var body = document.querySelector('.erp-lines tbody');
	var row = body.querySelector('tr');
	var copy = row.cloneNode(true);
	var index = body.querySelectorAll('tr').length;
	copy.querySelectorAll('input, select').forEach(function (input) {
		input.name = input.name.replace(/lines\[\d+\]/, 'lines[' + index + ']');
		if (input.tagName === 'SELECT') {
			input.selectedIndex = 0;
		} else {
			input.value = '';
		}
	});
	body.appendChild(copy);
});
document.querySelector('.erp-lines').addEventListener('click', function (event) {
	if (!event.target.classList.contains('erp-remove')) {
		return;
	}
	var body = document.querySelector('.erp-lines tbody');
	if (body.querySelectorAll('tr').length === 1) {
		event.target.closest('tr').querySelectorAll('input').forEach(function (input) { input.value = ''; });
		event.target.closest('tr').querySelectorAll('select').forEach(function (input) { input.selectedIndex = 0; });
		return;
	}
	event.target.closest('tr').remove();
});
document.querySelector('.erp-lines').addEventListener('change', function (event) {
	if (event.target.name.indexOf('[iso_material_id]') === -1) {
		return;
	}
	var row = event.target.closest('tr');
	var item = row.querySelector('input[name*="[item_name]"]');
	var label = event.target.options[event.target.selectedIndex].text;
	if (item && item.value === '' && event.target.value !== '') {
		item.value = label;
	}
});
</script>
<?php endif; ?>
