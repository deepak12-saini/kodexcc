	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<style>
	fieldset .new {
		background: #ddd;
	}
	</style>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Resource Requirement:  <small><i class="ace-icon fa fa-angle-double-right"></i> Send Requirement Request</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create('Purchase',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('date',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'datepicker col-xs-10 col-sm-5','id'=>'date','placeholder'=>'Date','value'=>date('Y-m-d')))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Item Detail:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('item_details',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'item_details','placeholder'=>'Item Detail'))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name of the Requisitioner:</label>
				<div class="col-sm-9">
					<?php 
					$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');		
					echo $this->Form->input('requisitioner_name',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'requisitioner_name','placeholder'=>' Name of the Requisitioner','value'=>$name))?>
				</div>
			</div>  
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Responsibility:</label>
				<div class="col-sm-9">
					<select name="data[Purchase][permitted_by]" class="col-xs-10 col-sm-5">
						<option value="">Select Employee</option>
						<?php foreach($employeArr as $employeArrs){ ?>
							<option value="<?php echo $employeArrs['NappUser']['id']; ?>"><?php echo $employeArrs['NappUser']['name'].' '.$employeArrs['NappUser']['lname']; ?></option>
						<?php  }  ?>
					</select>
				</div>
			</div>	
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9">
				<p><b>Please allow the following Items from the premises of <?php echo SITENAME; ?>:</b></p>
				</div>
			</div>	
			<fieldset class="newitem" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right " for="form-field-1"></label>
					<div class="col-sm-9"><b>Items 1</b></div>
				</div>			
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Resource Required</label>
					<div class="col-sm-9">
						<input type="text" name="resource_requirement[]" class="col-xs-10 col-sm-5" required>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Purpose/Project</label>
					<div class="col-sm-9">
						<input type="text" name="purpose_project[]" class="col-xs-10 col-sm-5" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Quantity</label>
					<div class="col-sm-9">
						<select name="quantity[]" class="col-xs-10 col-sm-5 " >
							<?php for($i=1; $i<100; $i++){?>
								<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Date</label>
					<div class="col-sm-9">
						<input type="text" name="time[]" class="col-xs-10 col-sm-5" required>
					</div>
				</div>
					<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Budget</label>
					<div class="col-sm-9">
						<input type="text" name="budget[]" class="col-xs-10 col-sm-5" required>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Remark</label>
					<div class="col-sm-9">
						<input type="text" name="remark[]" class="col-xs-10 col-sm-5" required>
					</div>
				</div>	
			</fieldset>
			<p id="addmore"></p>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9">
					<a href="#null" class="btn btn-mini btn-danger" onclick="addmore()" >Add More</a>
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
	function addmore(){
	
		var numItems = $('.newitem').length;
		
		var newdiv = 	numItems + 1 ;
		
		var newfieldset = '	<fieldset class="newitem" id="div_'+newdiv+'"><div class="form-group"><label class="col-sm-3 control-label no-padding-right " for="form-field-1"></label><div class="col-sm-9"><b>Items '+newdiv+' <a href="#null" onclick="remove('+newdiv+')" >&nbsp;&nbsp;&nbsp; Remove</a></b></div></div><div class="form-group"><label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Resource Required</label><div class="col-sm-9">	<input type="text" name="resource_requirement[]" class="col-xs-10 col-sm-5" required></div></div>					<div class="form-group"><label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Purpose/Project</label><div class="col-sm-9">	<input type="text" name="purpose_project[]" class="col-xs-10 col-sm-5" required></div></div><div class="form-group">	<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Quantity</label>	<div class="col-sm-9"><select name="quantity[]" class="col-xs-10 col-sm-5 " ><?php for($i=1; $i<100; $i++){?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?></select></div></div><div class="form-group"><label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Date</label><div class="col-sm-9"><input type="text" name="time[]" class="col-xs-10 col-sm-5" required></div></div><div class="form-group"><label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Budget</label><div class="col-sm-9"><input type="text" name="budget[]" class="col-xs-10 col-sm-5" required></div></div><div class="form-group"><label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Remark</label><div class="col-sm-9"><input type="text" name="remark[]" class="col-xs-10 col-sm-5" required>	</div></div><fieldset>';
		
		$("#addmore").append(newfieldset);
	}
	function remove(id){	
		$("#div_"+id).remove();
	}
	
jQuery(function(){ 

	
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',			
	});
	
	$("#task_id").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter task id"
	}); $("#title").validate({
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
