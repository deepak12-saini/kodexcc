	
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Notification<small><i class="ace-icon fa fa-angle-double-right"></i>Send Notification</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create('Front',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Type:</label>
				<div class="col-sm-9">
					<select name="type" class="col-xs-10 col-sm-5" onchange="changepro(this.value)" >
						<option value="3">Text</option>
						<option value="1">Video URL</option>
						<option value="2">Emailer URL</option>								
						<option value="4">Product</option>
					</select>
				</div>
			</div>	
			<div class="form-group"  id="product" style="display:none;">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Select Product</label>
				<div class="col-sm-9">
					<select name="product_id" class="form-control" >	
						<option value="">Select Products</option>
						<?php foreach($product as $products){ ?>
							<option value="<?php echo $products['Product']['id']; ?>"> <?php echo $products['Product']['title']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Notification Title: </label>
				<div class="col-sm-9">
					<input type="text" class="form-control" placeholder="Notification Title" value="" id="title" name="title">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Notification Description: </label>
				<div class="col-sm-9">
					<textarea name="description" id="description"  cols="82"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Image: </label>
				<div class="col-sm-9">
					<input type="file" class="form-control" name="image" accept="image/x-png,image/gif,image/jpeg">
				
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
function changepro(type){			
	if(type == 4){
		$(".inptype").hide();
		$("#product").show();
	}else{
		$(".inptype").show();
		$("#product").hide();
	}	
		
}	
 
jQuery(function(){ //short for $(document).ready(function(){

$("#title").validate({
	 expression: "if (VAL) return true; else return false;",
	message: "Please enter notification title"
});$("#description").validate({
	 expression: "if (VAL) return true; else return false;",
	message: "Please enter notification description"
});
});	
</script>
