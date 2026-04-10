<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>	
<div class="page-content">
	<div class="page-header">
	<h1>
	Durotech orders 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
		Edit Order
	</small>
	<a href="<?php echo SITEURL; ?>duro_orders" class="btn btn-mini btn-inverse" style="float:right;">Back</a>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
		
			<?php echo $this->Form->create('DuroOrder',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data'));?>	
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Customer Order Number: </label>
					<div class="col-sm-9">
						<?php 
						
						echo $this->Form->input('DuroOrder.customer_order_no',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'customer_order_no','placeholder'=>'Customer Order Number','readonly'=>true,'style'=>'background-color: #ddd !important;'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date of Order: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('DuroOrder.date_of_order',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'datepicker col-xs-10 col-sm-5','id'=>'date_of_order','placeholder'=>'Date of Order'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Is Old Customer: </label>
					<div class="col-sm-9">
						<select name="data[DuroOrder][customer_id]" class="col-xs-10 col-sm-5">
							<option value="0">Select Customer</option>
							<?php if(!empty($RewardPoint)){ foreach($RewardPoint as $RewardPoints){ ?>							
								<option value="<?php echo $RewardPoints['RewardPoint']['id']; ?>"><?php echo $RewardPoints['RewardPoint']['contact_name'].'('.$RewardPoints['RewardPoint']['contact_phone'].')'; ?></option>
							<?php } }  ?>					
						</select>							
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Customer Name: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('DuroOrder.contact_name',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'contact_name','placeholder'=>'Customer Name'))?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Customer Phone: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('DuroOrder.contact_phone',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'contact_phone','placeholder'=>'Customer Phone'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Deliver Address: </label>
					<div class="col-sm-9">
						<textarea name="data[DuroOrder][deliver_address]" id="deliver_address" class="col-xs-10 col-sm-5" placeholder="Deliver Address"><?php echo $docArr['DuroOrder']['deliver_address'];?></textarea>
					
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Deliver Date: </label>
					<div class="col-sm-9">
							<?php echo $this->Form->input('DuroOrder.delivery_date',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'datepicker col-xs-10 col-sm-5','id'=>'delivery_date','placeholder'=>'Deliver Date'))?>					
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Pallet: </label>
					<div class="col-sm-9">
							<?php echo $this->Form->input('DuroOrder.delivery_instruction',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'delivery_instruction','placeholder'=>'Delivery Instruction'))?>					
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Comment: </label>
					<div class="col-sm-9">
						<textarea name="data[DuroOrder][comment]" id="comment" class="col-xs-10 col-sm-5" placeholder="Comment"><?php echo $docArr['DuroOrder']['comment'];?></textarea>
					
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Status: </label>
					<div class="col-sm-9">
						<select name="data[DuroOrder][status]" class="col-xs-10 col-sm-5">
							<option <?php if($docArr['DuroOrder']['status'] == 0){ echo 'selected'; } ?> value="0">New Order</option>
							<option <?php if($docArr['DuroOrder']['status'] == 1){ echo 'selected'; } ?>  value="1">Accept</option>
							<option <?php if($docArr['DuroOrder']['status'] == 2){ echo 'selected'; } ?>  value="2">Order Ready</option>
							<option <?php if($docArr['DuroOrder']['status'] == 3){ echo 'selected'; } ?>  value="3">Dispatch</option>
							<option <?php if($docArr['DuroOrder']['status'] == 5){ echo 'selected'; } ?>  value="5">Canceled</option>
							<option <?php if($docArr['DuroOrder']['status'] == 6){ echo 'selected'; } ?>  value="6">Order Delivered</option>
						</select>							
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Applicator/Dealer: </label>
					<div class="col-sm-9">
						<select name="data[DuroOrder][is_applicator]" class="col-xs-10 col-sm-5">
							<option <?php if($docArr['DuroOrder']['is_applicator'] == 0){ echo 'selected'; } ?> value="0">Dealer</option>
							<option <?php if($docArr['DuroOrder']['is_applicator'] == 1){ echo 'selected'; } ?>  value="1">Applicator</option>							
						</select>							
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Is Sample: </label>
					<div class="col-sm-9">
						<select name="data[DuroOrder][is_sample]" class="col-xs-10 col-sm-5">
							<option <?php if($docArr['DuroOrder']['is_sample'] == 0){ echo 'selected'; } ?> value="0">Main Order</option>
							<option <?php if($docArr['DuroOrder']['is_sample'] == 1){ echo 'selected'; } ?> value="1">Sample Order</option>							
						</select>							
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
					<div class="col-sm-9">
						<div class="table-responsive">
						<table style=" width:100% !important; ">
							<tr>		
								<th>Product Name</th>
								<th>Color</th>
								<th>Size</th>
								<th>Quantity</th>
								<th></th>
							</tr>
																					
						</table>
						<div class="tblrow">
							<?php
								$i=1;
								if(!empty($docArr['OrderProduct'])){
								foreach($docArr['OrderProduct'] as $records){
							?>
							<table style=" width:100% !important; " id="tbl_<?php echo $i; ?>"><tbody>
							<tr>
							<td><select name="data[product_name][]" required>
									<?php if(!empty($ProductArr)){	foreach($ProductArr as $ProductArrs){
									?><option <?php if($ProductArrs['Product']['id'] == $records['product_id']){ echo 'selected'; } ?> value="<?php echo $ProductArrs['Product']['id'];?>"><?php echo $ProductArrs['Product']['title'];?></option>
									<?php } } ?></select></td>	
							
							<td> <input name="data[color][]" value="<?php echo $records['color']; ?>"  placeholder="Product Color" style="margin:5px;"  type="text"></td><td> <input name="data[size][]" value="<?php echo $records['size']; ?>"  placeholder="Product Size" style="margin:5px;" type="text"></td><td> <input name="data[qty][]" value="<?php echo $records['qty']; ?>"  placeholder="Product Quantity" style="margin:5px;" required="" type="text"></td><td> <?php if($i>1){ ?><a href="#null" onclick="deletetrow(1)"><i class="fa fa-close" style="color:red;"> </i></a> <?php } ?></td>		</tr></tbody></table>
							<?php  $i++; }} ?>
						</div>
						</div>
						<a href="#null" class="btn btn-mini btn-info" onclick="addmore()"> Add More</a>
					</div>
				</div>
				
				<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit',array('div'=>false,'label'=>false, 'class' => 'btn btn-xs btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					<?php echo $this->Html->link('Cancel','javascript:window.history.back();',array('class' => 'btn btn-xs btn-danger'));?>

				</div>
				</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	
	function deletetrow(noid){
		$("#tbl_"+noid).remove();
	}
	function addmore(){
	
		var n = $("#center div").length + 1;
		var  tablevew = '<table class="newtbl" style=" width:100% !important; " id="tbl_'+n+'" class="ss"><tbody><tr><td> <select name="data[product_name][]" required><?php if(!empty($ProductArr)){	foreach($ProductArr as $ProductArrs){
									?><option value="<?php echo $ProductArrs['Product']['id'];?>"><?php echo $ProductArrs['Product']['title'];?></option><?php } } ?></select></td><td> <input type="text" name="data[color][]"  value="" placeholder="Product Color" style="margin:5px;"   /></td><td> <input type="text" name="data[size][]"  value="" placeholder="Product Size" style="margin:5px;"   /></td><td> <input type="text" name="data[qty][]"  value="" placeholder="Product Quantity" style="margin:5px;"  required /></td><td> <a href="#null" onclick="deletetrow('+n+')"><i class="fa fa-close" style="color:red;"> </i></a></td>		</tr></tbody></table>';
		$(".tblrow").append(tablevew);
	}
	
	jQuery(function(){ 
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',			
		});
		
		$("#machine_id").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter Machine Id Number"
		});$("#order_taken_by").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter  order taken by"
		});$("#deliver_address").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter deliver address"
		});$("#auditee").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter auditee"
		}); 
	});
</script>