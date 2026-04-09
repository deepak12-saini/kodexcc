	<style>
		.btn{ margin-bottom:5px;}
	</style>
	<div class="page-content">
		<div class="page-header">
			<h1>
			Staff List
			<form action="" method="post" style="float:right;">
				<input type="text" name="name" value="<?php echo $name; ?>" placeholder="Search By Name" >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs" >	
				<a href="<?php echo SITEURL.'admin/users/staff'?>" class="btn btn-warning btn-xs">Show All</a>
			</form>		
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<!-- <th><?php echo $this->Paginator->sort('staff_id','Staff Id'); ?></th> -->				
                <th><?php echo $this->Paginator->sort('name'); ?></th>
                  <th><?php echo $this->Paginator->sort('email'); ?></th>
                  <th><?php echo $this->Paginator->sort('mobile_number'); ?></th>
                  <th><?php echo $this->Paginator->sort('designation'); ?></th>
                  <th><?php echo $this->Paginator->sort('is_active','Verify'); ?></th>
                  <th><?php echo $this->Paginator->sort('is_approved'); ?></th>
				  <th><?php echo $this->Paginator->sort('Assign Level'); ?></th>
                  <th><?php echo $this->Paginator->sort('created'); ?></th>
                  <th>Action</th>

				</tr>
				</thead>
				<tbody>
					<?php foreach ($staffArr as $staffArrs): ?>
					<tr>
				<!-- 	<td><?php echo h($staffArrs['NappUser']['emp_id']); ?>&nbsp;</td> -->
					<td><?php echo $staffArrs['NappUser']['name'].' '.$staffArrs['NappUser']['lname']; ?>&nbsp;</td>
					<td><?php echo h($staffArrs['NappUser']['email']); ?>&nbsp;</td>
					<td><?php echo h($staffArrs['NappUser']['mobile_number']); ?>&nbsp;</td>
					<td><?php echo h($staffArrs['NappUser']['designation']); ?>&nbsp;</td>					
					<td><?php if($staffArrs['NappUser']['is_active'] == 0){ echo '<span class="label label-danger">Not Verify</span>'; }else{ echo '<span class="label label-success">Verified</span>'; } ?>&nbsp;</td>					
					<td>
					<?php if($staffArrs['NappUser']['is_approved'] == 0){ 
						echo '<span class="label label-danger">Pending</span>'; 
					}else if($staffArrs['NappUser']['is_approved'] == 1){  
						echo '<span class="label label-success">Approved</span>'; 
					} else if($staffArrs['NappUser']['is_approved'] == 2){  
						echo '<span class="label label-warning">Disapproved</span>'; 
					} 
					
					?>&nbsp;</td>			
					<td>
						<?php
						$labels = '';
						foreach ($staffArrs['LabAssign'] ?? [] as $LabAssigns) { 
							if($LabAssigns['LabFile']['type'] == 0){
								$labels .=  '<b class="label label-success">'. $LabAssigns['LabFile']['label'].'</b><br>  ';
							}else{
								$labels .=  '<b class="label label-danger"> '.$LabAssigns['LabFile']['label'].'</b><br>  ';
							}
						}						
						echo $labels;
						?>
						
					</td>	
                    <td> <?php echo date('d-M-Y',strtotime($staffArrs['NappUser']['insert_date'])); ?></td>
					<td> 
										
					<a href="<?php echo SITEURL.'admin/users/edit_staff/'.$staffArrs['NappUser']['id']; ?>" class="btn btn-mini btn-primary">Edit</a>
					<a class="btn btn-mini btn-info" href="<?php echo SITEURL.'admin/users/access/'.$staffArrs['NappUser']['id'] ?>">Price PDF</a>
					<a href="<?php echo SITEURL.'admin/users/permission/'.$staffArrs['NappUser']['id']; ?>" class="btn btn-mini btn-danger">Permission</a>
					
					<?php if($staffArrs['NappUser']['is_natspec_presentation'] == 0){ ?>
						<a class="btn btn-xs btn-warning" href="<?php echo SITEURL.'admin/users/natspec_presentation_status/'.$staffArrs['NappUser']['id'].'/1/1'?>">Presentation Active</a>
					<?php }else{ ?>
						<a class="btn btn-xs btn-success" href="<?php echo SITEURL.'admin/users/natspec_presentation_status/'.$staffArrs['NappUser']['id'].'/0/1'?>">Presentation Deactive</a>
					<?php  } ?>
					
					<?php if($staffArrs['NappUser']['is_cpd_presentation'] == 0){ ?>
						<a class="btn btn-xs btn-warning" href="<?php echo SITEURL.'admin/users/cpd_presentation_status/'.$staffArrs['NappUser']['id'].'/1/1'?>">Refuel CPD Active</a>
					<?php }else{ ?>
						<a class="btn btn-xs btn-success" href="<?php echo SITEURL.'admin/users/cpd_presentation_status/'.$staffArrs['NappUser']['id'].'/0/1'?>">Refuel CPD Deactive</a>
					<?php  } ?>
					
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
			); ?>	</div>
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