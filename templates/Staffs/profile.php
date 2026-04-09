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
			<?php echo $this->Form->create('NappUser',array('class'=>'form-horizontal')); ?>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Select Department: </label>
					<div class="col-sm-9">
						<select id="dept_id" name="data[NappUser][dept_id]" class="col-xs-10 col-sm-5" >
							<option value="">Select Department</option>
							<?php foreach($departmentArr as $departments){ ?>
								<option <?php if($user['NappUser']['dept_id']  == $departments['Department']['id']){ echo 'selected'; } ?>  value="<?php echo $departments['Department']['id'];?>"><?php echo $departments['Department']['department_title'];?></option>
							<?php  }  ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">First Name: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('name',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'username','placeholder'=>'Username'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Last Name: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('lname',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'lname','placeholder'=>'Last Name'))?>
					</div>
				</div>
				
				<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email: </label>
						<div class="col-sm-9">
							<?php echo $this->Form->input('email',array('type'=>'email','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'email','placeholder'=>'Email'))?>
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Mobile Number:<br><small>Mobile number with counrty code<small> </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('mobile_number',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'mobile_number','placeholder'=>'Mobile Number e.g 61296031177'))?>
					</div>
					<br><span style="color:red;">&nbsp;&nbsp;&nbsp; e.g 61296031177</span>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Fax: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('fax',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'fax','placeholder'=>'Fax'))?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> zipcode: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('zipcode',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'zipcode','placeholder'=>'zipcode'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Employee Id: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('emp_id',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'emp_id','placeholder'=>'Employe Id'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Designation: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('designation',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'designation','placeholder'=>'Designation'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Select Country: </label>
					<div class="col-sm-9">
						<select id="dept_id" name="data[NappUser][country]" class="col-xs-10 col-sm-5" >
							
								<option <?php if($user['NappUser']['country']  == 'australia'){ echo 'selected'; } ?>  value="australia">Australia</option>
								<option <?php if($user['NappUser']['country']  == 'india'){ echo 'selected'; } ?>  value="india">India</option>
							
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