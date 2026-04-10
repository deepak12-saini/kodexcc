	<style>
		.btn{ margin-bottom:2px;} 
	</style>
	<div class="page-content">
		<div class="page-header">
			<h1>Kodex orders List </h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					
					<th><?php echo $this->Paginator->sort('contact_name','User'); ?></th>					
					<th><?php echo $this->Paginator->sort('contact_phone'); ?></th>		
					<th><?php echo $this->Paginator->sort('address'); ?></th>		
					<th><?php echo $this->Paginator->sort('points'); ?></th>					
					<th><?php echo $this->Paginator->sort('redeem'); ?></th>					
					<th><?php echo $this->Paginator->sort('created','Last Update'); ?></th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					<?php
					$k = 1;
					foreach ($DuroOrderArr as $DuroOrderArrs): ?>
					<tr>
						
						<td><?php echo $DuroOrderArrs['RewardPoint']['contact_name']; ?></td>
						<td><?php echo $DuroOrderArrs['RewardPoint']['contact_phone']; ?>&nbsp;</td>						
						<td><?php echo $DuroOrderArrs['RewardPoint']['address']; ?>&nbsp;</td>						
						<td><?php echo h($DuroOrderArrs['RewardPoint']['points']); ?>&nbsp;</td>
						<td><?php echo h($DuroOrderArrs['RewardPoint']['redeem']); ?>&nbsp;</td>
									
						<td> <?php echo date('d-M-Y',strtotime($DuroOrderArrs['RewardPoint']['created'])); ?></td>
						<td></td>
									
					</tr>
					<?php $k++; endforeach; ?>
				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php	echo $this->Paginator->counter(array(
'format' => __('showing {:current} records out of {:count} entries')));?>	</div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
			<ul class="pagination">
				<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
				echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));?></li>
				
				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));?></li>
				
			</ul>
			</div>
		</div>
	</div>	
</div>		
