	
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="<?php echo SITEURL.'admin/clients'?>" class="btn btn-inverse" >Back</a></div>
		<h1>Customer <small><i class="ace-icon fa fa-angle-double-right"></i> Edit Customer</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create($client, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Client Category  </label>
				<div class="col-sm-9">
					<select name="data[Client][category_id]" class ='col-xs-10 col-sm-5'>
						<option value="">Select Category </option>
						<?php foreach($ClientTypeArr as $ClientTypeArrs){ ?>
							<option <?php if($clientArr['Client']['category_id'] == $ClientTypeArrs['ClientType']['id']){ echo 'selected'; }?> value="<?php echo $ClientTypeArrs['ClientType']['id']; ?>"><?php echo $ClientTypeArrs['ClientType']['name']; ?></option>
						<?php  } ?>
					</select>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('fname',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'fname','placeholder'=>'Name'))?>
				</div>
			</div>	
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	Email: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('email',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'email','placeholder'=>'Email'))?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	Mobile: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('mobile',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'mobile','placeholder'=>'Mobile'))?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	Landline: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('landline',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'landline','placeholder'=>'Landline'))?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	Company: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('company',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'company','placeholder'=>'Company'))?>
				</div>
			</div>
			<!--div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	Job Title: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('job_title',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'job_title','placeholder'=>'Job Title'))?>
				</div>
			</div-->
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	Address 1: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('address1',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'address1','placeholder'=>'Address 1'))?>
				</div>
			</div>
			<!-- <div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	Address 2: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('address2',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'address2','placeholder'=>'Address 2'))?>
				</div>
			</div> -->
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	City: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('city',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'city','placeholder'=>'City'))?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	State: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('state',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'state','placeholder'=>'State'))?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	zip: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('zip',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'zip','placeholder'=>'zip'))?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">	country: </label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('country',array('div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'country','placeholder'=>'country'))?>
				</div>
			</div>
			<div class="form-group">
				<label for="form-field-2" class="col-sm-3 control-label no-padding-right">Status : </label>
				<div class="col-sm-9">
				
				
				<div class="radio"><label>
				<input type="radio" value="1"  <?php if($clientArr['Client']['status'] == 1){ echo 'checked'; } ?> class="ace" id="CategoryActive1" name="Client[status]"><span class="lbl"> Active</span>
				</label></div>
				
				<div class="radio"><label>
					<input type="radio" value="0"  <?php if($clientArr['Client']['status'] == 0){ echo 'checked'; } ?>   class="ace" id="CategoryActive0" name="Client[status]"><span class="lbl"> Inactive</span>
				</label></div>				
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

				$("#fname").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter  name"
                }); $("#lanme").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter last name"
                });  $("#email").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter email"
                }); $("#country").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter country"
                });
			});
			</script>
