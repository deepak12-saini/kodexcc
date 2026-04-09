	<style>
		.btn{ margin-bottom:5px;}
	</style>
	<?php
		$user_id=$this->Session->read('Customer.id');
	?>
	<div class="page-content">
		<div class="page-header">
			<h1>DuroLab 
			<?php
				$durolab_type = $this->Session->read('durolab_type');
				if($durolab_type == 'project_enquiry'){
					echo '- Project Enquiry';	
				}else if($durolab_type == 'product'){
					echo '- Product Development';	
				}else if($durolab_type == 'technical'){
					echo '- Technical Service';	
				}
			?>
			
			
			<?php if(!empty($UserPermission) && ($is_access == 2)){ echo $this->Html->link('Add New',array('controller' => 'tasks','action' => 'add'),array('class'=>'btn btn-info btn-xs top-button')); } ?>
			
			
			<form action="" method="post" style="float:right;">
				<input type="text" name="keyowrd" required value="<?php echo $keyowrd; ?>" placeholder="Search By TaskID" style="width:174px !important">
				<input type="submit" name="search"  value="search" class="btn btn-info btn-xs" style="margin-top:10px" >	
				<a href="<?php echo SITEURL.'tasks/index'?>" class="btn btn-warning btn-xs" style="margin-top:10px">Show All</a>
			</form>	
			
			</h1>
		</div>
	<?php if($is_access == 1){ ?>	
	<div class="row">
	
		<div class="col-xs-12">
		
			<form action="" class="form-horizontal" id="" method="post" accept-charset="utf-8">
				<?php if(!empty($usernewArr['NappUser']['email']) && !empty($usernewArr['NappUser']['mobile_number']) && ($usernewArr['NappUser']['is_active_otp'] > 0) ){ ?>
					<p><b>Form enhaced security of your account, we have sent One-Time Password (OTP) to your mobile number <?php echo $usernewArr['NappUser']['mobile_number'] ?> and email Id <?php echo $usernewArr['NappUser']['email'] ?></b></p>	
				<?php }else { ?>
					<p><b>Form enhaced security of your account, we have sent One-Time Password (OTP) to you email Id <?php echo $usernewArr['NappUser']['email'] ?></b></p>	
				<?php  } ?>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Please enter One Time Password (OTP): </label>
					<div class="col-sm-9">
						<input name="data[otp]" class="col-xs-10 col-sm-5 ErrorField" id="otp" placeholder="Fill OTP received on mobile / email" type="password">						
					</div>
				</div>				
				<div class="form-group">
					<div class="col-md-offset-3 col-md-9">
						<input class="btn btn-success" id="add_ser_prd_btn" value="Verify" type="submit">&nbsp;							
					</div>
				</div>
			</form>
			<p class="alert alert-warning"><b>Please check your mail. Enter one time pssword received on your registered email address.</b> </p>
		</div>		
	</div>
	<?php }else if($is_access == 2){ ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>				
				<th><?php echo $this->Paginator->sort('task_id','Project Number'); ?></th>
				<th><?php echo $this->Paginator->sort('Assigned By'); ?></th>
				<th><?php echo $this->Paginator->sort('Assigned To'); ?></th>
				<th><?php echo $this->Paginator->sort('title','Project Description'); ?></th>
				<th><?php echo $this->Paginator->sort('priority_number'); ?></th>	
				<th><?php echo $this->Paginator->sort('results_summary'); ?></th>
				<th><?php echo $this->Paginator->sort('alloteddate','Project Started/Assigned/Deadline Date'); ?></th>
				
				
						
				<th><?php echo $this->Paginator->sort('countdown'); ?></th>			
				<th><?php echo $this->Paginator->sort('status'); ?></th>
                <th><?php echo $this->Paginator->sort('created'); ?></th>
				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				<!--tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('assignedby'); ?></th>
				<th><?php echo $this->Paginator->sort('task_id','Task id'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('alloteddate','Alloted/Assigned/Deadline Date'); ?></th>
				
				<th><?php echo $this->Paginator->sort('Assigned'); ?></th>
				<th><?php echo $this->Paginator->sort('countdown'); ?></th>			
				<th><?php echo $this->Paginator->sort('status'); ?></th>
                <th><?php echo $this->Paginator->sort('created'); ?></th>
				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr-->
				</thead>
				<tbody>
					<?php 
					$i=1; foreach ($task as $tasks):						
					?>				
						
					<tr>
					<td><?php echo $i; ?>&nbsp;</td>
					
					<td><a data-toggle="modal" data-target="#myModal_<?php echo $i; ?>" href="#null"><?php echo $tasks['Task']['task_id']; ?></a>&nbsp;</td>
					<td><?php if($tasks['Task']['admin_id'] == 0){ echo '<span class="label label-info">'.$tasks['NappUser']['name'].' '.$tasks['NappUser']['lname'].'</span>'; }else{ echo  '<span class="label label-success">'.$tasks['User']['name'].'</span>'; } ?>&nbsp;</td>	
					<td>
						<?php
							if(!empty($tasks['TaskAssign'])){
								foreach($tasks['TaskAssign'] as $TaskAssign){
									echo '<span class="label label-success">'.$TaskAssign['NappUser']['name'].' '.$TaskAssign['NappUser']['lname'].'</span><br><br>';
								}	
							}
						?>
					</td>		
					<td><?php echo h($tasks['Task']['title']); ?>&nbsp;</td>
					<td><?php echo $tasks['Task']['priority_number']; ?>&nbsp;</td>
					<td><?php echo $tasks['Task']['results_summary']; ?>&nbsp;</td>
					<td>
					
					<span class="label label-info">Project Started: <?php if($tasks['Task']['alloteddate'] != '0000-00-00'){ echo date('d M Y',strtotime($tasks['Task']['alloteddate'])); }else{ echo 'N/A'; } ?>&nbsp;</span> <br/><br/>
					
					<span class="label label-warning">Assigned Date: <?php if($tasks['Task']['assigned_date'] != '0000-00-00'){ echo date('d M Y',strtotime($tasks['Task']['assigned_date'])); }else{ echo 'N/A'; }  ?>&nbsp;</span> <br/><br/>
					
					<span class="label label-danger">Deadline Date: <?php  if($tasks['Task']['task_completion_date'] == '0000-00-00'){  echo 'N/A'; }else{ echo date('d M Y',strtotime($tasks['Task']['task_completion_date']));  } ?>&nbsp;</span> 
					
					</td>
					
							
							
					<td>
						<?php 
						if($tasks['Task']['task_status'] == 3){
						
							$assigndatetime = $tasks['Task']['assigndatetime'];
							$taskcompletetime = $tasks['Task']['taskcompletetime'];
							
							$then = new DateTime($assigndatetime);
							$now = new DateTime($taskcompletetime); 
							$sinceThen = $then->diff($now);
							
							$d = $sinceThen->d;
							$m = $sinceThen->m;
							$h = $sinceThen->h;
							$i = $sinceThen->i;
							$s = $sinceThen->s;
							
							$date = $d.'d '.$h.'h '.$i.'m '.$s.'s';
							echo '<span class="label label-danger">'.$date.'</span>';	
						}else{ 
						?>
							<span class="label label-danger" id="demo_<?php echo $i; ?>">N/A</span>
							<script>
							<?php if($tasks['Task']['task_completion_date'] != '0000-00-00'){   ?>
							// Set the date we're counting down to
							var countDownDate_<?php echo $i; ?> = new Date("<?php echo date('M d, Y 10:00:00',strtotime($tasks['Task']['task_completion_date']))?>").getTime();

							// Update the count down every 1 second
							var x = setInterval(function() {
								// Get todays date and time
								var now = new Date().getTime();    
								// Find the distance between now an the count down date
								var distance_<?php echo $i; ?> = countDownDate_<?php echo $i; ?> - now;    
								// Time calculations for days, hours, minutes and seconds
								var days = Math.floor(distance_<?php echo $i; ?> / (1000 * 60 * 60 * 24));
								var hours = Math.floor((distance_<?php echo $i; ?> % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
								var minutes = Math.floor((distance_<?php echo $i; ?> % (1000 * 60 * 60)) / (1000 * 60));
								var seconds = Math.floor((distance_<?php echo $i; ?> % (1000 * 60)) / 1000);    
								// Output the result in an element with id="demo"
								document.getElementById("demo_<?php echo $i; ?>").innerHTML = days + "d " + hours + "h "
								+ minutes + "m " + seconds + "s ";    
								// If the count down is over, write some text 
								if (distance_<?php echo $i; ?> < 0) {
									//clearInterval(x);
									document.getElementById("demo_<?php echo $i; ?>").innerHTML = "EXPIRED";
								}
							}, 1000);
							<?php } ?>
							</script>
						<?php } ?>
					</td>							
					<!--td><?php echo h($tasks['Task']['result']); ?>&nbsp;</td-->							
					<td>
					<?php if($tasks['Task']['task_status']==0){?>
						<span class="label label-primary">Not Assign</span>
					<?php }else if($tasks['Task']['task_status']==1){?>
						<span class="label label-info">Assigned</span>	
					<?php }else if($tasks['Task']['task_status']==2){ ?>
						<span class="label label-warning">Submited</span>
					<?php }else if($tasks['Task']['task_status']==3){ ?>
						<span class="label label-success">Completed</span>
					<?php }?>&nbsp
					</td>
                    <td> <?php echo date('d-M-Y',strtotime($tasks['Task']['created'])); ?></td>
					 <td class="actions">
					 
						<?php echo $this->Html->link(__('Comments'), array('action' => 'comment', $tasks['Task']['task_id']),array('class'=>'btn btn-mini btn-inverse')); ?>
						<?php echo $this->Html->link(__('Document'), array('action' => 'taskdocument', $tasks['Task']['task_id']),array('class'=>'btn btn-mini btn-primary')); ?>
						
						<?php if(($user_id == $tasks['Task']['emp_id']) && ($tasks['Task']['task_status'] < 3)){ ?>
						
							<?php echo $this->Html->link(__('Assign'), array('action' => 'assign', $tasks['Task']['id']),array('class'=>'btn btn-mini btn-info')); ?>
							<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $tasks['Task']['id']),array('class'=>'btn btn-mini btn-warning')); ?>
							<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $tasks['Task']['id']), array('class'=>'btn btn-mini btn-danger'), __('Are you sure you want to delete # %s?', $tasks['Task']['id'])); ?>
						
							<?php echo $this->Form->postLink(__('Complete'), array('action' => 'completetask', $tasks['Task']['task_id'],'completed'), array('class'=>'btn btn-mini btn-success'), __('Are you sure you want to complete # %s?', $tasks['Task']['task_id'])); ?>
							
							<?php echo $this->Form->postLink(__('Move To Product'), array('action' => 'movetoproduct', $tasks['Task']['id']), array('class'=>'btn btn-mini btn-primary'), __('Are you sure you want to move in product # %s?', $tasks['Task']['id'])); ?>
						<?php } ?>
						</td>
					</tr>
					<?php $i++;  endforeach; ?>

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
	<?php } ?>
	
<?php

	$j=1; foreach ($task as $tasknew){	
?>
<div id="myModal_<?php echo $j; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Project Number: <?php echo $tasknew['Task']['task_id']; ?></h4>
		</div>
			<div class="modal-body">
			<div class="row">
				<div class="col-xs-12">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="simple-table" >
						<tbody>
						<tr><th>Project Number: </th><td><span class="label label-inverse"><?php echo h($tasknew['Task']['task_id']); ?></span></td></tr>	
						
						<tr><th>Assigned By: </th><td><?php if($tasknew['Task']['admin_id'] == 0){ echo '<span class="label label-info">'.$tasknew['NappUser']['name'].' '.$tasknew['NappUser']['lname'].'</span>'; }else{ echo  '<span class="label label-success">'.$tasknew['User']['name'].'</span>'; } ?>&nbsp;</td>	</tr>	
						
						<tr><th>Assigned To: </th>
							<td>
								<?php
									if(!empty($tasknew['TaskAssign'])){
										foreach($tasknew['TaskAssign'] as $TaskAssigns){
											echo '<span class="label label-success">'.$TaskAssigns['NappUser']['name'].' '.$TaskAssigns['NappUser']['lname'].'</span><br><br>';
										}	
									}
								?>
							</td>	
						</tr>	
						<tr>
							<th>Project Description: </th>
							<td><?php echo h($tasknew['Task']['title']); ?>&nbsp;</td>
						</tr>	
						<tr>
							<th>Background: </th>
							<td><?php echo htmlspecialchars_decode($tasknew['Task']['description']); ?>&nbsp;</td>
						</tr>
						
						<tr>
							<th>Priority Number: </th>
							<td><?php echo $tasknew['Task']['priority_number']; ?>nbsp;</td>
						</tr>
						<tr>
							<th>Results Summary: </th>
							<td><?php echo $tasknew['Task']['results_summary']; ?>&nbsp;</td>
						</tr>
						<tr>
							<th>Date Alloted on: </th>
							<td><?php echo $tasknew['Task']['alloteddate']; ?>&nbsp;</td>
						</tr>
						
						<tr>
							<th>Assigned Date: </th>
							<td>
							<?php 
							$assigned_date = $tasknew['Task']['assigned_date'];	
							if($assigned_date != '0000-00-00'){ echo $assigned_date; } 
							?>
							&nbsp;</td>
						</tr>
						
						<tr>
							<th>Deadline: </th>
							<td><?php 
							$task_completion_date = $tasknew['Task']['task_completion_date'];	
							if($task_completion_date != '0000-00-00'){ echo $task_completion_date; } 
							?>&nbsp;</td>
						</tr>
						<tr>
							<th>Priority number: </th>
							<td><?php 
							echo $tasknew['Task']['priority_number'];							
							?>&nbsp;</td>
						</tr>
						<tr>
							<th>Priority number: </th>
							<td><?php 
							echo $tasknew['Task']['priority_number'];							
							?>&nbsp;</td>
						</tr>
						<tr>
							<th>Project Started/Assigned/Deadline Date: </th>
							<td>
						
							<span class="label label-info">Project Started: <?php if($tasknew['Task']['alloteddate'] != '0000-00-00'){ echo date('d M Y',strtotime($tasknew['Task']['alloteddate'])); }else{ echo 'N/A'; } ?>&nbsp;</span> <br/><br/>
							
							<span class="label label-warning">Assigned Date: <?php if($tasknew['Task']['assigned_date'] != '0000-00-00'){ echo date('d M Y',strtotime($tasknew['Task']['assigned_date'])); }else{ echo 'N/A'; }  ?>&nbsp;</span> <br/><br/>
							
							<span class="label label-danger">Deadline Date: <?php  if($tasknew['Task']['task_completion_date'] == '0000-00-00'){  echo 'N/A'; }else{ echo date('d M Y',strtotime($tasknew['Task']['task_completion_date']));  } ?>&nbsp;</span> 
							
							</td>
						</tr>
						<tr>
							<th>Status: </th>
							<td>
								<?php if($tasknew['Task']['task_status']==0){ ?>
									<span class="label label-primary">Not Assign</span>
								<?php }else if($tasknew['Task']['task_status']==1){?>
									<span class="label label-info">Assigned</span>	
								<?php }else if($tasknew['Task']['task_status']==2){ ?>
									<span class="label label-warning">Submited</span>
								<?php }else if($tasknew['Task']['task_status']==3){ ?>
									<span class="label label-success">Completed</span>
								<?php }?>&nbsp
							</td>
						</tr>	
						<?php
							$durolab_type = $this->Session->read('durolab_type');
							if($durolab_type == 'project_enquiry'){
						?>	
						<tr>
							<th>New customer or Existing customer: </th>
							<td><?php 
							if($tasknew['Task']['is_new_old'] == 1){ echo 'New customer'; }else{  echo 'Existing customer'; }
							?>&nbsp;</td>
						</tr>
						<tr>
							<th>Client Name: </th>
							<td><?php 
							echo $tasknew['Task']['client_name'];
							?>&nbsp;</td>
						</tr>
						<tr>
							<th>Client Company Name: </th>
							<td><?php 
							echo $tasknew['Task']['client_company_name'];
							?>&nbsp;</td>
						</tr>
						<tr>
							<th>Projected Sales figure $: </th>
							<td><?php 
							echo $tasknew['Task']['projected_sales_figure'];
							?>&nbsp;</td>
						</tr>
						<tr>
							<th>What time frame does client need it in?: </th>
							<td><?php 
							echo $tasknew['Task']['time_frame'];
							?>&nbsp;</td>
						</tr>
						
						<?php } ?>	
						</tbody>
						
					</table>			
					</div>
				</div>
				</div>				
			</div>				
		</div>				
	</div>				
</div>	
<?php $j++; } ?>
</div>		
