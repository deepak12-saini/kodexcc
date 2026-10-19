<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1>Bill of materials <?php echo h((string)($entity->bom_no ?? '')); ?></h1>
				<p class="kx-lead">Quantities are per one finished unit.</p>
				<div class="kx-grid">
					<label class="kx-field"><span>Finished good<span class="kx-req">*</span></span>
						<select name="item_id">
							<option value="">Select</option>
							<?php foreach ($finished as $id => $label): ?>
								<option value="<?php echo h((string)$id); ?>" <?php echo (string)($entity->item_id ?? '') === (string)$id ? 'selected' : ''; ?>><?php echo h($label); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<label class="kx-field kx-span"><span>Notes</span><textarea name="notes" rows="2"><?php echo h((string)($entity->notes ?? '')); ?></textarea></label>
				</div>
				<table class="kx-table">
					<thead><tr><th>Raw material</th><th>Qty per unit</th></tr></thead>
					<tbody>
						<?php foreach (array_values($lines) as $index => $line): ?>
							<?php $item = is_array($line) ? ($line['item_id'] ?? '') : ($line->item_id ?? ''); ?>
							<?php $qty = is_array($line) ? ($line['quantity'] ?? '') : ($line->quantity ?? ''); ?>
							<tr>
								<td><select name="lines[<?php echo (int)$index; ?>][item_id]"><option value="">Select</option><?php foreach ($raw as $id => $label): ?><option value="<?php echo h((string)$id); ?>" <?php echo (string)$item === (string)$id ? 'selected' : ''; ?>><?php echo h($label); ?></option><?php endforeach; ?></select></td>
								<td><input type="number" step="0.001" min="0" name="lines[<?php echo (int)$index; ?>][quantity]" value="<?php echo h((string)$qty); ?>"></td>
							</tr>
						<?php endforeach; ?>
						<?php $next = count($lines); ?>
						<tr>
							<td><select name="lines[<?php echo (int)$next; ?>][item_id]"><option value="">Select</option><?php foreach ($raw as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select></td>
							<td><input type="number" step="0.001" min="0" name="lines[<?php echo (int)$next; ?>][quantity]"></td>
						</tr>
					</tbody>
				</table>
				<div class="kx-actions">
					<?php if (!empty($canEdit)): ?><button class="kx-btn kx-btn-primary" type="submit">Save</button><?php endif; ?>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/production">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
