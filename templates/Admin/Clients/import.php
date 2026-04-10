	
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="<?php echo SITEURL.'admin/clients'?>" class="btn btn-inverse" >Back</a></div>
		<h1>Customer <small><i class="ace-icon fa fa-angle-double-right"></i> Add New Customer</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Client Category  </label>
				<div class="col-sm-9">
					<select name="Client[category_id]" id="category_id" class ='col-xs-10 col-sm-5'>
						<option value="">Select Category </option>
						<?php foreach($ClientTypeArr as $ClientTypeArrs){ ?>
							<option value="<?php echo $ClientTypeArrs['ClientType']['id']; ?>"><?php echo $ClientTypeArrs['ClientType']['name']; ?></option>
						<?php  } ?>
					</select>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Upload Csv: </label>
				<div class="col-sm-9">
					<input type="file" id="file" name="file" required class ='col-xs-10 col-sm-5' accept=".csv" >
					
				</div>
				<p> Please <a href="<?php echo SITEURL.'files/demo.csv' ?>" target="_blank">Click Here</a> to download demo csv file.</p>
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

	$("#category_id").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select category"
	}); $("#file").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select file"
	}); 
});
</script>
