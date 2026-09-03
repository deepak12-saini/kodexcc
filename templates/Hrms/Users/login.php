<div class="hrms-login-form">
	<h1>Sign in</h1>
	<p>Confidential employee &amp; attendance portal</p>
	<?php echo $this->Form->create(null, [
		'url' => ['prefix' => 'Hrms', 'controller' => 'Users', 'action' => 'login'],
	]); ?>
		<label>Username</label>
		<input type="text" name="username" required autofocus autocomplete="username">
		<label>Password</label>
		<input type="password" name="password" required autocomplete="current-password">
		<button type="submit" class="hrms-btn hrms-btn-primary">Login</button>
	<?php echo $this->Form->end(); ?>
</div>
