<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<div class="kx-card">
			<?php if (empty($entity->id)): ?>
				<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
					<h1>Production order</h1>
					<div class="kx-grid">
						<label class="kx-field"><span>Finished good<span class="kx-req">*</span></span>
							<select name="item_id"><option value="">Select</option><?php foreach ($finished as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
						</label>
						<label class="kx-field"><span>Bill of materials<span class="kx-req">*</span></span>
							<select name="bom_id"><option value="">Select</option><?php foreach ($boms as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
						</label>
						<label class="kx-field"><span>Quantity<span class="kx-req">*</span></span><input type="number" step="0.001" min="0" name="planned_qty"></label>
						<label class="kx-field kx-span"><span>Notes</span><textarea name="notes" rows="2"></textarea></label>
					</div>
					<div class="kx-actions">
						<button class="kx-btn kx-btn-primary" type="submit">Release</button>
						<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/production">Back</a>
					</div>
				<?php echo $this->Form->end(); ?>
			<?php else: ?>
				<h1><?php echo h($entity->prod_no); ?></h1>
				<p class="kx-lead"><?php echo h($entity->status); ?> · planned <?php echo h((string)$entity->planned_qty); ?><?php if ($entity->iso_batch_id): ?> · <a href="<?php echo SITEURL; ?>admin/iso/batch-form/<?php echo (int)$entity->iso_batch_id; ?>">ISO batch</a><?php endif; ?></p>
				<?php if ($requirements !== [] && $issues === [] && $entity->status === 'released' && !empty($canEdit)): ?>
					<?php echo $this->Form->create(null, ['url' => ['action' => 'issueProduction', $entity->id], 'class' => 'kx-form']); ?>
						<table class="kx-table">
							<thead><tr><th>Raw material</th><th>Needed</th><th>Godown</th><th>Lot</th></tr></thead>
							<tbody>
								<?php foreach ($requirements as $row): ?>
									<tr>
										<td><?php echo h($row['name']); ?></td>
										<td><?php echo h((string)$row['need']); ?></td>
										<td><select name="lines[<?php echo (int)$row['item_id']; ?>][godown_id]"><?php foreach ($godowns as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select></td>
										<td><input type="text" name="lines[<?php echo (int)$row['item_id']; ?>][lot_no]"></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<div class="kx-actions"><button class="kx-btn kx-btn-primary" type="submit">Issue material</button></div>
					<?php echo $this->Form->end(); ?>
				<?php endif; ?>
				<?php if ($issues !== [] && $entity->status === 'released' && !empty($canEdit)): ?>
					<?php echo $this->Form->create(null, ['url' => ['action' => 'completeProduction', $entity->id], 'class' => 'kx-form']); ?>
						<div class="kx-grid">
							<label class="kx-field"><span>Output qty<span class="kx-req">*</span></span><input type="number" step="0.001" name="output_qty" value="<?php echo h((string)$entity->planned_qty); ?>"></label>
							<label class="kx-field"><span>Godown<span class="kx-req">*</span></span>
								<select name="output_godown_id"><?php foreach ($godowns as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
							</label>
							<label class="kx-field"><span>Output lot</span><input type="text" name="output_lot"></label>
						</div>
						<div class="kx-actions"><button class="kx-btn kx-btn-primary" type="submit">Receive finished goods</button></div>
					<?php echo $this->Form->end(); ?>
				<?php endif; ?>
				<div class="kx-actions"><a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/production">Back</a></div>
			<?php endif; ?>
		</div>
	</div>
</div>
