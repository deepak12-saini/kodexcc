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
					
					<th><?php echo $this->Paginator->sort('customer_order_no'); ?></th>					
					<th><?php echo $this->Paginator->sort('date_of_order'); ?></th>		
					<th><?php echo $this->Paginator->sort('user_id','Added By'); ?></th>					
					<th><?php echo $this->Paginator->sort('account_name'); ?></th>
					<th><?php echo $this->Paginator->sort('order_taken_by'); ?></th>
					<th><?php echo $this->Paginator->sort('contact_name'); ?></th>
					<th><?php echo $this->Paginator->sort('contact_phone'); ?></th>
					<th><?php echo $this->Paginator->sort('deliver_address'); ?></th>
					<th><?php echo $this->Paginator->sort('status'); ?></th>
					<th><?php echo $this->Paginator->sort('created'); ?></th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					<?php
					$k = 1;
					foreach ($DuroOrderArr as $DuroOrderArrs): ?>
					<tr>
						<td><a data-toggle="modal" data-target="#myModal_<?php echo $k; ?>" href="#null"><?php echo h($DuroOrderArrs['DuroOrder']['customer_order_no']); ?></a>&nbsp;</td>						
						<td><?php echo date('d-M-Y',strtotime($DuroOrderArrs['DuroOrder']['date_of_order'])); ?>&nbsp;</td>		
						<td><?php echo $DuroOrderArrs['NappUser']['name'].' '.$DuroOrderArrs['NappUser']['lname']; ?></td>
						<td><?php echo $DuroOrderArrs['DuroOrder']['account_name']; ?>&nbsp;</td>						
						<td><?php echo h($DuroOrderArrs['DuroOrder']['order_taken_by']); ?>&nbsp;</td>
						<td><?php echo h($DuroOrderArrs['DuroOrder']['contact_name']); ?>&nbsp;</td>
						<td><?php echo h($DuroOrderArrs['DuroOrder']['contact_phone']); ?>&nbsp;</td>				
						<td><?php echo h($DuroOrderArrs['DuroOrder']['deliver_address']); ?>&nbsp;</td>				
						<td>
						<?php
							if($DuroOrderArrs['DuroOrder']['status'] == 0){
								echo '<span class="label label-primary">New Order</span>';
							}else if($DuroOrderArrs['DuroOrder']['status'] == 1){
								echo '<span class="label label-info">Accepted</span>';
							}else if($DuroOrderArrs['DuroOrder']['status'] == 2){
								echo '<span class="label label-success">Order Ready</span>';
							}else if($DuroOrderArrs['DuroOrder']['status'] == 3){
								echo '<span class="label label-success">Dispatch</span>';
							}else if($DuroOrderArrs['DuroOrder']['status'] == 4){
								echo '<span class="label label-success">Completed</span>';
							}else if($DuroOrderArrs['DuroOrder']['status'] == 5){
								echo '<span class="label label-danger">Canceled</span>';
							}else if($DuroOrderArrs['DuroOrder']['status'] == 6){
								echo '<span class="label label-warning">Order Delivered</span>';
							}	
						?>&nbsp;
						</td>
						
						<td> <?php echo date('d-M-Y',strtotime($DuroOrderArrs['DuroOrder']['created'])); ?></td>
						<td>
						<?php if($DuroOrderArrs['DuroOrder']['is_point_added'] == 0){ ?>
							<a href="<?php echo SITEURL.'admin/duro_orders/edit/'.$DuroOrderArrs['DuroOrder']['id']; ?>" class="btn btn-mini btn-info">Edit</a>	
						<?php } ?>	
						<?php if(($DuroOrderArrs['DuroOrder']['is_point_added'] == 0 )&& ($DuroOrderArrs['DuroOrder']['status'] == 6)){ ?>	
							<a href="<?php echo SITEURL.'admin/duro_orders/calculatepoints/'.$DuroOrderArrs['DuroOrder']['id']; ?>" onclick="return confirm('Are you sure add points?')" class="btn btn-mini btn-primary">Add Point</a>	
						<?php } ?>	 		
						</td>					
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

<?php 
	$j=1;
	foreach ($DuroOrderArr as $DuroOrderArrs): 
	
	?>
	<div id="myModal_<?php echo $j; ?>" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Document Title Records of Library: <?php echo h($DuroOrderArrs['DuroOrder']['customer_order_no']); ?></h4>
		  </div>
		  <div class="modal-body">
			<div class="row">
			<div class="col-xs-12">
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover"  >
					<tbody>
					<tr><th>Documentid: </th><td><span class="label label-inverse"><?php echo h($DuroOrderArrs['DuroOrder']['customer_order_no']); ?></span></td></tr>	
					
					<tr><th>Added By: </th><td><?php echo $DuroOrderArrs['NappUser']['name'].' '.$DuroOrderArrs['NappUser']['lname']; ?></td></tr>	
					
					<tr><th>Date: </th><td><?php echo date('d-M-Y',strtotime($DuroOrderArrs['DuroOrder']['date_of_order'])); ?></td></tr>	
					<tr><th>Account Name: </th><td><?php echo $DuroOrderArrs['DuroOrder']['account_name']; ?></td></tr>	
					<tr><th>order taken by: </th><td><?php echo $DuroOrderArrs['DuroOrder']['order_taken_by']; ?></td></tr>	
					<tr><th>contact name: </th><td><?php echo $DuroOrderArrs['DuroOrder']['contact_name']; ?></td></tr>	
					<tr><th>contact phone: </th><td><?php echo $DuroOrderArrs['DuroOrder']['contact_phone']; ?></td></tr>						
					<tr><th>deliver address: </th><td><?php echo $DuroOrderArrs['DuroOrder']['deliver_address']; ?></td></tr>		
					<tr><th>Pallet: </th><td><?php echo $DuroOrderArrs['DuroOrder']['delivery_instruction']; ?></td></tr>
					<tr><th>Sale Rep.: </th><td><?php echo $DuroOrderArrs['DuroOrder']['sale_rep']; ?></td></tr>
					<tr><th>Status: </th><td>
					<?php
						if($DuroOrderArrs['DuroOrder']['status'] == 0){
							echo '<span class="label label-primary">New Order</span>';
						}else if($DuroOrderArrs['DuroOrder']['status'] == 1){
							echo '<span class="label label-info">Accepted</span>';
						}else if($DuroOrderArrs['DuroOrder']['status'] == 2){
							echo '<span class="label label-success">Order Ready</span>';
						}else if($DuroOrderArrs['DuroOrder']['status'] == 3){
							echo '<span class="label label-success">Dispatch</span>';
						}else if($DuroOrderArrs['DuroOrder']['status'] == 4){
							echo '<span class="label label-success">Completed</span>';
						}else if($DuroOrderArrs['DuroOrder']['status'] == 5){
							echo '<span class="label label-danger">Canceled</span>';
						}else if($DuroOrderArrs['DuroOrder']['status'] == 6){
							echo '<span class="label label-warning">Order Delivered</span>';
						}
					?>&nbsp;
						</td></tr>	
						<tr>
							<th>Order Summary: </th>
							<td> 
								<p>Order Accepted: <?php if($DuroOrderArrs['DuroOrder']['accepted_order'] != '0000-00-00 00:00:00'){  echo date('d-M-Y h:i a',strtotime($DuroOrderArrs['DuroOrder']['accepted_order'])); } ?></p>
								<p>Order Ready: <?php if($DuroOrderArrs['DuroOrder']['ready_order'] != '0000-00-00 00:00:00'){  echo date('d-M-Y h:i a',strtotime($DuroOrderArrs['DuroOrder']['ready_order'])); } ?></p>
								<p>Order Dispatch: <?php if($DuroOrderArrs['DuroOrder']['order_dispatched'] != '0000-00-00 00:00:00'){   echo date('d-M-Y h:i a',strtotime($DuroOrderArrs['DuroOrder']['order_dispatched'])); } ?></p>
								
								<p>Order Delivered: <?php if($DuroOrderArrs['DuroOrder']['order_deliverd'] != '0000-00-00 00:00:00'){   echo date('d-M-Y h:i a',strtotime($DuroOrderArrs['DuroOrder']['order_deliverd'])); } ?></p>
								<p>Order Completed: <?php if($DuroOrderArrs['DuroOrder']['completed_order'] != '0000-00-00 00:00:00'){   echo date('d-M-Y h:i a',strtotime($DuroOrderArrs['DuroOrder']['completed_order'])); } ?></p>
							</td>		
						</tr>	
						<tr><th>Created: </th><td><?php echo date('d-M-Y',strtotime($DuroOrderArrs['DuroOrder']['created'])); ?></td></tr>	
						<?php if($DuroOrderArrs['DuroOrder']['is_sample'] == 1){ ?>
							<tr><th>Feedback To Sample Items: </th></tr>
							<tr><th>Feedback: </th><td><?php echo $DuroOrderArrs['DuroOrder']['feedback']; ?></td></tr>
							<tr><th>who give to: </th><td><?php echo $DuroOrderArrs['DuroOrder']['who_give_to']; ?></td></tr>
							<tr><th>when use: </th><td><?php echo $DuroOrderArrs['DuroOrder']['when_use']; ?></td></tr>
							<tr><th>where use: </th><td><?php echo $DuroOrderArrs['DuroOrder']['where_use']; ?></td></tr>
						<?php } ?>
					</tbody>
					
				</table>
				<table class="table table-striped table-bordered table-hover"  >
					<thead>		
						<tr>
							<th>S.no</th>
							<th>Product Name</th>
							<th>size</th>
							<th>color</th>
							<th>qty</th>
						<tr>	
					</thead>
					<tbody>
						<?php 
						$k=1;
						foreach($DuroOrderArrs['OrderProduct'] as $records){  ?>
							<tr>
								<th><?php echo $k; ?></th>
								<th><?php echo $records['product_name']; ?></th>
								<th><?php echo $records['size']; ?></th>
								<th><?php echo $records['color']; ?></th>								
								<th><?php echo $records['qty']; ?></th>
								
							<tr>		
						<?php  $k++; } ?>	
					</tbody>
				</table>
				
				<p>
					<?php if($DuroOrderArrs['DuroOrder']['status'] == 0){?>
						<a href="<?php echo SITEURL.'admin/duro_orders/status/'.$DuroOrderArrs['DuroOrder']['id'].'/1' ?>" class="btn btn-success">Accept</a> &nbsp;&nbsp; 
						<a href="<?php echo SITEURL.'admin/duro_orders/status/'.$DuroOrderArrs['DuroOrder']['id'].'/5' ?>" class="btn btn-danger">Cancel</a> 
					<?php }else if($DuroOrderArrs['DuroOrder']['status'] == 1 || $DuroOrderArrs['DuroOrder']['status'] == 6){ ?>
						<a onclick="return confirm('Are you sure completed this order')" href="<?php echo SITEURL.'admin/duro_orders/status/'.$DuroOrderArrs['DuroOrder']['id'].'/4' ?>" class="btn btn-success">Complete</a>
					<?php } ?>
				</p>

				</div>
			</div>
		</div>		
		  </div>
		 
		</div>

	  </div>
	</div>
	<?php $j++; endforeach; ?>
					