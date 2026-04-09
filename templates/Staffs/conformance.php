<div class="page-content">
	<div class="page-header">
	<h1>
	Non Conformance List 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
		Non Conformance Send
	</small>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<?php echo $this->Form->create('Conformance',array('class'=>'form-horizontal')); ?>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Select Employee: </label>
					<div class="col-sm-9">
						<select id="emp_id" name="data[Conformance][compaint_to]" class="col-xs-10 col-sm-5" >
							<option value="">Select Employee</option>
							<?php foreach($NappUser as $NappUsers){ ?>
								<option value="<?php echo $NappUsers['NappUser']['id'];?>"><?php echo $NappUsers['NappUser']['name'].' '.$NappUsers['NappUser']['lname'];?></option>
							<?php  }  ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Select Department: </label>
					<div class="col-sm-9">
						<select id="dept_id" name="data[Conformance][dept_id]" class="col-xs-10 col-sm-5" >
							<option value="">Select Department</option>
							<?php foreach($departmentArr as $departments){ ?>
								<option value="<?php echo $departments['Department']['id'];?>"><?php echo $departments['Department']['department_title'];?></option>
							<?php  }  ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Non Conformance: </label>
					<div class="col-sm-9">
						<textarea name="data[Conformance][non_conforance]" class='col-xs-10 col-sm-5' placeholder="Non Conformance" id='non_conforance'></textarea>
						
					</div>
				</div>
				
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Aspects Examined: </label>
					<div class="col-sm-9">
						<textarea name="data[Conformance][aspects_examined]" class='col-xs-10 col-sm-5' placeholder="Aspects Examined" id='aspects_examined'></textarea>			
						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Non Conformance Raised: </label>
					<div class="col-sm-9">
						<textarea name="data[Conformance][non_conforance_raised]" class='col-xs-10 col-sm-5' placeholder="Non Conformance Raised" id='non_conforance_raised'></textarea>						
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date Of Complaint: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('date_of_complaint',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'datepicker col-xs-10 col-sm-5','id'=>'date_of_complaint','placeholder'=>'Date Of Complaint'))?>
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
		
		$("#dept_id").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please select department"
		});$("#non_conforance").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter non Conformance"
		});$("#aspects_examined").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter aspects examined"
		});$("#non_conforance_raised").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter non Conformance raised"
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