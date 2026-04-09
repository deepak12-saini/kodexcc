	
	<style>
		.btn{margin-bottom: 5px; }
		.label-danger, .label.label-danger, .badge.badge-danger, .badge-danger {
			margin-bottom: 5px;
		}
	</style>
	
	<div class="page-content">
	<div class="page-header">
		<h1>Non Conformance List			
			<form action="" method="post" style="float:right;">
				<input type="text" name="nc_number" value="<?php echo $nc_number; ?>" placeholder="Search Nc Number " >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs" >	
				<a href="<?php echo SITEURL.'admin/staffs/conformancelist'?>" class="btn btn-warning btn-xs">Show All</a>
			</form>		
		</h1>
	</div>
	
	
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>			
					<th><?php echo $this->Paginator->sort('nc_number','NCR-Number'); ?></th>							
					<th><?php echo $this->Paginator->sort('compaint_by','Assigned By'); ?></th>				
					<th><?php echo $this->Paginator->sort('compaint_to','Issue To'); ?></th>
					<th><?php echo $this->Paginator->sort('Investigation By'); ?></th>
					
					<th><?php echo $this->Paginator->sort('counter'); ?></th>
					<th><?php echo $this->Paginator->sort('timetaken','Time Taken'); ?></th>		
					<th><?php echo $this->Paginator->sort('is_reminder'); ?></th>				
					<th><?php echo $this->Paginator->sort('status'); ?></th>				
					<th><?php echo $this->Paginator->sort('created'); ?></th>				
					<th>Action</th>				
					
				</tr>
				</thead>
				<tbody>
				<?php 
				$k=1;
				foreach ($ConformanceArr as $conformance): 
				?>
					<tr>
						<td><a data-toggle="modal" data-target="#myModal_<?php echo $k; ?>" href="#null"><?php echo h($conformance['Conformance']['nc_number']); ?></a></td>						
						<td><?php echo '<span class="label label-info">'.$conformance['NappUser1']['name'].' '.$conformance['NappUser1']['lname'].'</span>'; ?></td>				
								
						<td>
						<?php
						foreach($conformance['ConformanceRelation'] as $napuser){
							if($napuser['NappUser']['id'] != $conformance['NappUser1']['id']){
								echo '<span class="label label-info">'.$napuser['NappUser']['name'].' '.$napuser['NappUser']['lname'].'</span>';
							}
						}
						?>
						
						</td>	
						<td>
							<?php
							if(!empty($UserArr)){ foreach($UserArr as $UserArrs){
								echo '<span class="label label-info" style="margin-bottom:5px;">'.$UserArrs['User']['name'].'</span>';
							} }
							?>
						</td>		
						<!--td>
						<?php 
						if($conformance['Conformance']['status'] == 3){
						
							$assigndatetime = date('M d, Y h:i:s',strtotime($conformance['Conformance']['created']));
						
							$then = new DateTime($assigndatetime);
							$now = new DateTime(); 
							$sinceThen = $then->diff($now);
							
							$d = $sinceThen->d;
							$m = $sinceThen->m;
							$h = $sinceThen->h;
							$i = $sinceThen->i;
							$s = $sinceThen->s;
							
							$date = $d.'d '.$h.'h '.$i.'m '.$s.'s';
							echo '<span class="label label-success">'.$date.'</span>';	
						}else{ 
						?>
							<span class="label label-danger" id="demo_<?php echo $i; ?>">N/A</span>
							<script>
							<?php
							if($conformance['Conformance']['complete_date'] != '0000-00-00'){  
							$assigndatetime = date('M d, Y h:i:s',strtotime($conformance['Conformance']['created'].' +24 hours'));
							?>
							// Set the date we're counting down to
							var countDownDate_<?php echo $i; ?> = new Date("<?php echo date('M d, Y 10:00:00',strtotime($assigndatetime))?>").getTime();

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
						</td-->		
						<td>
						<?php 
						if($conformance['Conformance']['status'] == 3){
						
							$assigndatetime = date('M d, Y H:i:s',strtotime($conformance['Conformance']['created']));
							$complete_date = date('M d, Y H:i:s',strtotime($conformance['Conformance']['complete_date']));

							$then = new DateTime($assigndatetime);
							$now = new DateTime($complete_date); 
							$sinceThen = $then->diff($now);
							
							$d = $sinceThen->d;
							$m = $sinceThen->m;
							$h = $sinceThen->h;
							$i = $sinceThen->i;
							$s = $sinceThen->s;
							
							$date = $d.'d '.$h.'h '.$i.'m '.$s.'s';
							echo '<span class="label label-success">'.$date.'</span>';	
						}else{ 
							$complete_date  = date('M d, Y H:i:s',strtotime($conformance['Conformance']['created'].' +24 hours'));
							$assigndatetime = date('M d, Y H:i:s');
							if(strtotime($complete_date) > strtotime($assigndatetime)){	
							$then = new DateTime($assigndatetime);
							$now = new DateTime($complete_date); 
							$sinceThen = $then->diff($now);
							
							$d = $sinceThen->d;
							$m = $sinceThen->m;
							$h = $sinceThen->h;
							$i = $sinceThen->i;
							$s = $sinceThen->s;
							
							$date = $d.'d '.$h.'h '.$i.'m '.$s.'s';
							echo '<span class="label label-success">'.$date.'</span>';	
							}else{
								echo '<span class="label label-danger">EXPIRED</span>';	
							}	
						}
						
						/*
						?>
							<span class="label label-danger" id="demo_<?php echo $i; ?>">N/A</span>
							<script>
							<?php
							//Apr 27, 2018 13:26:08
							if($conformance['Conformance']['complete_date'] != '0000-00-00'){  
							$assigndatetime = date('M d, Y H:i:s',strtotime($conformance['Conformance']['created'].' +24 hours'));
							
							?>
							// Set the date we're counting down to
							var countDownDate_<?php echo $i; ?> = new Date("<?php echo $assigndatetime ?>").getTime();
							
							// Update the count down every 1 second
							var x = setInterval(function() {
								// Get todays date and time
								var nows_<?php echo $i; ?>  = new Date().getTime(); 
								
								// Find the distance between now an the count down date
								var distance_<?php echo $i; ?> = countDownDate_<?php echo $i; ?> - nows_<?php echo $i; ?>;    
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
						<?php } */ ?>
						</td>							
						<td>
							<?php 
							
							if($conformance['Conformance']['status'] == 3){

								$assigndatetime = date('M d, Y H:i:s',strtotime($conformance['Conformance']['created']));
								$complete_date = date('M d, Y H:i:s',strtotime($conformance['Conformance']['complete_date']));

								$then = new DateTime($assigndatetime);
								$now = new DateTime($complete_date); 
								$sinceThen = $then->diff($now);
								
								$d = $sinceThen->d;
								$m = $sinceThen->m;
								$h = $sinceThen->h;
								$i = $sinceThen->i;
								$s = $sinceThen->s;
								
								$date = $d.'d '.$h.'h '.$i.'m '.$s.'s';
								echo '<span class="label label-success">'.$date.'</span>';	
							}else{ 
								$assigndatetime = date('M d, Y H:i:s',strtotime($conformance['Conformance']['created']));
								$complete_date = date('M d, Y H:i:s');

								$then = new DateTime($assigndatetime);
								$now = new DateTime($complete_date); 
								$sinceThen = $then->diff($now);
								
								$d = $sinceThen->d;
								$m = $sinceThen->m;
								$h = $sinceThen->h;
								$i = $sinceThen->i;
								$s = $sinceThen->s;
								
								$date = $d.'d '.$h.'h '.$i.'m '.$s.'s';
								echo '<span class="label label-warning">'.$date.'</span>';	
							}
							/*
							?>
						
						
							<span class="label label-warning" id="demo_new_<?php echo $i; ?>">N/A</span>
							<script>
							
								window.onload=function() {
								  // Month,Day,Year,Hour,Minute,Second
								  upTime_<?php echo $i; ?>('<?php echo date('M,d,Y,H:i:s',strtotime($conformance['Conformance']['created']))?>'); // ****** Change this line!
								}
								function upTime_<?php echo $i; ?>(countTo) {
								  nownow_<?php echo $i; ?> = new Date();
								  countTo_<?php echo $i; ?> = new Date(countTo_<?php echo $i; ?>);
								  difference = (nownow_<?php echo $i; ?> - countTo_<?php echo $i; ?>);

								   var days=Math.floor(difference/(60*60*1000*24)*1);
								   var hours=Math.floor((difference%(60*60*1000*24))/(60*60*1000)*1);
								   var mins=Math.floor(((difference%(60*60*1000*24))%(60*60*1000))/(60*1000)*1);
								   var secs=Math.floor((((difference%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1);
								  // var finaltime = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
								   
								   document.getElementById("demo_new_<?php echo $i; ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
								   
								   clearTimeout(upTime.to);
								  upTime.to=setTimeout(function(){ upTime(countTo); },1000);
								}
								var countDownDatenew_<?php echo $i; ?> = '<?php echo date('M d, Y H:i:s',strtotime($conformance['Conformance']['created']))?>';
								countDownDatenew_<?php echo $i; ?> = new Date(countDownDatenew_<?php echo $i; ?>);
								
								// Update the count down every 1 second
								var y = setInterval(function() {

									// Get todays date and time
									var now_<?php echo $i; ?> = new Date().getTime();
									
									//var now_<?php echo $i; ?> = new Date('<?php echo date('M d, Y H:i:s') ?>').getTime();
									//now_<?php echo $i; ?> = parseInt(now_<?php echo $i; ?>);
									//alert(now_<?php echo $i; ?>);
									// Find the distance between now an the count down date
									var distance_<?php echo $i; ?> = now_<?php echo $i; ?> - countDownDatenew_<?php echo $i; ?>.getTime();

									// Time calculations for days, hours, minutes and seconds
									var days = Math.floor(distance_<?php echo $i; ?> / (1000 * 60 * 60 * 24));
									var hours = Math.floor((distance_<?php echo $i; ?> % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
									var minutes = Math.floor((distance_<?php echo $i; ?> % (1000 * 60 * 60)) / (1000 * 60));
									var seconds = Math.floor((distance_<?php echo $i; ?> % (1000 * 60)) / 1000);

									// Output the result in an element with id="demo"
									document.getElementById("demo_new_<?php echo $i; ?>").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
									
								}, 1000); 
							</script>


							<?php } */ ?>

						</td>				
						<td>
							<?php if($conformance['Conformance']['status'] == 1){ echo '<span class="label label-danger"> Pending</span>'; } else if($conformance['Conformance']['status'] == 2){ echo '<span  class="label label-warning"> In-Process</span>'; }else if($conformance['Conformance']['status'] == 3){ echo '<span  class="label label-success"> Completed</span>'; } ?>
						</td>	
						<td>
							<?php if($conformance['Conformance']['is_reminder'] == 1){ echo '<span class="label label-success"> Reminder: '.$conformance['Conformance']['period'].' </span>'; } else if($conformance['Conformance']['is_reminder'] == 0){ echo '<span  class="label label-danger"> No Reminder</span>'; } ?>
						</td>							
						<td><?php echo h($conformance['Conformance']['created']); ?></td>	
						
						<td>
							<a href="<?php echo SITEURL.'admin/staffs/detail/'.base64_encode($conformance['Conformance']['nc_number']).'/1'; ?>" class="btn btn-mini btn-danger ">View</a>
							<?php if($conformance['Conformance']['status'] < 3){?>
								<a href="<?php echo SITEURL.'admin/staffs/replyto/'.base64_encode($conformance['Conformance']['id']); ?>" class="btn btn-mini btn-primary to ">Reply</a>
							<?php }else{ echo 'N/A'; } ?>
							<?php if(($conformance['Conformance']['compaint_by'] == $user_id) && ($conformance['Conformance']['status'] <= 2)){ ?>		
								<a href="<?php echo SITEURL.'admin/staffs/edit/'.base64_encode($conformance['Conformance']['id']).'/'.base64_encode($user_id); ?>" class="btn btn-mini btn-info">Edit</a>
							<?php } ?>
							<?php if($conformance['Conformance']['status'] <= 2){ ?>
								<a href="<?php echo SITEURL.'admin/staffs/reminder/'.base64_encode($conformance['Conformance']['id']).'/'.base64_encode($user_id); ?>" class="btn btn-mini btn-success">Reminder</a>
							<?php } ?>
							
						</td>							
                    				
					</tr>
					
					
					<?php $k++;  endforeach; ?>

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
	
	<!-- Modal -->
	<?php 
	$j=1;
	foreach ($ConformanceArr as $conformance): 
	
	?>
	<div id="myModal_<?php echo $j; ?>" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Non Conformance: <?php echo h($conformance['Conformance']['nc_number']); ?></h4>
		  </div>
		  <div class="modal-body">
			<div class="row">
			<div class="col-xs-12">
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover"  >
					<tbody>
					<tr><th>NCR-Number: </th><td><span class="label label-inverse"><?php echo h($conformance['Conformance']['nc_number']); ?></span></td></tr>	
					
					<tr><th>Assigned By:</th><td><?php echo '<span class="label label-info">'.$conformance['NappUser1']['name'].' '.$conformance['NappUser1']['lname'].'</span>'; ?></td><tr>					
					<tr><th>Issue To:</th><td>
							<?php
							foreach($conformance['ConformanceRelation'] as $napuser){
						if($napuser['NappUser']['id'] != $conformance['NappUser1']['id']){
							echo '<span class="label label-info">'.$napuser['NappUser']['name'].' '.$napuser['NappUser']['lname'].'</span>';
						}
					}?></td></tr>
					<tr><th>Investigation By:</th>
						<td>
						<?php
							if(!empty($UserArr)){ foreach($UserArr as $UserArrs){
								echo '<span class="label label-info" style="margin-bottom:5px;">'.$UserArrs['User']['name'].'</span><br>';
							} }								
						?>
						</td>
					</tr>		
					
					
							
					<tr><th>Non Conformance Description:</th><td><?php echo h($conformance['Conformance']['non_conforance']); ?></td><tr>					
					<!--tr><th>Non Conformance Raised</th><td><?php echo h($conformance['Conformance']['non_conforance_raised']); ?></td><tr-->						
											
					<tr><th>Department:</th><td><?php echo h($conformance['Department']['department_title']); ?></td><tr>				
					<!--tr><th>Aspects Examined</th><td><?php echo h($conformance['Conformance']['aspects_examined']); ?></td><tr-->					
					

					<tr><th>Route Cause and Analysis:</th>
					<td>
					<?php if(!empty($conformance['Conformance']['why_1'])){ echo '<p>1. '. $conformance['Conformance']['why_1'].'</p>'; } ?>
					<?php if(!empty($conformance['Conformance']['why_2'])){ echo '<p>2. '. $conformance['Conformance']['why_2'].'</p>'; } ?>
					<?php if(!empty($conformance['Conformance']['why_3'])){ echo '<p>3. '. $conformance['Conformance']['why_3'].'</p>'; } ?>
					<?php if(!empty($conformance['Conformance']['why_4'])){ echo '<p>4. '. $conformance['Conformance']['why_4'].'</p>'; } ?>
					<?php if(!empty($conformance['Conformance']['why_5'])){ echo '<p>5. '. $conformance['Conformance']['why_5'].'</p>'; } ?>
					
					<?php if(!empty($conformance['Conformance']['is_corrective'])){ ?><br><br>
							<b>Posted By : <?php echo $conformance['Conformance']['is_corrective']; ?></b>
						<?php } ?>
					</td>
					</tr>
					<tr>
						
					<tr>
						<th>Corrective Action Taken</th>
						<td>
						<?php echo h($conformance['Conformance']['corrective_action_taken']); ?>
							
						<?php if(!empty($conformance['Conformance']['is_corrective'])){ ?><br><br>
							<b>Posted By : <?php echo $conformance['Conformance']['is_corrective']; ?></b>
						<?php } ?>
						</td>
					</tr>					
					<tr><th>Short Term / Containment Action:</th><td><?php echo h($conformance['Conformance']['short_term']); ?></td><tr>
					<tr><th>Follow Up Investigation / Continuous Improvement:</th><td><?php echo h($conformance['Conformance']['follow_up']); ?></td><tr>
					<tr><th>Corrective Action Successfull:</th><td><?php if($conformance['Conformance']['corrective_action_successfull'] == 1){ echo 'Yes'; }else{ echo 'No'; } ?></td><tr>
					<tr><th>Supporting Documents Attached:</th><td><?php if($conformance['Conformance']['support_document'] == 1){ echo 'Yes'; }else{ echo 'No'; } ?></td><tr>
					
					<tr><th>Management</th><td><?php echo $conformance['Conformance']['management_representive']; ?>
							<?php if(!empty($conformance['User']['name'])){ ?><br><br>
								<b style="color:red;"><?php echo $conformance['User']['name']; ?> (Management)</b>
							<?php } ?></b>
						
					</td></tr>
					<tr><th>Customer Name</th><td><?php echo h($conformance['Conformance']['customer_name']); ?></td><tr>
					<tr><th>Client Deatil</th><td><?php echo h($conformance['Conformance']['client_detail']); ?></td><tr>
					<tr><th>Other Deatil</th><td><?php echo h($conformance['Conformance']['other_detail']); ?></td><tr>
					
					
					<tr>
					<th>Download Doc</th>
					<td>
					<?php if(!empty($conformance)){ $i=1; foreach($conformance['TaskDocument'] as $ComplaintArrs){ ?>
						<a href="<?php echo SITEURL.'tasks/download/'.$ComplaintArrs['id']; ?>" class="label label-danger" target="_blank">Download <?php echo $i; ?></a><br/>
					<?php $i++;  } } ?>
					</td>
					</tr>
					<tr><th>Status</th>
					<td>
						<?php if($conformance['Conformance']['status'] == 1){ echo '<span class="label label-danger"> Pending</span>'; } else if($conformance['Conformance']['status'] == 2){ echo '<span  class="label label-warning"> In-Process</span>'; }else if($conformance['Conformance']['status'] == 3){ echo '<span  class="label label-success"> Completed</span>'; } ?>
					</td>
					</tr>
					<tr><th>Created</th>
					<td>
						<?php echo h($conformance['Conformance']['created']); ?>
					</td>
					</tr>
					</tbody>
					
				</table>			
				</div>
			</div>
		</div>		
		  </div>
		 
		</div>

	  </div>
	</div>
	<?php $j++; endforeach; ?>
</div>		


