<style>
	.btn{ margin-bottom:2px;}
</style>
<div class="page-content">
	<div class="page-header">
		<h1>Kodex Reward Points</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('RewardPoint.contact_name', 'User'); ?></th>
					<th><?php echo $this->Paginator->sort('RewardPoint.contact_phone', 'contact_phone'); ?></th>
					<th><?php echo $this->Paginator->sort('RewardPoint.address', 'address'); ?></th>
					<th><?php echo $this->Paginator->sort('RewardPoint.points', 'points'); ?></th>
					<th><?php echo $this->Paginator->sort('RewardPoint.redeem', 'redeem'); ?></th>
					<th><?php echo $this->Paginator->sort('RewardPoint.created', 'Last Update'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($DuroOrderArr as $row): $r = $row['RewardPoint']; ?>
					<tr>
						<td><?php echo h($r['contact_name'] ?? ''); ?></td>
						<td><?php echo h($r['contact_phone'] ?? ''); ?></td>
						<td><?php echo h($r['address'] ?? ''); ?></td>
						<td><?php echo h($r['points'] ?? ''); ?></td>
						<td><?php echo h($r['redeem'] ?? ''); ?></td>
						<td><?php echo !empty($r['created']) ? h(date('d-M-Y', strtotime((string)$r['created']))) : ''; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php echo $this->Paginator->counter(
				__('Showing {{current}} records out of {{count}} entries')
			); ?></div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
			<ul class="pagination">
				<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
				echo $this->Paginator->prev('< ' . __('previous'), ['class' => 'prev disabled']);?></li>
				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >', ['class' => 'next']);?></li>
			</ul>
			</div>
		</div>
	</div>
</div>

