<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>KodexCC HRMS Login</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="icon" href="<?php echo SITEURL; ?>favicon.png" type="image/png">
	<link rel="shortcut icon" href="<?php echo SITEURL; ?>favicon.png" type="image/png">
	<link rel="stylesheet" href="<?php echo SITEURL; ?>css/hrms.css?v=14">
</head>
<body class="hrms-login-body">
	<div class="hrms-login-card">
		<div class="hrms-login-brand">
			<img class="hrms-login-logo" src="<?php echo SITEURL; ?>img/kodex-logo.png" alt="Kodex">
		</div>
		<?php echo $this->Flash->render(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
</body>
</html>
