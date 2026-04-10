	<?php
		$chkuserpermission = $this->requestAction('/app/chkuserpermission');
	
	?>
	<div class="page-content">
		<div class="page-header">
			<h1>Kodex orders List <a href="<?php echo SITEURL.'duro_orders/add' ?>" class="btn btn-mini btn-primary"> Add New</a></h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="example" >
				<thead>
				<tr>					
					<th></th>					
					<th>Order Number</th>					
					<th>Date of Order</th>		
					<th>Added By</th>
					<th>Contact Name</th>
					<th>Contact Phone</th>
					<th>Deliver Address</th>
					<th>Status</th>
					<th>created</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					<?php
					$k = 1;
					$j = 1;
					foreach ($DuroOrderArr as $DuroOrderArrs): ?>
					<tr>
						<td><?php echo $k; ?>&nbsp;</td>						
						<td><a data-toggle="modal" data-target="#myModal_<?php echo $j; ?>" href="#null"><?php echo h($DuroOrderArrs['DuroOrder']['customer_order_no']); ?></a>&nbsp;</td>						
						<td><?php echo date('d-M-Y',strtotime($DuroOrderArrs['DuroOrder']['date_of_order'])); ?>&nbsp;</td>		
						<td><?php echo $DuroOrderArrs['NappUser']['name'].' '.$DuroOrderArrs['NappUser']['lname']; ?></td>
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
						<?php if($DuroOrderArrs['DuroOrder']['status'] < 1 && ($DuroOrderArrs['DuroOrder']['user_id'] == $user_id)){ ?>
							
							<a href="<?php echo SITEURL.'duro_orders/edit/'.$DuroOrderArrs['DuroOrder']['id']; ?>" class="btn btn-mini btn-info">Edit</a>	
							<?php if($DuroOrderArrs['DuroOrder']['is_sample'] == 1){ ?>
								<a href="<?php echo SITEURL.'duro_orders/feedback/'.$DuroOrderArrs['DuroOrder']['id']; ?>" class="btn btn-mini btn-primary">Feedback</a>
							<?php } ?>	
						<?php } ?>	
						
						<?php if(in_array(24,$chkuserpermission) ){ if($DuroOrderArrs['DuroOrder']['status'] == 0){ ?>
							<a onclick="return confirm('Are you to accept this order?')" href="<?php echo SITEURL.'duro_orders/accepted/'.$DuroOrderArrs['DuroOrder']['id']; ?>" class="btn btn-mini btn-info">Accept</a>	
							<a onclick="return confirm('Are you to cencel this order?')" href="<?php echo SITEURL.'duro_orders/cancelled/'.$DuroOrderArrs['DuroOrder']['id']; ?>" class="btn btn-mini btn-danger">Cancel</a>	
						
						<?php } }
						if(in_array(25,$chkuserpermission) ){
						if($DuroOrderArrs['DuroOrder']['status'] == 1){  ?>
							<a href="<?php echo SITEURL.'duro_orders/orderready/'.$DuroOrderArrs['DuroOrder']['id']; ?>" class="btn btn-mini btn-info">Order Ready</a>
						<?php }else if($DuroOrderArrs['DuroOrder']['status'] == 2){  ?>
							<a href="<?php echo SITEURL.'duro_orders/deliver/'.$DuroOrderArrs['DuroOrder']['id']; ?>" class="btn btn-mini btn-info">Deliver</a>
						<?php } } ?>							
						</td>					
					</tr>
					<?php $j++; 
					endforeach; ?>
					
				</tbody>
			</table>			
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
			<h4 class="modal-title">Order Detail: <?php echo h($DuroOrderArrs['DuroOrder']['customer_order_no']); ?></h4>
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
					<tr><th>Customer name: </th><td><?php echo $DuroOrderArrs['DuroOrder']['contact_name']; ?></td></tr>	
					<tr><th>Customer phone: </th><td><?php echo $DuroOrderArrs['DuroOrder']['contact_phone']; ?></td></tr>		
					<tr><th>Deliver Address: </th><td><?php echo $DuroOrderArrs['DuroOrder']['deliver_address']; ?></td></tr>
					<tr><th>Deliver Date: </th><td><?php echo $DuroOrderArrs['DuroOrder']['delivery_date']; ?></td></tr>
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
							echo '<span class="label label-danger">Cancelled</span>';
						}else if($DuroOrderArrs['DuroOrder']['status'] == 6){
							echo '<span class="label label-warning">Order Delivered</span>';
						}				
					?>&nbsp;
						</td>
					</tr>
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
				<?php 
				//echo $DuroOrderArrs['DuroOrder']['status'];
				if(($DuroOrderArrs['DuroOrder']['status'] == 1) && (in_array(25,$chkuserpermission))){?>
					<a href="<?php echo SITEURL.'duro_orders/status/'.$DuroOrderArrs['DuroOrder']['id'].'/2' ?>" class="btn btn-success">Order Ready</a> &nbsp;&nbsp; 					
				<?php }else if(($DuroOrderArrs['DuroOrder']['status'] == 2) && (in_array(26,$chkuserpermission))){ ?>	
					<a href="<?php echo SITEURL.'duro_orders/status/'.$DuroOrderArrs['DuroOrder']['id'].'/3' ?>" class="btn btn-success">Dispatch Order</a> &nbsp;&nbsp;
				
				<?php }else if(($DuroOrderArrs['DuroOrder']['status'] == 4) && (in_array(29,$chkuserpermission))){ ?>						
					<a href="<?php echo SITEURL.'duro_orders/status/'.$DuroOrderArrs['DuroOrder']['id'].'/6' ?>" class="btn btn-success">Order Deliver</a> 
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
					