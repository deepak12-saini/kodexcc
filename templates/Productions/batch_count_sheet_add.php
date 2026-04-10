	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>QC Result <small><i class="ace-icon fa fa-angle-double-right"></i> Add New </small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'url' => ['action' => 'batchCountSheetAdd']]); ?>
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Employee Name:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('BatchCountSheet.employee_name', ['label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'employee_name', 'placeholder' => 'Employee Name']); ?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('BatchCountSheet.date', ['type' => 'text', 'label' => false, 'class' => 'datepicker col-xs-10 col-sm-5', 'id' => 'date', 'placeholder' => 'Date']); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Batch Number:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('BatchCountSheet.batch_number', ['label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'batch_number', 'placeholder' => 'Batch Number']); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Product Name:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('BatchCountSheet.product_name', ['label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'product_name', 'placeholder' => 'Product Name']); ?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">No. of Pails:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('BatchCountSheet.no_of_pails', ['label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'no_of_pails', 'placeholder' => 'no. of pails']); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Quantity:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('BatchCountSheet.quantity', ['label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'quantity', 'placeholder' => 'quantity']); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date Completed:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('BatchCountSheet.date_completed', ['type' => 'text', 'label' => false, 'class' => 'datepicker col-xs-10 col-sm-5', 'id' => 'date_completed', 'placeholder' => 'Date completed']); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Signature:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('BatchCountSheet.signature', ['label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'signature', 'placeholder' => 'Signature']); ?>
				</div>
			</div>
									
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit', ['div' => false, 'label' => false, 'class' => 'btn btn-success', 'id' => 'add_ser_prd_btn', 'value' => 'Submit']); ?>&nbsp;
					<?php echo $this->Html->link('Cancel', 'javascript:window.history.back();', ['class' => 'btn btn-danger']); ?>

				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
jQuery(function(){
	$('.datepicker').datepicker({
		format: "yyyy-mm-dd",
	});	
	$("#product_name").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter product name"
	});$("#employee_name").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter employee name"
	}); $("#batch_number").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter batch number "
	}); $("#no_of_pails").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter no of pails"
	}); $("#quantity").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter quantity"
	});$("#date_completed").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter date completed"
	});
});
</script>
