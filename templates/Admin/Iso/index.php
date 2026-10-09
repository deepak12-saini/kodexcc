<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet kx-overview">
		<div class="kx-card">
			<div class="kx-overview-head">
				<div>
					<h1>ISO 9001</h1>
					<p class="kx-lead">Quality records for your role. Open a count to see what is waiting, or pick a record below.</p>
				</div>
				<span class="kx-role"><?php echo h($isoRoleLabel); ?></span>
			</div>
			<?php if ($cards !== []): ?>
				<div class="kx-stats">
					<?php foreach ($cards as $card): ?>
						<a class="kx-stat kx-tone-<?php echo h($card['tone']); ?>" href="<?php echo SITEURL . 'admin/iso/' . h($card['url']); ?>">
							<span class="kx-stat-icon"><i class="fa <?php echo h($card['icon']); ?>"></i></span>
							<span class="kx-stat-copy">
								<strong><?php echo (int)$card['count']; ?></strong>
								<span><?php echo h($card['label']); ?></span>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<h2 class="kx-section">Records</h2>
			<div class="kx-modules">
				<?php foreach ($modules as $module): ?>
					<a class="kx-module" href="<?php echo SITEURL . 'admin/iso/' . h($module['url']); ?>">
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
