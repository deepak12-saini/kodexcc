	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	
	<div class="page-content">
		<div class="page-header">			
			<h1><small> Client Comments - Recent Activities</small>
			<a href="<?php echo SITEURL.'clients/comment/'.$client_id ?>" class="btn btn-mini btn-inverse" style="float:right;">Back</a>
			</h1>
		</div>
	
	<div>
		<div class="col-xs-12">	
			<div class="row">
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
