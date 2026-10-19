<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet kx-overview">
		<div class="kx-card">
			<div class="kx-overview-head">
				<div>
					<h1>Purchasing</h1>
					<p class="kx-lead">Raise a request, record the supplier price, approve the order, then receive the goods. Receiving creates the ISO incoming-material record for QA.</p>
				</div>
				<span class="kx-role"><?php echo h($erpRoleLabel); ?></span>
			</div>
			<div class="kx-stats">
				<?php foreach ($cards as $card): ?>
					<a class="kx-stat kx-tone-<?php echo h($card['tone']); ?>" href="<?php echo SITEURL . 'admin/erp/' . h($card['url']); ?>">
						<span class="kx-stat-icon"><i class="fa <?php echo h($card['icon']); ?>"></i></span>
						<span class="kx-stat-copy">
							<strong><?php echo (int)$card['count']; ?></strong>
							<span><?php echo h($card['label']); ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
			<h2 class="kx-section">Steps</h2>
			<div class="kx-modules">
				<?php foreach ($modules as $module): ?>
					<a class="kx-module" href="<?php echo SITEURL . 'admin/erp/' . h($module['url']); ?>">
						<span class="kx-module-icon"><i class="fa <?php echo h($module['icon']); ?>"></i></span>
						<span>
							<strong><?php echo h($module['label']); ?></strong>
							<small><?php echo h($module['hint']); ?></small>
						</span>
						<i class="fa fa-angle-right kx-module-go"></i>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
