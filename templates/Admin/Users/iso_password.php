<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form']); ?>
			<div class="kx-card">
				<h1>Change password</h1>
				<p class="kx-lead">Signed in as <?php echo h($username); ?>. This updates only your ISO login.</p>
				<div class="kx-grid">
					<label class="kx-field kx-span"><span>Current password<span class="kx-req">*</span></span>
						<input type="password" name="current_password" autocomplete="current-password" required placeholder="The password you use now">
					</label>
					<label class="kx-field"><span>New password<span class="kx-req">*</span></span>
						<input type="password" name="new_password" autocomplete="new-password" required minlength="6" placeholder="At least 6 characters">
					</label>
					<label class="kx-field"><span>Confirm new password<span class="kx-req">*</span></span>
						<input type="password" name="confirm_password" autocomplete="new-password" required minlength="6" placeholder="Type the new password again">
					</label>
				</div>
				<div class="kx-actions">
					<button class="kx-btn kx-btn-primary" type="submit">Save password</button>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/iso">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
