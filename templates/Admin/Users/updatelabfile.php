	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Price List  <small><i class="ace-icon fa fa-angle-double-right"></i>Update <?php echo $labfile['LabFile']['label'];?></small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> File Password:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('password',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'password','placeholder'=>'File Password','value'=>$labfile['LabFile']['password']))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Upload Price list File: <br/></label>
				<div class="col-sm-9">
					<input type="file" name="filename"  id="filename" class="col-xs-10 col-sm-5" />
				</div>
			</div>				
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit',array('div'=>false,'label'=>false, 'class' => 'btn btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					<?php echo $this->Html->link('Cancel','javascript:window.history.back();',array('class' => 'btn btn-danger'));?>

				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
jQuery(function(){ 

	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',			
	});
	
	$("#password").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter  file password"
	}); 
	
	
});
</script>
