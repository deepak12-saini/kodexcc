<div class="page-content">
		<div class="page-header">
			<h1>Login History Logs</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>				
					<th><?php echo $this->Paginator->sort('id'); ?></th>					
					<th><?php echo $this->Paginator->sort('name'); ?></th>
					<th><?php echo $this->Paginator->sort('email'); ?></th>			
					<th><?php echo $this->Paginator->sort('role'); ?></th>			
					<th><?php echo $this->Paginator->sort('Login Time'); ?></th>									
					<th><?php echo $this->Paginator->sort('Logout Time'); ?></th>	
					
				</tr>
				</thead>
				<tbody>
					<?php 
				
					foreach ($LoginHistoryArr as $LoginHistoryArrs):
						if($LoginHistoryArrs['LoginHistory']['role'] == 'Admin'){
							$name = $LoginHistoryArrs['User']['name'];
							$email = $LoginHistoryArrs['User']['email'];
						}else{
							$name = $LoginHistoryArrs['NappUser']['name'].' '.$LoginHistoryArrs['NappUser']['name'] ;
							$email = $LoginHistoryArrs['NappUser']['email'];
						}
					?>
					<tr>								
						<td>#<?php echo h($LoginHistoryArrs['LoginHistory']['id']); ?>&nbsp;</td>
						<td><?php echo $name; ?>&nbsp;</td>			
						<td><?php echo $email; ?>&nbsp;</td>			
						<td><?php echo h($LoginHistoryArrs['LoginHistory']['role']); ?>&nbsp;</td>
						<td><?php echo h($LoginHistoryArrs['LoginHistory']['logintime']); ?>&nbsp;</td>					
						<td><?php 
						if($LoginHistoryArrs['LoginHistory']['logouttime'] != '0000-00-00 00:00:00'){
							echo $LoginHistoryArrs['LoginHistory']['logouttime'];
						}else{
							echo '-'; 
						} 
						?>&nbsp;</td>	
					
					</tr>
					<?php endforeach; ?>

				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php	echo $this->Paginator->counter(array('format' => __('showing {:current} records out of {:count} entries')));?>	</div>
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