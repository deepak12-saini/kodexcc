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
.kx-admin-brand img {
	display: block;
	height: 4.25rem;
	width: auto;
	max-width: 16rem;
	object-fit: contain;
}
.kx-admin-body {
	padding: 1.6rem 1.7rem 1.7rem;
}
.kx-admin-body h1 {
	margin: 0;
	font-size: 1.7rem;
	font-weight: 700;
	color: #111827;
}
.kx-admin-lead {
	margin: 0.2rem 0 1.1rem;
	color: #6b7280;
	font-size: 0.95rem;
}
.kx-admin-body label {
	display: block;
	margin: 0 0 0.5rem;
	font-size: 1.35rem !important;
	line-height: 1.3;
	color: #111827 !important;
	font-weight: 700 !important;
}
.kx-admin-body input[type="text"],
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
.kx-admin-body input:focus {
	border-color: #0f766e;
	outline: none;
}
.kx-admin-body .ValidationErrors {
	display: block;
	margin: -0.7rem 0 0.8rem;
	padding: 0;
	color: #b42318;
	font-size: 0.82rem;
	font-style: normal;
}
.kx-admin-body button,
.kx-admin-body input[type="submit"] {
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
.kx-admin-body button:hover,
.kx-admin-body input[type="submit"]:hover {
	background: #0d655e;
}
.kx-admin-body .message,
.kx-admin-body .alert {
	margin-bottom: 1rem;
}
</style>
<div class="kx-admin-login">
	<div class="kx-admin-card">
		<div class="kx-admin-brand">
			<img src="<?php echo SITEURL; ?>img/kodex-logo.png" alt="Kodex">
		</div>
		<div class="kx-admin-body">
			<h1>Admin Panel</h1>
			<p class="kx-admin-lead">Sign in</p>
			<?php echo $this->Form->create(null, [
				'url' => ['prefix' => 'Admin', 'controller' => 'Users', 'action' => 'login'],
				'name' => 'loginForm',
				'id' => 'loginForm',
			]); ?>
			<?php echo $this->Flash->render(); ?>
			<label for="username">Username</label>
			<?php echo $this->Form->text('username', [
				'name' => 'User[username]',
				'label' => false,
				'placeholder' => 'Username',
				'id' => 'username',
				'required' => true,
				'autocomplete' => 'username',
			]); ?>
			<label for="password">Password</label>
			<?php echo $this->Form->password('password', [
				'name' => 'User[password]',
				'label' => false,
				'placeholder' => 'Password',
				'id' => 'password',
				'required' => true,
				'autocomplete' => 'current-password',
			]); ?>
			<?php echo $this->Form->submit('Login'); ?>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(function () {
	$("#username").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter username"
	});
	$("#password").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Please enter password"
	});
});
</script>
