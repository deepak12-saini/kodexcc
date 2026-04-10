	<div class="page-content">
		<div class="page-header">
			<h1>Resource Requirement List
				<a href="<?php echo SITEURL.'purchases/resource_add'?>" class="btn btn-xs btn-info" >Add New Resource Requirement</a>
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>		
					<tr>
						<th><?php echo $this->Paginator->sort('unique_id','Order Id'); ?></th>						
						<th><?php echo $this->Paginator->sort('item_details'); ?></th>						
						<th><?php echo $this->Paginator->sort('requisitioner_name'); ?></th>						
						<th><?php echo $this->Paginator->sort('date'); ?></th>							
						<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>						
						<th><?php echo $this->Paginator->sort('permitted_by','Responsibility'); ?></th>						
						<th><?php echo $this->Paginator->sort('Request Type'); ?></th>						
						<th><?php echo $this->Paginator->sort('status'); ?></th>						
						<th><?php echo $this->Paginator->sort('created'); ?></th>						
						<th>Action</th>						
					</tr>				
				</thead>
				<tbody>
					<?php $j=1; foreach($purchaseArr as $purchaseArrs){ ?>
					<tr>
						<td><a data-toggle="modal" data-target="#myModal_<?php echo $j; ?>" href="#null"><?php echo $purchaseArrs['Purchase']['unique_id']; ?></a></td>
						<td><?php echo $purchaseArrs['Purchase']['item_details']; ?></td>
						<td><?php echo $purchaseArrs['Purchase']['requisitioner_name']; ?></td>
						<td><?php echo $purchaseArrs['Purchase']['date']; ?></td>						
						<td><?php echo $purchaseArrs['NappUser_1']['name'].' '.$purchaseArrs['NappUser_1']['name']; ?></td>
						<td><?php echo $purchaseArrs['NappUser']['name'].' '.$purchaseArrs['NappUser']['name']; ?></td>		
						<td>
							
							<?php 
								$id = $this->Session->read('Customer.id');
								if($purchaseArrs['Purchase']['prepared_by'] == $id){
									echo '<span class="label label-info">Sender</span>';
								}else{
									echo '<span class="label label-danger">Receiver</span>';
								}
							?>
						</td>
						<td>
						<?php 
						if($purchaseArrs['Purchase']['status'] == 0){
							echo '<span class="label label-danger">Pending</span>';
						}else if($purchaseArrs['Purchase']['status'] == 1){
							echo '<span class="label label-primary">Aprroved</span>';
						}else if($purchaseArrs['Purchase']['status'] == 2){
							echo '<span class="label label-success">Received</span>';
						}						
						?>
						</td>
						<td><?php echo $purchaseArrs['Purchase']['created']; ?></td>
						<td>							
							<?php 
							if($purchaseArrs['Purchase']['status'] < 2){
							
								$userid = $this->Session->read('Customer.id');	
								if($purchaseArrs['Purchase']['prepared_by'] == $userid){
							?>
								<a href="<?php echo SITEURL.'purchases/resource_edit/'.$purchaseArrs['Purchase']['id']; ?>" class="btn btn-mini btn-info">Edit</a>
								
								<a onclick="return confirm('Are you sure delete?')" href="<?php echo SITEURL.'purchases/resource_delete/'.$purchaseArrs['Purchase']['id']; ?>" class="btn btn-mini btn-danger">Delete</a>
								
								
								<?php } ?>
								<?php 
								$id = $this->Session->read('Customer.id');
								if($purchaseArrs['Purchase']['prepared_by'] != $id){ 
								?>
									<a class="btn btn-mini btn-primary" href="<?php echo SITEURL.'purchases/resource_process/'.$purchaseArrs['Purchase']['id']; ?>">Approval</a>
								<?php } ?>	
								<!-- <a class="btn btn-mini btn-primary" href="<?php echo SITEURL.'purchases/process/'.$purchaseArrs['Purchase']['id']; ?>">Process</a> -->

								<!-- 								
								<a onclick="return confirm('Are you sure you want to close?')" href="<?php echo SITEURL.'purchases/received/'.$purchaseArrs['Purchase']['id']; ?>" class="btn btn-mini btn-warning">Received</a> -->
								
								<?php }else{ ?>
								N/A
							<?php  }  ?>
						</td>
					</tr>
					<?php $j++; } ?>
				</tbody>
			</table>
			
		</div>
	</div>		
	
</div>	
</div>	


<?php 
$i=1;
foreach($purchaseArr as $purchaseArrs): 				
?>
<div id="myModal_<?php echo $i; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Order-Id: <?php echo h($purchaseArrs['Purchase']['unique_id']); ?></h4>
		</div>
			<div class="modal-body">
			<div class="row">
				<div class="col-xs-12">
					<div class="table-responsive">
					
					<table class="table table-striped table-bordered table-hover" id="simple-table" >
						<tbody>						
							<tr><th>Unique Id: </th><td><span class="label label-inverse"><?php echo h($purchaseArrs['Purchase']['unique_id']); ?></span></td></tr>	
							
							<tr><th>Date: </th><td><?php echo h($purchaseArrs['Purchase']['date']); ?></td></tr>	
							<tr><th>Item Details: </th><td><?php echo h($purchaseArrs['Purchase']['item_details']); ?></td></tr>	
							<tr><th>Name of the Requisitioner: </th><td><?php echo h($purchaseArrs['Purchase']['requisitioner_name']); ?></td></tr>	
							
							<tr><th>Responsibility: </th><td><?php echo $purchaseArrs['NappUser']['name'].' '.$purchaseArrs['NappUser']['name']; ?></td></tr>	
							 
							<tr><th>Prepared By: </th><td><?php echo $purchaseArrs['NappUser_1']['name'].' '.$purchaseArrs['NappUser_1']['name']; ?></td></tr>	
														
							<tr><th>status: </th>
							<td>
							<?php if($purchaseArrs['Purchase']['status'] == 0){ echo '<span class="label label-info">Pending</span>'; }else if($purchaseArrs['Purchase']['status'] == 1){ echo '<span class="label label-success">Approved</span>'; } ?>
							</td></tr>					
						</tbody>						
					</table>	
					<?php 
						if(!empty($purchaseArrs['PurchaseRequirement'])){
						$j=1;
						foreach($purchaseArrs['PurchaseRequirement'] as $PurchaseRequirement){ 						
					?>	
					<b class="label label-success">Item <?php echo $j; ?></b>
					<table class="table table-striped table-bordered table-hover" id="simple-table" >
						<tbody>					
						
							<tr><th>Resource Required: </th><td><?php echo h($PurchaseRequirement['resource_requirement']); ?></td></tr>	
							<tr><th>Purpose/Project: </th><td><?php echo h($PurchaseRequirement['purpose_project']); ?></td></tr>	
							<tr><th>Quantity: </th><td><?php echo h($PurchaseRequirement['quantity']); ?></td></tr>	
							<tr><th>Date: </th><td><?php echo h($PurchaseRequirement['time']); ?></td></tr>	
							<tr><th>Budget: </th><td><?php echo h($PurchaseRequirement['budget']); ?></td></tr>	
							<tr><th>Remark: </th><td><?php echo h($PurchaseRequirement['remark']); ?></td></tr>	
							
						</tbody>						
					</table>	
					<?php
						$j++;	
						}
					}
					
					?>
					</div>
				</div>
				</div>				
			</div>				
		</div>				
	</div>				
</div>				
<?php $i++; endforeach; ?>