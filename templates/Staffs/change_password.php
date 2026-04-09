<div class="page-content">
	<div class="page-header">
	<h1>
	Profile Setting
	<small>
	<i class="ace-icon fa fa-angle-double-right"></i>
	Change Password
	</small>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<?php echo $this->Form->create('NappUser',array('class'=>'form-horizontal')); ?>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Current Password </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('current_password',array('type'=>'password','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'current_password','placeholder'=>'Current Password'))?>
					</div>
				</div>
				<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> New Password </label>
						<div class="col-sm-9">
							<?php echo $this->Form->input('new_password',array('type'=>'password','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'new_password','placeholder'=>'New Password'))?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Confirm Password </label>
						<div class="col-sm-9">
							<?php echo $this->Form->input('confirm_password',array('type'=>'password','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'confirm_password','placeholder'=>'Confirm Password'))?>
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
	

				$("#current_password").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter current password"
                });$("#new_password").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter new password"
                });$("#confirm_password").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter confirm password"
                }); 
			});
			</script>