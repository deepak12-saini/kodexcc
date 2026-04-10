	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<style>
	fieldset .new {
		background: #ddd;
	}
	</style>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Purchase  <small><i class="ace-icon fa fa-angle-double-right"></i> Edit Purchase Request</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create('Purchase',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('date',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'datepicker col-xs-10 col-sm-5','id'=>'date','placeholder'=>'Date','value'=>date('Y-m-d')))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Item Detail:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('item_details',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'item_details','placeholder'=>'Item Detail'))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name of the Requisitioner:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('requisitioner_name',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'requisitioner_name','placeholder'=>' Name of the Requisitioner'))?>
				</div>
			</div>  
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Permitted By:</label>
				<div class="col-sm-9">
					<select name="data[Purchase][permitted_by]" class="col-xs-10 col-sm-5">
						<option value="">Select Employee</option>
						<?php foreach($employeArr as $employeArrs){ ?>
							<option <?php if($PurchaseArr['Purchase']['permitted_by'] == $employeArrs['NappUser']['id']){ echo 'selected'; } ?> value="<?php echo $employeArrs['NappUser']['id']; ?>"><?php echo $employeArrs['NappUser']['name'].' '.$employeArrs['NappUser']['lname']; ?></option>
						<?php  }  ?>
					</select>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status:</label>
				<div class="col-sm-9">
					<label class="block">
						<input type="radio" id="status_0" name="data[Purchase][status]" <?php if($PurchaseArr['Purchase']['status'] == 0){ echo 'checked="checked"'; } ?> value="0" class="ace input-lg"> <span class="lbl bigger-120"> Pending </span>
					</label>
					<label class="block">
						<input type="radio" id="status_1"  name="data[Purchase][status]"  <?php if($PurchaseArr['Purchase']['status'] == 1){ echo 'checked="checked"'; } ?> value="1" class="ace input-lg"> <span class="lbl bigger-120"> Approved</span>
					</label>					
					<label class="block">
						<input type="radio" id="status_1"  name="data[Purchase][status]"  <?php if($PurchaseArr['Purchase']['status'] == 2){ echo 'checked="checked"'; } ?> value="2" class="ace input-lg"> <span class="lbl bigger-120"> In-Process</span>
					</label>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9">
				<p><b>Please allow the following Items from the premises of <?php echo SITENAME; ?>:</b></p>
				</div>
			</div>	
			<?php 
				if(!empty($PurchaseArr['PurchaseRequirement'])){
				$j=1;
				foreach($PurchaseArr['PurchaseRequirement'] as $PurchaseRequirement){ 
				
			?>
			<fieldset class="newitem" id="div_<?php echo $j; ?>" >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right " for="form-field-1"></label>
					<div class="col-sm-9"><b>Items <?php echo $j; ?>  <a href="#null" onclick="remove(<?php echo $j; ?>)" >&nbsp;&nbsp;&nbsp; Remove</a></b></div>
				</div>			
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Item Name </label>
					<div class="col-sm-9">
						<input type="text" name="item_name[]" class="col-xs-10 col-sm-5" required value="<?php echo $PurchaseRequirement['item_name']; ?>">
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Description Of Items</label>
					<div class="col-sm-9">
						<input type="text" name="description_item[]" class="col-xs-10 col-sm-5" required  
						value="<?php echo $PurchaseRequirement['description_item']; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Quantity</label>
					<div class="col-sm-9">
						<input type="text" name="quantity[]" value="<?php echo $PurchaseRequirement['quantity']; ?>" class="col-xs-10 col-sm-5" required>
					
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Comment</label>
					<div class="col-sm-9">
						<input type="text" name="comments[]" class="col-xs-10 col-sm-5" required  value="<?php echo $PurchaseRequirement['comments']; ?>">
					</div>
				</div>	
				
			</fieldset>
			<?php
			$j++;	
			} }else{
			?>
			<fieldset class="newitem"  >
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right " for="form-field-1"></label>
					<div class="col-sm-9"><b>Items 1</b></div>
				</div>			
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Item Name1</label>
					<div class="col-sm-9">
						<input type="text" name="item_name[]" class="col-xs-10 col-sm-5" required>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Description Of Items</label>
					<div class="col-sm-9">
						<input type="text" name="description_item[]" class="col-xs-10 col-sm-5" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Quantity</label>
					<div class="col-sm-9">
						<input type="text" name="quantity[]" class="col-xs-10 col-sm-5" required>
						
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Comment</label>
					<div class="col-sm-9">
						<input type="text" name="comments[]" class="col-xs-10 col-sm-5" required>
					</div>
				</div>	
			</fieldset>
			
			<?php } ?>
			<p id="addmore"></p>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9">
					<a href="#null" class="btn btn-mini btn-danger" onclick="addmore()" >Add More</a>
				</div>
			</div>	
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit',array('div'=>false,'label'=>false, 'class' => 'btn btn-mini btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					<?php echo $this->Html->link('Cancel','javascript:window.history.back();',array('class' => 'btn btn-mini btn-danger'));?>

				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
	function addmore(){
	
		var numItems = $('.newitem').length;
		
		var newdiv = 	numItems + 1 ;
		
		var newfieldset = '<fieldset class="newitem" id="div_'+newdiv+'"><div class="form-group"><label class="col-sm-3 control-label no-padding-right " for="form-field-1"></label><div class="col-sm-9"><b>Items '+newdiv+' <a href="#null" onclick="remove('+newdiv+')" >&nbsp;&nbsp;&nbsp; Remove</a></b></div></div><div class="form-group"><label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Item Name1</label><div class="col-sm-9"><input type="text" name="item_name[]" class="col-xs-10 col-sm-5" required></div>	</div><div class="form-group"><label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Description Of Items</label><div class="col-sm-9"><input type="text" name="description_item[]" class="col-xs-10 col-sm-5" required>	</div>	</div><div class="form-group">	<label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Quantity</label>	<div class="col-sm-9"><input type="text" name="quantity[]" class="col-xs-10 col-sm-5" required></div></div><div class="form-group"><label class="col-sm-3 control-label no-padding-right new" for="form-field-1">Comment</label><div class="col-sm-9"><input type="text" name="comments[]" class="col-xs-10 col-sm-5" required></div></div></fieldset>';
		
		$("#addmore").append(newfieldset);
	}
	function remove(id){	
		$("#div_"+id).remove();
	}
	
jQuery(function(){ 

	
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',			
	});
	
	$("#task_id").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter task id"
	}); $("#title").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter  employe id"
	}); $("#assignedto").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select employee"
	}); $("#assignedby").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please eneter assigned by user"
	}); 
	
	
});
</script>
