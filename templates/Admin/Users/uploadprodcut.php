	
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Product<small><i class="ace-icon fa fa-angle-double-right"></i>Upload Product List</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
		
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Upload:<br><small>File Type : Csv</small> </label>
				<div class="col-sm-9">
					<input type="file" name="data[file]" id="upld" >
				</div>
			</div>			
					
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Upload Csv File',array('div'=>false,'label'=>false, 'class' => 'btn btn-xs btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					<?php echo $this->Html->link('Cancel','javascript:window.history.back();',array('class' => 'btn btn-xs  btn-danger'));?>

				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
jQuery(function(){ 
	$("#upld").validate({
	 expression: "if (VAL) return true; else return false;",
	message: "Please select image"
}); 
});
</script>
