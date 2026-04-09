	
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
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Is Reminder:<br><small>Set Reminder for notification</small> </label>
				<div class="col-sm-9">
					<label class="block">
						<input type="checkbox" name="is_reminder" id="is_reminder" <?php if($ConformanceArr['Conformance']['is_reminder'] == 1){ echo 'checked'; } ?> class="ace input-lg"  value="1"  onclick = "Check()"> <span class="lbl bigger-120"></span>
					</label>
				</div>
			</div>	
			
			<div class="form-group reminder"  <?php if($ConformanceArr['Conformance']['is_reminder'] == 0){ ?>  style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Reminder Type:</label>
				<div class="col-sm-9">
					<label class="block">
						<input type="checkbox" name="is_email" id="is_email" <?php if($ConformanceArr['Conformance']['is_email'] == 1){ echo 'checked'; } ?> onclick="checkremider(1)" value="1" class="ace input-lg"> <span class="lbl bigger-120"> Email</span>
					</label>					
					<label class="block">
						<input type="checkbox" name="is_sms" id="is_sms"  <?php if($ConformanceArr['Conformance']['is_sms'] == 1){ echo 'checked'; } ?> onclick="checkremider(1)" value="1" class="ace input-lg"> <span class="lbl bigger-120"> SMS</span>
					</label>
					<label class="block">
						<input type="checkbox" name="is_both" id="is_both"  <?php if($ConformanceArr['Conformance']['is_both'] == 1){ echo 'checked'; } ?> onclick="checkremider(2)" value="1" class="ace input-lg"> <span class="lbl bigger-120"> Both</span>
					</label>
				</div>
			</div>
			
			<div class="form-group reminder" <?php if($ConformanceArr['Conformance']['is_reminder'] == 0){ ?>  style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Period:</label>
				<div class="col-sm-9">
					<select name="period" >
						
						<option <?php if($ConformanceArr['Conformance']['period'] == '30 min'){ echo 'selected'; } ?> value="30 min">Every 30 Min</option>
						<option <?php if($ConformanceArr['Conformance']['period'] == 'hour'){ echo 'selected'; } ?>value="every hour">Every 1 Hour</option>
						<option <?php if($ConformanceArr['Conformance']['period'] == 'day'){ echo 'selected'; } ?> value="every day">Every Day</option>	
							
					</select>
				</div>
			</div>
			<div class="form-group reminder" <?php if($ConformanceArr['Conformance']['send_to'] == 0){  ?>  style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Notifcation Time:</label>
				<div class="col-sm-9">
					<select name="send_to" >					
						<option <?php if($ConformanceArr['Conformance']['send_to'] == 1){ echo 'selected'; } ?> value="1">Office Work Hour</option>
						<option <?php if($ConformanceArr['Conformance']['send_to'] == 2){ echo 'selected'; } ?> value="2">24 Hour</option>
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

	function checkremider(type){
		 var is_both = document.getElementById("is_both");
		if(type ==1){
			$("#is_both").prop('checked', false);
		}else{
			if (is_both.checked) {
				$("#is_email").prop('checked', false);
				$("#is_sms").prop('checked', false);
			}	
		}	
	}	
    function Check() {
		
        var chkPassport = document.getElementById("is_reminder");
        if (chkPassport.checked) {
            $(".reminder").show();
        } else {
             $(".reminder").hide();
        }
    }
</script>	
<script type="text/javascript">
jQuery(function(){ 
	
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',	
		 minDate: 0	
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
