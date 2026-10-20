<style>
body.login-layout {
	background:
		radial-gradient(ellipse 70% 50% at 85% 10%, rgba(13, 148, 136, 0.28), transparent 55%),
		radial-gradient(ellipse 50% 40% at 10% 90%, rgba(26, 95, 122, 0.22), transparent 50%),
		linear-gradient(160deg, #0b1824, #122636 55%, #0e1f2c) !important;
	min-height: 100vh;
}
body.login-layout .main-container,
body.login-layout .main-content {
	background: transparent !important;
	min-height: 100vh;
}
.kx-admin-login {
	min-height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 1.5rem;
	font-family: "Segoe UI", system-ui, sans-serif;
}
.kx-admin-card {
	width: min(420px, 100%);
	background: #fff;
	border-radius: 14px;
	box-shadow: 0 20px 50px rgba(0, 0, 0, 0.28);
	overflow: hidden;
}
.kx-admin-brand {
	display: flex;
	justify-content: center;
	align-items: center;
	padding: 1.15rem 1.25rem;
	background: #07111f;
}
.kx-admin-brand img { display: block; height: 4.25rem; width: auto; max-width: 16rem; object-fit: contain; }
.kx-admin-body { padding: 1.6rem 1.7rem 1.7rem; }
.kx-admin-body h1 { margin: 0 0 0.8rem; font-size: 1.7rem; font-weight: 700; color: #111827; }
.kx-admin-body label { display: block; margin: 0 0 0.5rem; font-size: 1.35rem !important; line-height: 1.3; color: #111827 !important; font-weight: 700 !important; }
.kx-admin-body input[type="email"],
.kx-admin-body input[type="password"] {
	width: 100%;
	height: 52px;
	margin: 0 0 1.1rem;
	padding: 0 0.95rem;
	border: 1px solid #d1d5db;
	border-radius: 8px;
	box-shadow: none;
	font-size: 1.1rem;
}
.kx-admin-body button {
	width: 100%;
	height: 46px;
	margin-top: 0.25rem;
	border: 0;
	border-radius: 8px;
	background: #0f766e;
	color: #fff;
	font-size: 1rem;
	font-weight: 600;
}
.kx-admin-reset-note { margin: 0 0 1rem; color: #6b7280; font-size: 0.95rem; }
.kx-admin-back {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 46px;
	margin-top: 0.75rem;
	border: 2px solid #0f766e;
	border-radius: 8px;
	color: #0f766e !important;
	font-size: 1.05rem !important;
	font-weight: 700 !important;
	text-decoration: none !important;
}
.kx-admin-body .message, .kx-admin-body .alert { margin-bottom: 1rem; }
</style>
<div class="kx-admin-login">
	<div class="kx-admin-card">
		<div class="kx-admin-brand">
			<img src="<?php echo SITEURL; ?>img/kodex-logo.png" alt="Kodex">
		</div>
		<div class="kx-admin-body">
			<h1>Reset password</h1>
			<?php echo $this->Flash->render(); ?>
			<?php if (!empty($setPassword)): ?>
				<p class="kx-admin-reset-note">Choose a password for <strong><?php echo h($username); ?></strong>. Use at least 6 characters.</p>
				<?php echo $this->Form->create(null, ['url' => ['action' => 'staffReset', '?' => ['token' => $token]]]); ?>
					<input type="hidden" name="token" value="<?php echo h($token); ?>">
					<label for="new_password">New password</label>
					<input id="new_password" name="new_password" type="password" required minlength="6" autocomplete="new-password">
					<label for="confirm_password">Confirm password</label>
					<input id="confirm_password" name="confirm_password" type="password" required minlength="6" autocomplete="new-password">
					<button type="submit">Save password</button>
				<?php echo $this->Form->end(); ?>
			<?php else: ?>
				<p class="kx-admin-reset-note">Enter the email on your staff profile. We will send a link so you can set your own password.</p>
				<?php echo $this->Form->create(null); ?>
					<label for="email">Email</label>
					<input id="email" name="email" type="email" required autocomplete="email">
					<button type="submit">Send reset link</button>
				<?php echo $this->Form->end(); ?>
			<?php endif; ?>
			<a class="kx-admin-back" href="<?php echo SITEURL; ?>admin">Back to sign in</a>
		</div>
	</div>
</div>
