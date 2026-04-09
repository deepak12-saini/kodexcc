	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Product<small><i class="ace-icon fa fa-angle-double-right"></i> Add Product Project</small>
		</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
	
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'role' => 'form', 'type' => 'file']); ?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">upload Product Image : </label>
				<div class="col-sm-9">
					<input type="file" name="data[Product][image]" id="image" class="col-xs-10 col-sm-5">

				</div>
			</div>										
							
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Upload',array('div'=>false,'label'=>false, 'class' => 'btn btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					<?php echo $this->Html->link('Cancel','javascript:window.history.back();',array('class' => 'btn btn-danger'));?>

				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>

<script type="text/javascript">
jQuery(function(){ 
	$("#image").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select image"
});
});
</script>
