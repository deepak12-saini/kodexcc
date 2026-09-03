<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo h($title_for_layout ?? 'KodexCC HRMS'); ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="icon" href="<?php echo SITEURL; ?>favicon.png" type="image/png">
	<link rel="shortcut icon" href="<?php echo SITEURL; ?>favicon.png" type="image/png">
	<link rel="stylesheet" href="<?php echo SITEURL; ?>css/hrms.css?v=3">
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="hrms-body">
	<aside class="hrms-sidebar">
		<?php echo $this->element('hrms_sidebar'); ?>
	</aside>
	<main class="hrms-main">
		<header class="hrms-topbar">
			<div>
				<strong><?php echo h($pageTitle ?? 'Dashboard'); ?></strong>
			</div>
			<div class="hrms-topbar-right">
				<span class="hrms-role"><?php echo h(strtoupper((string)($hrRole ?? ''))); ?></span>
				<span><?php echo h($hrUser['name'] ?? ''); ?></span>
				<a href="<?php echo SITEURL; ?>hrms/users/logout">Logout</a>
			</div>
		</header>
		<div class="hrms-content">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</main>
	<script>
	(function () {
		var token = <?php echo json_encode((string)$this->request->getAttribute('csrfToken')); ?>;
		if (!token) return;
		document.querySelectorAll('form').forEach(function (form) {
			var method = (form.getAttribute('method') || 'get').toLowerCase();
			if (method !== 'post') return;
			if (form.querySelector('input[name="_csrfToken"]')) return;
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = '_csrfToken';
			input.value = token;
			form.appendChild(input);
		});
	})();
	</script>
</body>
</html>
