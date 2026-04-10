<?php
$t = [
	'inputContainer' => '{{content}}',
	'inputContainerError' => '{{content}}',
];
$deptOpts = [];
foreach ($departmentArr as $row) {
	$d = $row['Department'] ?? [];
	if (!empty($d['id'])) {
		$deptOpts[$d['id']] = $d['department_title'] ?? '';
	}
}
?>
<div class="page-content">
	<div class="page-header">
	<h1>
	Profile
	<small>
	<i class="ace-icon fa fa-angle-double-right"></i>
	Profile Setting
	</small>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<?php echo $this->Form->create(null, [
				'class' => 'form-horizontal',
				'url' => ['controller' => 'Staffs', 'action' => 'profile'],
			]); ?>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="dept_id">Select Department: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.dept_id', [
							'type' => 'select',
							'options' => $deptOpts,
							'empty' => 'Select Department',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'dept_id',
						]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="username">First Name: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.name', [
							'type' => 'text',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'username',
							'placeholder' => 'Username',
						]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="lname">Last Name: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.lname', [
							'type' => 'text',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'lname',
							'placeholder' => 'Last Name',
							'value' => $u['lname'] ?? null,
						]); ?>
					</div>
				</div>

				<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="email">Email: </label>
						<div class="col-sm-9">
							<?php echo $this->Form->control('NappUser.email', [
								'type' => 'email',
								'label' => false,
								'templates' => $t,
								'class' => 'col-xs-10 col-sm-5',
							'id' => 'email',
							'placeholder' => 'Email',
						]); ?>
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="mobile_number"> Mobile Number:<br><small>Mobile number with country code</small> </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.mobile_number', [
							'type' => 'text',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'mobile_number',
							'placeholder' => 'Mobile Number e.g 61296031177',
						]); ?>
					</div>
					<br><span style="color:red;">&nbsp;&nbsp;&nbsp; e.g 61296031177</span>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="fax"> Fax: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.fax', [
							'type' => 'text',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'fax',
							'placeholder' => 'Fax',
						]); ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="zipcode"> zipcode: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.zipcode', [
							'type' => 'text',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'zipcode',
							'placeholder' => 'zipcode',
							'value' => $u['zipcode'] ?? null,
						]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="emp_id"> Employee Id: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.emp_id', [
							'type' => 'text',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'emp_id',
							'placeholder' => 'Employe Id',
						]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="designation"> Designation: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.designation', [
							'type' => 'text',
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'designation',
							'placeholder' => 'Designation',
							'value' => $u['designation'] ?? null,
						]); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="country">Select Country: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('NappUser.country', [
							'type' => 'select',
							'options' => [
								'australia' => 'Australia',
								'india' => 'India',
							],
							'label' => false,
							'templates' => $t,
							'class' => 'col-xs-10 col-sm-5',
							'id' => 'country',
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
		$("#dept_id").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please select department"
		});$("#name").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter name"
		});$("#username").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter username"
		});$("#email").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter email"
		});$("#phone").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter phone"
		});$("#emp_id").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter emp id"
		}); $("#designation").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter designation "
		});
	});
</script>
