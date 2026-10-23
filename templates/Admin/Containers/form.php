<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1><?php echo h($pageTitle); ?></h1>
				<p class="kx-lead">Record the container when it leaves the factory. The timer counts whole days from that date.</p>
				<?php
				$left = \App\Utility\ContainerTransit::date($entity->dispatched_on ?? '');
				?>
				<div class="kx-grid">
					<label class="kx-field"><span>Container number<span class="kx-req">*</span></span><input name="container_no" required value="<?php echo h($entity->container_no ?? ''); ?>" placeholder="For example MSKU1234567"></label>
					<label class="kx-field"><span>Customer</span><input name="customer" value="<?php echo h($entity->customer ?? ''); ?>" placeholder="Who the container is for"></label>
					<label class="kx-field"><span>Destination<span class="kx-req">*</span></span><input name="destination" required value="<?php echo h($entity->destination ?? ''); ?>" placeholder="City or country"></label>
					<label class="kx-field"><span>Left factory<span class="kx-req">*</span></span><input name="dispatched_on" type="date" required value="<?php echo h($left); ?>"></label>
					<label class="kx-field"><span>Shipping line</span><input name="shipping_line" value="<?php echo h($entity->shipping_line ?? ''); ?>" placeholder="Line or vessel"></label>
					<label class="kx-field"><span>Booking or BL number</span><input name="reference_no" value="<?php echo h($entity->reference_no ?? ''); ?>"></label>
					<label class="kx-field kx-span"><span>Notes</span><textarea name="notes" rows="3"><?php echo h($entity->notes ?? ''); ?></textarea></label>
				</div>
				<div class="kx-actions">
					<button class="kx-btn kx-btn-primary" type="submit">Save</button>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/containers">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
