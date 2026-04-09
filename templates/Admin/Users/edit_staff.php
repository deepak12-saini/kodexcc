<div class="page-content">
	<div class="page-header">
	<h1>
	Durotech Employee 
	<small>
	<i class="ace-icon fa fa-angle-double-right"></i>
		Edit Employee
	</small>
	
	<a href="<?php echo SITEURL; ?>admin/users/staff" class="btn btn-mini btn-inverse" style="float:right;">Back</a>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<?php echo $this->Form->create(null,array('class'=>'form-horizontal')); ?>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Select Department: </label>
					<div class="col-sm-9">
						<select id="dept_id" name="data[NappUser][dept_id]" class="col-xs-10 col-sm-5" >
							<option value="">Select Department</option>
							<?php foreach($departmentArr as $departments){ ?>
								<option <?php  if($departments['Department']['id'] == $staffArr['NappUser']['dept_id']){ echo 'selected'; } ?> value="<?php echo $departments['Department']['id'];?>"><?php echo $departments['Department']['department_title'];?></option>
							<?php  }  ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Name: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('NappUser.name',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'name','placeholder'=>'Enter Name'))?>
					</div>
				</div>
			
				<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email: </label>
						<div class="col-sm-9">
							<?php echo $this->Form->input('NappUser.email',array('type'=>'email','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'email','placeholder'=>'Email'))?>
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone Number: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('NappUser.mobile_number',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'phone','placeholder'=>'Phone Number'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Production App Login Key: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('NappUser.productionlogin_key',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'productionlogin_key','placeholder'=>'Production App Login Key'))?>
					</div>
				</div>
				
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status: </label>
					<div class="col-sm-9">
						
						<input type="radio" name="data[NappUser][is_approved]" <?php  if(1 == $staffArr['NappUser']['is_approved']){ echo 'checked'; } ?> value="1" > Active
						<input type="radio" name="data[NappUser][is_approved]" <?php  if(0 == $staffArr['NappUser']['is_approved']){ echo 'checked'; } ?> value="0" > Deactive
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> OTP:<br><small>IS SMS OTP ACTIVE</small></label>
					<div class="col-sm-9">
						
						<input type="radio" name="data[NappUser][is_active_otp]" <?php  if(1 == $staffArr['NappUser']['is_active_otp']){ echo 'checked'; } ?> value="1" > Active
						<input type="radio" name="data[NappUser][is_active_otp]" <?php  if(0 == $staffArr['NappUser']['is_active_otp']){ echo 'checked'; } ?> value="0" > Deactive
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
			jQuery(function(){ //short for $(document).ready(function(){
	

				$("#name").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter name"
                });$("#dept_id").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please select deptatment"
                });$("#email").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter email"
                });
			});
			</script>