	<div class="page-content">
		<div class="page-header">
			<h1>Batches Made List </h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo $this->Paginator->sort('BatchRegister.id', __('ID')); ?></th>
				<th><?php echo $this->Paginator->sort('NappUser.name', __('Name')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.batch_no', __('Batch No')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.date', __('Date')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.product', __('Product')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.apearance', __('Appearance')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.viscosity', __('Viscosity')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.t_degree_c', __('T°C')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.s_g', __('S.G')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.check_test', __('Check test')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.test_by', __('Test by')); ?></th>
				<th><?php echo $this->Paginator->sort('BatchRegister.comments', __('Comments')); ?></th>
                  <th><?php echo $this->Paginator->sort('BatchRegister.created', __('Created')); ?></th>

				</tr>
				</thead>
				<tbody>
						<?php foreach ($BatchRegisterArr as $BatchRegisterArrs): ?>
					<tr>
					<td>#<?php echo h($BatchRegisterArrs['BatchRegister']['id']); ?>&nbsp;</td>
					<td><?php echo h(($BatchRegisterArrs['NappUser']['name'] ?? '') . ' ' . ($BatchRegisterArrs['NappUser']['lname'] ?? '')); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['batch_no']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['date']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['product']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['apearance']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['viscosity']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['t_degree_c']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['s_g']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['check_test']); ?>&nbsp;</td>
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['test_by']); ?>&nbsp;</td>					
					<td><?php echo h($BatchRegisterArrs['BatchRegister']['comments']); ?>&nbsp;</td>					
                    <td> <?php echo !empty($BatchRegisterArrs['BatchRegister']['created']) ? h(date('d-M-Y', strtotime((string)$BatchRegisterArrs['BatchRegister']['created']))) : ''; ?></td>
					
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
