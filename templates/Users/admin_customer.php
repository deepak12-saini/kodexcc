	
<style>
.btn{margin-bottom: 5px; }
.label {
    margin-bottom: 5px;
}
</style>
	
	<div class="page-content">

		<div class="page-header">
			<h1>Customer Management
			
			<form action="" method="post" style="float:right;">
				<input type="text" name="name" value="<?php echo $name; ?>" placeholder="Search By Name" >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs" >	
				<a href="<?php echo SITEURL.'admin/users/customer'?>" class="btn btn-warning btn-xs">Show All</a>
			</form>	
			
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				
				<th><?php echo $this->Paginator->sort('name','Name'); ?></th>				
				<th><?php echo $this->Paginator->sort('email'); ?></th>				
				<th><?php echo $this->Paginator->sort('mobile / phone'); ?></th>
				<th><?php echo $this->Paginator->sort('company','company/position/zipcode/website'); ?></th>				
				<th><?php echo $this->Paginator->sort('Address'); ?></th>
				<th><?php echo $this->Paginator->sort('Assign Level'); ?></th>
			  <th><?php echo $this->Paginator->sort('insert_date','Date'); ?></th>
				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($NappUser as $NappUsers): ?>
					<tr>
					<td><?php echo ucfirst($NappUsers['NappUser']['name']); ?>&nbsp;<?php echo ucfirst($NappUsers['NappUser']['lname']); ?></td>
					
					<td><?php echo h($NappUsers['NappUser']['email']); ?>&nbsp;</td>
					<td>
					Mobile: <?php echo h($NappUsers['NappUser']['mobile_number']); ?>&nbsp;<br>
					Phone: <?php echo h($NappUsers['NappUser']['phone']); ?>&nbsp;<br>				
					</td>
					
					<td>
						<b> Company:</b> <?php echo h($NappUsers['NappUser']['company']); ?>&nbsp;<br/>
						<b> Position:</b> <?php echo h($NappUsers['NappUser']['position']); ?>&nbsp;<br/>
						<b> Zipcode:</b> <?php echo h($NappUsers['NappUser']['zipcode']); ?>&nbsp;<br/>
						<b> Website:</b> <?php echo h($NappUsers['NappUser']['website']); ?>&nbsp;
					</td>
					
					<td>
						<?php echo h($NappUsers['NappUser']['address_1']); ?>&nbsp;
						<?php echo h($NappUsers['NappUser']['address_2']); ?>&nbsp;
						<?php echo h($NappUsers['NappUser']['address_3']); ?>&nbsp;
						<?php echo h($NappUsers['NappUser']['address_4']); ?>&nbsp;
						<?php echo h($NappUsers['NappUser']['address_5']); ?>
					</td>					
					<td>
						<?php
						$labels = '';
						foreach($NappUsers['LabAssign'] as $LabAssigns){ 
							if($LabAssigns['LabFile']['type'] == 0){
								$labels .=  '<b class="label label-success">'. $LabAssigns['LabFile']['label'].'</b><br>  ';
							}else{
								$labels .=  '<b class="label label-danger"> '.$LabAssigns['LabFile']['label'].'</b><br>  ';
							}
						}						
						echo $labels;
						?>
						
					</td>
                    <td><?php echo date('d-M-Y',strtotime($NappUsers['NappUser']['insert_date'])); ?></td>
                    <td>
						<?php if($NappUsers['NappUser']['is_natspec_presentation'] == 0){ ?>
							<a class="btn btn-xs btn-danger" href="<?php echo SITEURL.'admin/users/natspec_presentation_status/'.$NappUsers['NappUser']['id'].'/1'?>">Presentation Active</a>
						<?php }else{ ?>
							<a class="btn btn-xs btn-success" href="<?php echo SITEURL.'admin/users/natspec_presentation_status/'.$NappUsers['NappUser']['id'].'/0'?>">Presentation Deactive</a>
						<?php  } ?>
						
						<?php if($NappUsers['NappUser']['is_cpd_presentation'] == 0){ ?>
							<a class="btn btn-xs btn-danger" href="<?php echo SITEURL.'admin/users/cpd_presentation_status/'.$NappUsers['NappUser']['id'].'/1'?>">Refuel CPD Active</a>
						<?php }else{ ?>
							<a class="btn btn-xs btn-success" href="<?php echo SITEURL.'admin/users/cpd_presentation_status/'.$NappUsers['NappUser']['id'].'/0'?>">Refuel CPD Deactive</a>
						<?php  } ?>
						
						 <a class="btn btn-xs btn-info" href="<?php echo SITEURL.'admin/users/access/'.$NappUsers['NappUser']['id'] ?>">Price PDF</a>
						 <a class="btn btn-xs btn-warning" href="<?php echo SITEURL.'admin/users/customerpermission/'.$NappUsers['NappUser']['id'] ?>">Customer Permission</a>
						
						
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