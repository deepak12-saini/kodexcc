<div class="page-content">
	<div class="page-header">
		<h1>Reward Product
			<a href="<?php echo SITEURL; ?>admin/reward-products/add" class="btn btn-info btn-xs top-button">Add New</a>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="example" >
				<thead>
				<tr>
					<th>S.no</th>
					<th>Product Name</th>
					<th>Size</th>
					<th>Applicator Points</th>
					<th>Dealer Points</th>
					<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					foreach ($RewardProduct as $RewardProducts) :
					?>
					<tr>
					<td>#<?php echo $i; ?>&nbsp;</td>
					<td><?php echo h($RewardProducts['Product']['title'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($RewardProducts['RewardProduct']['size'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($RewardProducts['RewardProduct']['applicator_points'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($RewardProducts['RewardProduct']['dealer_point'] ?? ($RewardProducts['RewardProduct']['dealer_points'] ?? '')); ?>&nbsp;</td>

					 <td class="actions">
						<a href="<?php echo SITEURL?>admin/reward-products/edit/<?php echo h($RewardProducts['RewardProduct']['id']); ?>" class="btn btn-mini btn-info"><?php echo __('Edit'); ?></a>
						</td>
					</tr>
					<?php $i++; endforeach; ?>

				</tbody>
			</table>
			</div>
		</div>
	</div>

</div>
