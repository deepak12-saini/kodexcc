<?php
$t = [
	'inputContainer' => '{{content}}',
	'inputContainerError' => '{{content}}',
];
?>
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
			<?php echo $this->Form->create(null, [
				'class' => 'form-horizontal',
				'url' => ['controller' => 'Staffs', 'action' => 'changePassword'],
			]); ?>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="current_password"> Current Password </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.current_password', [
							'type' => 'password',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'current_password',
							'placeholder' => 'Current Password',
							'autocomplete' => 'current-password',
						]); ?>
					</div>
				</div>
				<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="new_password"> New Password </label>
						<div class="col-sm-9">
							<?php echo $this->Form->control('NappUser.new_password', [
								'type' => 'password',
								'label' => false,
								'templates' => $t,
								'class' => 'col-xs-10 col-sm-5',
								'id' => 'new_password',
								'placeholder' => 'New Password',
								'autocomplete' => 'new-password',
							]); ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="confirm_password"> Confirm Password </label>
						<div class="col-sm-9">
							<?php echo $this->Form->control('NappUser.confirm_password', [
								'type' => 'password',
								'label' => false,
								'templates' => $t,
								'class' => 'col-xs-10 col-sm-5',
								'id' => 'confirm_password',
								'placeholder' => 'Confirm Password',
								'autocomplete' => 'new-password',
							]); ?>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-3 col-md-9">
							<?php echo $this->Form->submit('Submit', ['class' => 'btn btn-success', 'id' => 'add_ser_prd_btn']); ?>&nbsp;
							<?php echo $this->Html->link('Cancel', 'javascript:window.history.back();', ['class' => 'btn btn-danger']); ?>

						</div>
					</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
		<script type="text/javascript">
			jQuery(function(){

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
