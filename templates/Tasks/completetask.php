	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<div class="page-content">
		<div class="page-header">
			<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
			<h1>DuroLab <small><i class="ace-icon fa fa-angle-double-right"></i> Writing up results summary</small>
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create('Task',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
		
			<?php echo $this->Form->input('id',array('type'=>'hidden','value'=>$task['Task']['id']))?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">writing up results summary:</label>
				<div class="col-sm-9">				
					<textarea name="data[Task][results_summary]" id="results_summary" class="ckeditor" placeholder="writing up results summary"><?php echo  $task['Task']['results_summary']; ?></textarea>
				</div>
			</div>	
			
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit',array('div'=>false,'label'=>false, 'class' => 'btn btn-mini btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					<?php echo $this->Html->link('Cancel','javascript:window.history.back();',array('class' => 'btn btn-mini btn-danger'));?>

				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
jQuery(function(){ 	
	$("#results_summaryd").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter  results summary"
	});	
});
</script>
