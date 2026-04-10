	<div class="page-content">
		<div class="page-header">
			<h1>QC Result</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.id', __('ID')); ?></th>
				<th><?php echo $this->Paginator->sort('NappUser.name', __('Name')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.employee_name', __('Employee')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.batch_number', __('Batch #')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.product_name', __('Product')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.quantity', __('Qty')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.no_of_pails', __('Pails')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.date', __('Date')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.date_completed', __('Completed')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchCountSheet.signature', __('Signature')); ?></th>
                  <th><?php echo $this->Paginator->sort('BatchCountSheet.created', __('Created')); ?></th>

				</tr>
				</thead>
				<tbody>
						<?php foreach ($BatchRegisterArr as $BatchRegisterArrs): ?>
					<tr>
					<td>#<?php echo h($BatchRegisterArrs['BatchCountSheet']['id']); ?>&nbsp;</td>
					<td><?php echo h(($BatchRegisterArrs['NappUser']['name'] ?? '') . ' ' . ($BatchRegisterArrs['NappUser']['lname'] ?? '')); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchCountSheet']['employee_name']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchCountSheet']['batch_number']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchCountSheet']['product_name']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchCountSheet']['quantity']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchCountSheet']['no_of_pails']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchCountSheet']['date']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchCountSheet']['date_completed']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchCountSheet']['signature']); ?>&nbsp;</td>									
                    <td> <?php echo !empty($BatchRegisterArrs['BatchCountSheet']['created']) ? h(date('d-M-Y', strtotime((string)$BatchRegisterArrs['BatchCountSheet']['created']))) : ''; ?></td>
					
					</tr>
					<?php endforeach; ?>

				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php echo h($this->Paginator->counter(__('showing {{current}} records out of {{count}} entries'))); ?></div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
			<ul class="pagination">
				<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
				echo $this->Paginator->prev('< ' . __('previous')); ?></li>
				
				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >'); ?></li>
				
			</ul>
			</div>
		</div>
	</div>	
</div>
