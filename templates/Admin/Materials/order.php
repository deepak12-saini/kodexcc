<div class="page-content">
		<div class="page-header">
			<h1>Material Order Request
			<?php echo $this->Form->create(null, ['type' => 'post', 'style' => 'float:right;', 'class' => 'form-inline']); ?>
				<?php echo $this->Form->control('search', [
					'type' => 'search',
					'label' => false,
					'placeholder' => 'Search By Requested id',
					'value' => $search ?? '',
					'style' => 'width:220px;display:inline-block;',
				]); ?>
				<?php echo $this->Form->submit(__('Search'), ['class' => 'btn btn-xs btn-primary']); ?>
			<?php echo $this->Form->end(); ?>
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>				
					<th><?php echo $this->Paginator->sort('MaterialOrder.order_id', __('Order Number')); ?></th>					
					<th><?php echo $this->Paginator->sort('MaterialOrder.material_type', __('Material type')); ?></th>
					<th><?php echo $this->Paginator->sort('MaterialOrder.weight', __('Weight/dimensions')); ?></th>			
					<th><?php echo $this->Paginator->sort('MaterialOrder.quantity', __('Quantity Required')); ?></th>									
					<th><?php echo $this->Paginator->sort('MaterialOrder.name', __('Request By')); ?></th>	
					<th><?php echo $this->Paginator->sort('MaterialOrder.status', __('Status')); ?></th>		
					<th><?php echo $this->Paginator->sort('MaterialOrder.lastmodification', __('Last Update')); ?></th>
					<th><?php echo $this->Paginator->sort('MaterialOrder.created', __('Order Requested')); ?></th>
					<th></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach (($MaterialOrder ?? []) as $MaterialOrders): ?>
					<tr>								
					<td>#<?php echo h((string)($MaterialOrders['MaterialOrder']['order_id'] ?? '')); ?>&nbsp;</td>
					<td><?php echo h((string)($MaterialOrders['MaterialOrder']['material_type'] ?? '')); ?>&nbsp;</td>
					<td><?php echo h((string)($MaterialOrders['MaterialOrder']['weight'] ?? '')); ?>&nbsp;</td>
					<td><?php echo h((string)($MaterialOrders['MaterialOrder']['quantity'] ?? '')); ?>&nbsp;</td>					
					<td><?php echo h((string)($MaterialOrders['MaterialOrder']['name'] ?? '')); ?>&nbsp;</td>	
					<td>
						<?php $st = (int)($MaterialOrders['MaterialOrder']['status'] ?? 0); ?>
						<?php if ($st === 0) { ?>
							<span class="label label-danger">Pending</span>
						<?php } elseif ($st === 1) { ?>
							<span class="label label-warning">Accepted</span>
						<?php } elseif ($st === 2) { ?>
							<span class="label label-success">Completed</span>
						<?php } ?>
					</td>	
                    <td><?php $lm = $MaterialOrders['MaterialOrder']['lastmodification'] ?? null; echo $lm ? h(date('d-M-Y h:i a', strtotime((string)$lm))) : ''; ?></td>
                    <td><?php $cr = $MaterialOrders['MaterialOrder']['created'] ?? null; echo $cr ? h(date('d-M-Y h:i a', strtotime((string)$cr))) : ''; ?></td>
					<td>
						<?php if ($st === 0) { ?>
							<a href="<?php echo h(SITEURL . 'admin/materials/status/1/' . ($MaterialOrders['MaterialOrder']['id'] ?? '')); ?>" class="btn btn-mini btn-success">Accept</a>
						<?php } elseif ($st === 1) { ?>
							<a href="<?php echo h(SITEURL . 'admin/materials/status/2/' . ($MaterialOrders['MaterialOrder']['id'] ?? '')); ?>" class="btn btn-mini btn-success">Complete</a>
						<?php } ?>	
					</td>
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
				echo $this->Paginator->prev('< ' . __('previous'), ['class' => 'prev disabled']); ?></li>
				
				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >', ['class' => 'next']); ?></li>
				
			</ul>
			</div>
		</div>
	</div>	
</div>
