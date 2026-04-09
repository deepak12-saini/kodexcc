	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	
	<div class="page-content">
		<div class="page-header">
			<div class="right_btn pull-right" ><a href="<?php echo SITEURL.'tasks'?>" class="btn btn-inverse" >Back</a></div>
			<h1>DuroLab <small><i class="ace-icon fa fa-angle-double-right"></i> Comments</small>
			</h1>
		</div>
	
	<div>
		<div class="col-xs-9">	
			<div class="row">
			<div class="col-xs-12" style="border-bottom: 1px solid #e2e2e2 !important;border-left: 1px solid #e2e2e2 !important;	border-right: 1px solid #e2e2e2 !important;">
			<?php echo $this->Form->create('Task',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Comments: <br/> <small></small></label>
					<div class="col-sm-9">
						<textarea name="data[comment]" class="col-xs-10 col-sm-5" placeholder="Write your comments here"></textarea>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Upload Document: </label>
					<div class="col-sm-9">
						<input type="file" name="documents" class="col-xs-10 col-sm-5" />
					</div>
				</div>				
				<div class="form-group">
					<div class="col-md-offset-3 col-md-9">
						<?php echo $this->Form->submit('Submit',array('div'=>false,'label'=>false, 'class' => 'btn btn-xs btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
						<?php echo $this->Html->link('Cancel','javascript:window.history.back();',array('class' => 'btn btn-xs btn-danger'));?>

					</div>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>	
		
			
		<div id="user-profile-12" class="user-profile row-fluid">
		<div class="span9">								

			<div class="widget-box transparent">
				<div class="widget-header widget-header-small">
					<h4 class="blue smaller">
						<i class="icon-rss orange"></i>
						Recent Activities
					</h4>

				</div>

				<div class="widget-body">
					<div class="widget-main padding-8">
						<div id="profile-feed-1" class="profile-feed">
							<?php
								
								if(!empty($TaskAssign['TaskComment'])){
								foreach($TaskAssign['TaskComment'] as $TaskAssigns){
									
								
								$name = '';
								if($TaskAssigns['type'] == 1){
									$name = $TaskAssigns['User']['name'];
								}else{
									if(!empty($TaskAssigns['NappUser']['name'])){
										$name = $TaskAssigns['NappUser']['name'].' '.$TaskAssigns['NappUser']['name'];
									}else{
										$name = '';
									}
								}	
								
								
							?>
							<div class="profile-activity clearfix">
								<div>
									<i class="pull-left thumbicon icon-picture btn-info no-hover"></i>
									<a class="user" href="#"> <?php echo $name; ?>: </a>
									<?php echo $TaskAssigns['comment']; ?>
									<?php if(!empty($TaskAssigns['documents'])){ ?>
										<a href="<?php echo SITEURL.'tasks/commentdownload/'.base64_encode(base64_encode($TaskAssigns['id']));?>"><i class="fa fa-download"></i></a>
									<?php } ?>
									<div class="time">
										<i class="icon-time bigger-110"></i>
										<?php  
										echo date('d M Y h:i a',strtotime($TaskAssigns['created']));
										//$created = base64_encode($TaskAssigns['created']);									
										//$time_elapsed_string = $this->requestAction('/tasks/time_elapsed_string/'.$created); ?>
									</div>
								</div>
								<div class="tools action-buttons">
									<a href="#" class="blue">
										<i class="icon-pencil bigger-125"></i>
									</a>

									<a href="#" class="red">
										<i class="icon-remove bigger-125"></i>
									</a>
								</div>
							</div>
							<?php } } ?>
						</div>
					</div>
				</div>
			</div>

		</div>
		</div>
		</div>
		<div class="col-xs-3">	
		<div class="span6 widget-container-span ui-sortable">
		<div class="widget-box">
			<div class="widget-header">
				<h5>Task Documents <a href="<?php echo SITEURL.'tasks/uploaddoc/'.$TaskAssign['Task']['task_id'] ?>" class="btn btn-mini btn-warning" style="float:right;">Upload Doc</a></h5>
			</div>
			<div class="widget-body">
				<div class="widget-main">
					<?php
					if(!empty($TaskAssign['TaskDocument'])){
					$i=0;
					foreach($TaskAssign['TaskDocument'] as $TaskDocument){
					if($i % 2 ==0){
					?>
					<p class="alert alert-info">
						<a href="<?php echo SITEURL.'tasks/download/'.$TaskDocument['id']; ?>" target="blank">Download Document</a>&nbsp;
						<br>
						<small>
						<?php 
						if($TaskDocument['admin_id'] == 0){ echo '<span class="label label-info">'.$TaskDocument['NappUser']['name'].' '.$TaskDocument['NappUser']['lname'].'</span>'; }else{ echo  '<span class="label label-success">'.$TaskDocument['User']['name'].'</span>'; }
						
						
						//echo $TaskDocument['NappUser']['name'].' '.$TaskDocument['NappUser']['lname']; ?> (<?php echo date('d M Y h:i a',strtotime($TaskDocument['created'])); ?>)</small>
					</p>
					<?php }else{ ?>
					<p class="alert alert-success">
						<a href="<?php echo SITEURL.'tasks/download/'.$TaskDocument['id']; ?>" target="blank">Download Document</a>&nbsp;
						<br>
						<small>
						<?php 
						if($TaskDocument['admin_id'] == 0){ echo '<span class="label label-info">'.$TaskDocument['NappUser']['name'].' '.$TaskDocument['NappUser']['lname'].'</span>'; }else{ echo  '<span class="label label-success">'.$TaskDocument['User']['name'].'</span>'; }
						//echo $TaskDocument['NappUser']['name'].' '.$TaskDocument['NappUser']['lname']; ?> (<?php echo date('d M Y h:i a',strtotime($TaskDocument['created'])); ?>)</small>
					</p>
					<?php } $i++; } } ?>
				</div>
			</div>
		</div>
		</div>
		</div>
	</div>	
</div>	
<script type="text/javascript">
jQuery(function(){ 

	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',			
	});
	
	$("#title").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter  employe id"
	}); $("#assignedto").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select employee"
	}); $("#assignedby").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please eneter assigned by user"
	}); 
	
	
});
</script>
