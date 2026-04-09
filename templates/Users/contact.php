	<div class="page-content">
		<div class="page-header">
			<h1>
			All Durotech Employee
				
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('email'); ?></th>			 
				<th><?php echo $this->Paginator->sort('phone'); ?></th>		
				<th><?php echo $this->Paginator->sort('dept_id','Department'); ?></th>				
                <th><?php echo $this->Paginator->sort('designation'); ?></th>
               	 
				<th><?php echo $this->Paginator->sort('status'); ?></th>			
				<th><?php echo $this->Paginator->sort('created'); ?></th>
               
				</tr>
				</thead>
				<tbody>
					<?php foreach ($staffArr as $staffArrs): ?>
					<tr>
						<td><?php echo $staffArrs['Staff']['name']; ?>&nbsp;</td>
					<td><?php echo h($staffArrs['Staff']['email']); ?>&nbsp;</td>
					<td><?php echo h($staffArrs['Staff']['phone']); ?>&nbsp;</td>		
					<td><?php echo h($staffArrs['Department']['department_title']); ?>&nbsp;</td>
					<td><?php echo $staffArrs['Staff']['designation']; ?>&nbsp;</td>
											
					<td><?php if($staffArrs['Staff']['status'] == 1){ echo '<span class="label label-danger">Active</span>'; }else{ echo '<span class="label label-success">Deactive</span>'; } ?>&nbsp;</td>					
					<td> <?php echo date('d-M-Y',strtotime($staffArrs['Staff']['created'])); ?></td>
					
					
					</tr>
					<?php endforeach; ?>

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