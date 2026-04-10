	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<style>
	fieldset .new {
		background: #ddd;
	}
	</style>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Purchase  <small><i class="ace-icon fa fa-angle-double-right"></i> Process</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create('Purchase',array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('date',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'datepicker col-xs-10 col-sm-5','id'=>'date','placeholder'=>'Date','value'=>date('Y-m-d'),'disabled'=>true))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Item Detail:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('item_details',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'item_details','placeholder'=>'Item Detail','disabled'=>true))?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name of the Requisitioner:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->input('requisitioner_name',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'requisitioner_name','placeholder'=>' Name of the Requisitioner','disabled'=>true))?>
				</div>
			</div>  
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Prepared By:</label>
				<div class="col-sm-9">
					<input  class="col-xs-10 col-sm-5" value="<?php echo $PurchaseArr['NappUser_1']['name'].' '.$PurchaseArr['NappUser_1']['lname']; ?>" disabled="disabled" type="text" value="Mouse"/>				</div>
			</div>  
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Permitted By:</label>
				<div class="col-sm-9">
					<input  class="col-xs-10 col-sm-5" value="<?php echo $PurchaseArr['NappUser']['name'].' '.$PurchaseArr['NappUser']['lname']; ?>" disabled="disabled" type="text" value="Mouse"/>				
				</div>
			</div>  
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9">
				<p><b>Please allow the following Items from the premises of <?php echo SITENAME; ?>:</b></p>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="simple-table" >
					<th>S.no</th>
					<th>Item Name</th>
					<th>Description Of Items</th>
					<th>Quantity</th>
					<th>Comment</th>
					
				</tr>
			<?php 
				if(!empty($PurchaseArr['PurchaseRequirement'])){
				$j=1;
				foreach($PurchaseArr['PurchaseRequirement'] as $PurchaseRequirement){ 
				
			?>
			
				<tr>
					<td><?php echo $j; ?></td>
					<td><?php echo $PurchaseRequirement['item_name']; ?></td>
					<td><?php echo $PurchaseRequirement['description_item']; ?></td>
					<td><?php echo $PurchaseRequirement['quantity']; ?></td>
					<td><?php echo $PurchaseRequirement['comments']; ?></td>
				</tr>
		
			<?php
			$j++;	
			} }
			?>
			</table>
			</div>
			</div>
			</div>	
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Authorized By:</label>
				<div class="col-sm-9">
					<select name="data[Purchase][authorized_by]" class="col-xs-10 col-sm-5">
						<option value="">Select Employee</option>
						<?php foreach($employeArr as $employeArrs){ ?>
							<option <?php if($PurchaseArr['Purchase']['authorized_by'] == $employeArrs['NappUser']['id']){ echo 'selected'; } ?> value="<?php echo $employeArrs['NappUser']['id']; ?>"><?php echo $employeArrs['NappUser']['name'].' '.$employeArrs['NappUser']['lname']; ?></option>
						<?php  }  ?>
					</select>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Final Result:</label>
				<div class="col-sm-9">
					<textarea name="data[Purchase][final_result]" class="col-xs-10 col-sm-5" ></textarea>				
				</div>
			</div>  
			<div class="form-group reminder">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status:</label>
				<div class="col-sm-9">
					<label class="block">
						<input type="radio" id="status_0" name="data[Purchase][status]" <?php if($PurchaseArr['Purchase']['status'] == 0){ echo 'checked="checked"'; } ?> value="0"  class="ace input-lg"> <span class="lbl bigger-120"> Pending </span>
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
