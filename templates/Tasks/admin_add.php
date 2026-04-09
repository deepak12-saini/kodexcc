	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>DuroLab  <small><i class="ace-icon fa fa-angle-double-right"></i> Add Task</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create('Task',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
			
			
			<!--div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Select Type:</label>
				<div class="col-sm-9">
					<select name="data[Task][task_type]" class = 'col-xs-10 col-sm-5' >
						<option value="product">Product-Duro</option>
						<option value="technical">Technical Service-Duro</option>
					</select>
				</div>
			</div-->	
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> TaskId:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('task_id',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'task_id','placeholder'=>'Task Title','value'=>$foldername))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee:<br><small>Assign Task to Employee</small> </label>
				<div class="col-sm-9">
					<?php foreach($employeArr as $employes){ ?>
					
						<label class="block">
							<input class="ace input-lg"  value="<?php echo $employes['NappUser']['id']; ?>" name="employe_id[]" type="checkbox">
							<span class="lbl bigger-120"> <?php echo $employes['NappUser']['name'].' '.$employes['NappUser']['lname']; ?></span>
						</label>
					<div style="clear:both;"></div>
					<?php } ?>					
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('title',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'title','placeholder'=>'Task Title'))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('description',array('div'=>false,'label'=>false, 'class' => 'ckeditor col-xs-10 col-sm-5','id'=>'description','placeholder'=>'Description'))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date Alloted on:</label>
				<div class="col-sm-9">
					<?php 	$dated = date('Y-m-d');	?>
					<input type="text" id="alloteddate" name="data[Task][alloteddate]" class="datepicker col-xs-10 col-sm-5" value="<?php echo $dated; ?>" >
				</div>
			</div>	
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Assigned By:</label>
				<div class="col-sm-9">
					<?php 
					$name = $this->Session->read('User.name');
					echo $this->Form->input('assignedby',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'assignedto','placeholder'=>'Assigned By','value'=>$name,'readonly'=>true))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Would you like to notify the assigned person via email?:</label>
				<div class="col-sm-9">
					<label class="block">
						<input type="radio" name="data[Task][is_notify]"  value="1" checked class="ace input-lg"> <span class="lbl bigger-120">  Yes </span>
					</label>					
					<label class="block">
						<input type="radio" name="data[Task][is_notify]"  value="0" class="ace input-lg"> <span class="lbl bigger-120"> No</span>
					</label>
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
						<input type="radio" name="is_new_old" value="1" checked class="ace input-lg"> <span class="lbl bigger-120">  New customer </span>
					</label>					
					<label class="block">
						<input type="radio" name="is_new_old"  value="0" class="ace input-lg"> <span class="lbl bigger-120"> Existing customer</span>
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
						<option value="1 week">1 Week</option>
						<option value="1 month">1 Month</option>
						<option value="6 months">6 Months</option>
						<option value="12 months">12 Months</option>						
					</select>	
				</div>
			</div>
			<?php } ?>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Upload Document: <br/> <small>if you want to upload multiple files</small></label>
				<div class="col-sm-9">
					<input type="file" name="document[]" multiple class="col-xs-10 col-sm-5" />
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
