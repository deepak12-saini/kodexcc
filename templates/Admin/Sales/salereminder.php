<?php
$sr = $SaleReminder['SaleReminder'] ?? [];
?>
	<div class="page-content">
		<div class="page-header">
			<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
			<h1>DuroTeam <small><i class="ace-icon fa fa-angle-double-right"></i> Set Sales Reminder</small>
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
					
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> First Reminder:<br><small>Set Reminder for notification</small> </label>
				<div class="col-sm-9">
					<label class="block">
						<textarea name="first_reminder" placeholder="" style="width:300px" placeholder="First Reminder"><?php echo h($sr['first_reminder'] ?? ''); ?></textarea>
						
						<p>Send at 
						<select name="f_time">						
							<option <?php if (($sr['f_time'] ?? '') == '06'){ echo 'selected'; } ?> value="06">06 am</option>
							<option  <?php if (($sr['f_time'] ?? '') == '07'){ echo 'selected'; } ?> value="07">07 am</option>
							<option  <?php if (($sr['f_time'] ?? '') == '08'){ echo 'selected'; } ?> value="08">08 am</option>
							
							<option  <?php if (($sr['f_time'] ?? '') == '12'){ echo 'selected'; } ?> value="12">12 pm</option>
							<option  <?php if (($sr['f_time'] ?? '') == '13'){ echo 'selected'; } ?> value="13">01 pm</option>
							<option  <?php if (($sr['f_time'] ?? '') == '14'){ echo 'selected'; } ?> value="14">02 pm</option>
							
							<option  <?php if (($sr['f_time'] ?? '') == '17'){ echo 'selected'; } ?> value="17">05 pm</option>
							<option  <?php if (($sr['f_time'] ?? '') == '18'){ echo 'selected'; } ?> value="18">06 pm</option>
							<option  <?php if (($sr['f_time'] ?? '') == '19'){ echo 'selected'; } ?> value="19">07 pm</option>
						
						</select>
						
						</p>
					</label>
				</div>
			</div>	 		
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Second Reminder:<br><small>Set Reminder for notification</small> </label>
				<div class="col-sm-9">
					<label class="block">
						<textarea name="second_reminder" placeholder="" style="width:300px" placeholder="Second Reminder"><?php echo h($sr['second_reminder'] ?? ''); ?></textarea>
						
						<p>Send at 
						<select name="s_time">						
							<option <?php if (($sr['s_time'] ?? '') == '06'){ echo 'selected'; } ?> value="06">06 am</option>
							<option  <?php if (($sr['s_time'] ?? '') == '07'){ echo 'selected'; } ?> value="07">07 am</option>
							<option  <?php if (($sr['s_time'] ?? '') == '08'){ echo 'selected'; } ?> value="08">08 am</option>
							
							<option  <?php if (($sr['s_time'] ?? '') == '12'){ echo 'selected'; } ?> value="12">12 pm</option>
							<option  <?php if (($sr['s_time'] ?? '') == '13'){ echo 'selected'; } ?> value="13">01 pm</option>
							<option  <?php if (($sr['s_time'] ?? '') == '14'){ echo 'selected'; } ?> value="14">02 pm</option>
							
							<option  <?php if (($sr['s_time'] ?? '') == '17'){ echo 'selected'; } ?> value="17">05 pm</option>
							<option  <?php if (($sr['s_time'] ?? '') == '18'){ echo 'selected'; } ?> value="18">06 pm</option>
							<option  <?php if (($sr['s_time'] ?? '') == '19'){ echo 'selected'; } ?> value="19">07 pm</option>
						</select>
						
						</p>
						
					</label>
				</div>
			</div>	
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Third Reminder:<br><small>Set Reminder for notification</small> </label>
				<div class="col-sm-9">
					<label class="block">
						<textarea name="third_reminder" placeholder="" style="width:300px"  placeholder="First Reminder"><?php echo h($sr['third_reminder'] ?? ''); ?></textarea>
						
						<p>Send at 
						<select name="t_time">						
							<option <?php if (($sr['t_time'] ?? '') == '06'){ echo 'selected'; } ?> value="06">06 am</option>
							<option  <?php if (($sr['t_time'] ?? '') == '07'){ echo 'selected'; } ?> value="07">07 am</option>
							<option  <?php if (($sr['t_time'] ?? '') == '08'){ echo 'selected'; } ?> value="08">08 am</option>
							
							<option  <?php if (($sr['t_time'] ?? '') == '12'){ echo 'selected'; } ?> value="12">12 pm</option>
							<option  <?php if (($sr['t_time'] ?? '') == '13'){ echo 'selected'; } ?> value="13">01 pm</option>
							<option  <?php if (($sr['t_time'] ?? '') == '14'){ echo 'selected'; } ?> value="14">02 pm</option>
							
							<option  <?php if (($sr['t_time'] ?? '') == '17'){ echo 'selected'; } ?> value="17">05 pm</option>
							<option  <?php if (($sr['t_time'] ?? '') == '18'){ echo 'selected'; } ?> value="18">06 pm</option>
							<option  <?php if (($sr['t_time'] ?? '') == '19'){ echo 'selected'; } ?> value="19">07 pm</option>
						</select>
						
						</p>
						
					</label>
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
