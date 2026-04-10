	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	
	<div class="page-content">
		<div class="page-header">			
			<h1><small> Client Comments - Recent Activities</small>
			<a href="<?php echo SITEURL.'admin/clients/add-comment/'.$client_id ?>" class="btn btn-mini btn-inverse" style="float:right;">Add Comment</a>
			</h1>
		</div>
	
	<div>
		<div class="col-xs-12">	
			<!--div class="row">
				<div class="col-xs-12" style="border-bottom: 1px solid #e2e2e2 !important;border-left: 1px solid #e2e2e2 !important;	border-right: 1px solid #e2e2e2 !important;">
				<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
					
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
			</div-->	
		
			
			<div id="user-profile-12" class="user-profile row-fluid">
				<div class="span9">								

					<div class="widget-box transparent">
					
					<div class="widget-body">
						<div class="widget-main padding-8">
							<div id="profile-feed-1" class="profile-feed">
								<?php
								
									
									if(!empty($clientArr)){
									foreach($clientArr as $TaskAssigns){
									
									if($TaskAssigns['ClientComment']['type'] == 1){
										$name = $TaskAssigns['User']['name'] ?? '';
									}else{
										$name = ($TaskAssigns['NappUser']['name'] ?? '').' '.($TaskAssigns['NappUser']['lname'] ?? '');
									}	
									
								?>
								<div class="profile-activity clearfix">
									<div>
										<?php if($TaskAssigns['ClientComment']['type'] == 1){ ?>
											<i class="pull-left thumbicon icon-picture btn-info no-hover"></i>
										<?php }else{ ?>
											<i class="pull-left thumbicon icon-picture btn-warning no-hover"></i>
										<?php } ?>
										<a class="user" href="#"> <?php echo $name; ?>: </a>
										<?php echo $TaskAssigns['ClientComment']['comment']; ?>
										<?php if(!empty($TaskAssigns['ClientComment']['documents'])){ ?>
											<a href="<?php echo SITEURL.'admin/clients/comment-download/'.base64_encode(base64_encode($TaskAssigns['ClientComment']['documents']));?>"><i class="fa fa-download"></i></a>
										<?php } ?>
										<div class="time">
											<i class="icon-time bigger-110"></i>
											<?php  
												echo date('d M Y h:i a',strtotime($TaskAssigns['ClientComment']['created']));
											?>
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
								<?php } }else{ ?>
								<div class="profile-activity clearfix">
									<div>
										<p> No C   omment Found</p>
									</div>
								</div>
								<?php } ?>
								</div>
							</div>
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
