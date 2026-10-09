<div class="page-content">
	<div class="page-header">
		<h1>ISO 9001 <small>quality records</small></h1>
	</div>
	<div class="row">
		<?php foreach ($cards as $card): ?>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box" style="background-color: <?php echo h($card['color']); ?>; color:#fff; margin-bottom:16px;">
					<div class="inner" style="padding:10px;">
						<h3>#<?php echo (int)$card['count']; ?></h3>
						<p><?php echo h($card['label']); ?></p>
					</div>
					<a href="<?php echo SITEURL . 'admin/iso/' . $card['url']; ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<p>Purchase receives material, QA checks it, production records the batch, and a failed check stays as a non-conformance with a corrective action.</p>
		</div>
	</div>
</div>
