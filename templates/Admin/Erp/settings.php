<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1>Company settings</h1>
				<p class="kx-lead">These details appear on orders and invoices. The state decides whether GST is split into CGST and SGST or charged as IGST.</p>
				<div class="kx-grid">
					<?php foreach ($fields as $key => $field): ?>
						<?php $current = (string)($settingValues[$key] ?? ''); ?>
						<label class="kx-field<?php echo ($field['type'] ?? '') === 'textarea' ? ' kx-span' : ''; ?>">
							<span><?php echo h($field['label']); ?><?php if (!empty($field['required'])): ?><span class="kx-req">*</span><?php endif; ?></span>
							<?php if (($field['type'] ?? '') === 'textarea'): ?>
								<textarea name="<?php echo h($key); ?>" rows="3" placeholder="<?php echo h($field['placeholder'] ?? ''); ?>"><?php echo h($current); ?></textarea>
							<?php else: ?>
								<input type="text" name="<?php echo h($key); ?>" value="<?php echo h($current); ?>" placeholder="<?php echo h($field['placeholder'] ?? ''); ?>">
							<?php endif; ?>
						</label>
					<?php endforeach; ?>
				</div>
				<div class="kx-actions">
					<?php if (!empty($canEdit)): ?><button class="kx-btn kx-btn-primary" type="submit">Save</button><?php endif; ?>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
