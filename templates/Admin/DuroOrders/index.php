<style>
	.btn{ margin-bottom:2px;}
</style>
<div class="page-content">
	<div class="page-header">
		<h1>Kodex orders List</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('DuroOrder.customer_order_no', 'customer_order_no'); ?></th>
					<th><?php echo $this->Paginator->sort('DuroOrder.date_of_order', 'date_of_order'); ?></th>
					<th>Added By</th>
					<th><?php echo $this->Paginator->sort('DuroOrder.account_name', 'account_name'); ?></th>
					<th><?php echo $this->Paginator->sort('DuroOrder.contact_name', 'contact_name'); ?></th>
					<th><?php echo $this->Paginator->sort('DuroOrder.contact_phone', 'contact_phone'); ?></th>
					<th><?php echo $this->Paginator->sort('DuroOrder.deliver_address', 'deliver_address'); ?></th>
					<th><?php echo $this->Paginator->sort('DuroOrder.status', 'status'); ?></th>
					<th><?php echo $this->Paginator->sort('DuroOrder.created', 'created'); ?></th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($DuroOrderArr as $row): $d = $row['DuroOrder']; $u = $row['NappUser'] ?? []; ?>
					<tr>
						<td><?php echo h($d['customer_order_no'] ?? ''); ?></td>
						<td><?php echo !empty($d['date_of_order']) ? h(date('d-M-Y', strtotime((string)$d['date_of_order']))) : ''; ?></td>
						<td><?php echo h(trim((string)($u['name'] ?? '') . ' ' . (string)($u['lname'] ?? ''))); ?></td>
						<td><?php echo h($d['account_name'] ?? ''); ?></td>
						<td><?php echo h($d['contact_name'] ?? ''); ?></td>
						<td><?php echo h($d['contact_phone'] ?? ''); ?></td>
						<td><?php echo h($d['deliver_address'] ?? ''); ?></td>
						<td>
							<?php
							$s = (int)($d['status'] ?? 0);
							echo match ($s) {
								1 => '<span class="label label-info">Accepted</span>',
								2 => '<span class="label label-success">Order Ready</span>',
								3 => '<span class="label label-success">Dispatch</span>',
								4 => '<span class="label label-success">Completed</span>',
								5 => '<span class="label label-danger">Canceled</span>',
								6 => '<span class="label label-warning">Order Delivered</span>',
								default => '<span class="label label-primary">New Order</span>',
							};
							?>
						</td>
						<td><?php echo !empty($d['created']) ? h(date('d-M-Y', strtotime((string)$d['created']))) : ''; ?></td>
						<td><a href="#null" class="btn btn-mini btn-info disabled">Edit</a></td>
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

