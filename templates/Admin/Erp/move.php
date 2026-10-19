<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1><?php echo $kind === 'transfer' ? 'Stock transfer' : 'Stock adjustment'; ?></h1>
				<p class="kx-lead">Lot can be left blank when the stock has no lot number.</p>
				<div class="kx-grid">
					<label class="kx-field"><span>Item<span class="kx-req">*</span></span>
						<select name="item_id"><?php foreach ($itemOptions as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
					</label>
					<?php if ($kind === 'transfer'): ?>
						<label class="kx-field"><span>From<span class="kx-req">*</span></span>
							<select name="from_godown_id"><?php foreach ($godowns as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
						</label>
						<label class="kx-field"><span>To<span class="kx-req">*</span></span>
							<select name="to_godown_id"><?php foreach ($godowns as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
						</label>
					<?php else: ?>
						<label class="kx-field"><span>Godown<span class="kx-req">*</span></span>
							<select name="godown_id"><?php foreach ($godowns as $id => $label): ?><option value="<?php echo h((string)$id); ?>"><?php echo h($label); ?></option><?php endforeach; ?></select>
						</label>
						<label class="kx-field"><span>Direction<span class="kx-req">*</span></span>
							<select name="direction"><option value="in">In</option><option value="out">Out</option></select>
						</label>
					<?php endif; ?>
					<label class="kx-field"><span>Lot</span><input type="text" name="lot_no"></label>
					<label class="kx-field"><span>Quantity<span class="kx-req">*</span></span><input type="number" step="0.001" min="0" name="quantity"></label>
					<label class="kx-field"><span>Date<span class="kx-req">*</span></span><input type="date" name="moved_on" value="<?php echo date('Y-m-d'); ?>"></label>
					<label class="kx-field kx-span"><span><?php echo $kind === 'transfer' ? 'Notes' : 'Reason'; ?></span><input type="text" name="<?php echo $kind === 'transfer' ? 'notes' : 'reason'; ?>"></label>
				</div>
				<div class="kx-actions">
					<button class="kx-btn kx-btn-primary" type="submit">Post</button>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/inventory">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
