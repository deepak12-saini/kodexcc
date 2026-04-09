	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="<?php echo SITEURL.'admin/products/label'?>" class="btn btn-mini btn-inverse" >Back</a></div>
		<h1>Product Label <small><i class="ace-icon fa fa-angle-double-right"></i> Add Label</small>
		</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
	
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'role' => 'form', 'type' => 'file']); ?>
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Label Category: </label>
				<div class="col-sm-9">
				
					<select class="col-xs-10 col-sm-5" name="data[Label][category_id]" id="category_id">
					<option value="">Please select Label category </option>
						<?php foreach ($LabelCategory as $LabelCategoryArr) { ?>
							<option value="<?php echo h($LabelCategoryArr['LabelCategory']['id'] ?? ''); ?>"><?php echo h($LabelCategoryArr['LabelCategory']['category'] ?? $LabelCategoryArr['LabelCategory']['name'] ?? ''); ?></option>
						<?php } ?>
					</select>

				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Label Name : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Label][name]" id="name" class="col-xs-10 col-sm-5" placeholder="Label Name">

				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Upload Product Label : </label>
				<div class="col-sm-9">
					<input type="file" name="data[Label][url]" id="url" class="col-xs-10 col-sm-5">
				</div>
			</div>		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Weight: </label>
				<div class="col-sm-9">
					<input type="text" placeholder="weight" name="data[Label][weight]" id="weight" class="col-xs-10 col-sm-5">
				</div>
			</div>		
			<div class="form-group">
			<label for="form-field-2" class="col-sm-3 control-label no-padding-right">Label Status : </label>
				<div class="col-sm-9">
				
				
				<div class="radio"><label>
				<input type="radio" checked="checked" value="1"  class="ace" id="CategoryActive1" name="data[Label][status]"><span class="lbl"> Active</span>
				</label></div>
				
				<div class="radio"><label>
					<input type="radio" value="0"  class="ace" id="CategoryActive0" name="data[Label][status]"><span class="lbl"> Inactive</span>
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
jQuery(function(){ 
	$("#category_id").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select label category
	});$("#product_name").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter product name"
	});$("#weightD").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter weight"
	});$("#file").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select file"
	});
});
</script>
