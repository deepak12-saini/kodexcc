	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<style>
	fieldset .new {
		background: #ddd;
	}
	</style>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Resource Requirement <small><i class="ace-icon fa fa-angle-double-right"></i> Approve </small>
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
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Responsibility:</label>
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
					<th>Resource Required</th>
					<th>Purpose/Project</th>
					<th>Quantity</th>
					<th>Date</th>
					<th>Budget</th>
					<th>Remark</th>
					<th>Status</th>
					
				</tr>
			<?php 
				if(!empty($PurchaseArr['PurchaseRequirement'])){
				$j=1;
				foreach($PurchaseArr['PurchaseRequirement'] as $PurchaseRequirement){ 
				
			?>
			
				<tr>
					<td><?php echo $j; ?></td>
					<td><?php echo $PurchaseRequirement['resource_requirement']; ?></td>
					<td><?php echo $PurchaseRequirement['purpose_project']; ?></td>
					<td>
						<select name="quantity[<?php echo $PurchaseRequirement['id']; ?>][]"   >
							<?php for($i=1; $i<100; $i++){?>
								<option <?php if($PurchaseRequirement['quantity'] == $i){ echo 'selected'; }?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
						</select>
					</td>
					<td><?php echo $PurchaseRequirement['time']; ?></td>
					<td><?php echo $PurchaseRequirement['budget']; ?></td>
					<td><?php echo $PurchaseRequirement['remark']; ?></td>
					<td>
						<input type="checkbox" name="status[<?php echo $PurchaseRequirement['id']; ?>][]"  value="<?php echo $PurchaseRequirement['id']; ?>" checked> Approved
					</td>
				</tr>
		
			<?php
			$j++;	
			} }
			?>
			</table>
			</div>
			</div>
			</div>	
			
			
			<div class="form-group reminder">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status:</label>
				<div class="col-sm-9">
					<label class="block">
						<input type="radio" id="status_0" name="data[Purchase][status]"  value="0" checked class="ace input-lg"> <span class="lbl bigger-120"> In-Process</span>
					</label>
					<label class="block">
						<input type="radio" id="status_1"  name="data[Purchase][status]" value="1" class="ace input-lg"> <span class="lbl bigger-120"> Send To Purchase Request </span>
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
