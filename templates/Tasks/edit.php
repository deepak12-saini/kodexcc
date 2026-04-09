	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>DuroLab <small><i class="ace-icon fa fa-angle-double-right"></i> Edit Task</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create('Task',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Project Description:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('title',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-10','id'=>'title','placeholder'=>'Project Description'))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Background:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('description',array('div'=>false,'label'=>false, 'class' => 'ckeditor col-xs-10 col-sm-5','id'=>'description','placeholder'=>'Background'))?>
				</div>
			</div>
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date Alloted on:</label>
				<div class="col-sm-9">
					<?php 	$dated = $task['Task']['alloteddate'];	?>
					<input type="text" id="alloteddate" name="data[Task][alloteddate]" class="datepicker col-xs-10 col-sm-5" value="<?php echo $dated; ?>" >
				</div>
			</div>	
			<?php
				$durolab_type = $this->Session->read('durolab_type');
				if($durolab_type == 'project_enquiry'){
			?>	
			<div class="form-group reminder">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> New customer or Existing customer:</label>
				<div class="col-sm-9">
					<label class="block">
						<input type="radio" name="is_new_old" <?php if($task['Task']['is_new_old'] == 1){ echo 'selected'; } ?> value="1" checked class="ace input-lg"> <span class="lbl bigger-120">  New customer </span>
					</label>					
					<label class="block">
						<input type="radio" name="is_new_old"  <?php if($task['Task']['is_new_old'] == 0){ echo 'selected'; } ?> value="0" class="ace input-lg"> <span class="lbl bigger-120"> Existing customer</span>
					</label>
				
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Client Name:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('client_name',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'client_name','placeholder'=>'Client Name'))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Client Company Name:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('client_company_name',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'client_company_name','placeholder'=>'Client Company Name'))?>
				</div>
			</div>	
				
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Projected Sales figure $:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('projected_sales_figure',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'projected_sales_figure','placeholder'=>'Projected Sales figure $'))?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">What time frame does client need it in?:</label>
				<div class="col-sm-9">
					<select name="data[Task][time_frame]" class="col-xs-10 col-sm-1">		
						<option <?php if($task['Task']['time_frame'] == '1 week'){ echo 'selected'; } ?> value="1 week">1 Week</option>
						<option <?php if($task['Task']['time_frame'] == '1 month'){ echo 'selected'; } ?> value="1 month">1 Month</option>
						<option <?php if($task['Task']['time_frame'] == '6 months'){ echo 'selected'; } ?>  value="6 months">6 Months</option>
						<option <?php if($task['Task']['time_frame'] == '12 months'){ echo 'selected'; } ?> value="12 months">12 Months</option>						
					</select>	
				</div>
			</div>
			<?php } ?>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Assigned Date:</label>
				<div class="col-sm-9">
					<?php 	$assigned_date = $task['Task']['assigned_date'];	?>
					<input type="text" id="alloteddate" name="data[Task][assigned_date]" class="datepicker col-xs-10 col-sm-5" value="<?php if($assigned_date != '0000-00-00'){ echo $assigned_date; } ?>" placeholder="Task Assign Date" >
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Deadline:</label>
				<div class="col-sm-9">
					<?php 	$task_completion_date = $task['Task']['task_completion_date'];	?>
					<input type="text" id="alloteddate" name="data[Task][task_completion_date]" class="datepicker col-xs-10 col-sm-5" value="<?php if($task_completion_date != '0000-00-00'){ echo $task_completion_date; } ?>" placeholder="Task completion Date" >
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Priority number:</label>
				<div class="col-sm-9">
					<select name="data[Task][priority_number]" class="col-xs-10 col-sm-1">		
						<option <?php if($task['Task']['priority_number'] == 1){ echo 'selected'; } ?> value="1">1</option>
						<option <?php if($task['Task']['priority_number'] == 2){ echo 'selected'; } ?> value="2">2</option>
						<option <?php if($task['Task']['priority_number'] == 3){ echo 'selected'; } ?>value="3">3</option>
						<option <?php if($task['Task']['priority_number'] == 4){ echo 'selected'; } ?> value="4">4</option>
						<option <?php if($task['Task']['priority_number'] == 5){ echo 'selected'; } ?> value="5">5</option>
					</select>	
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
