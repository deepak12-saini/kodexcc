<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>	
<div class="page-content">
	<div class="page-header">
	<h1>
	Kodex orders 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
		Add New Order
	</small>
	<a href="<?php echo SITEURL; ?>hrs" class="btn btn-mini btn-inverse" style="float:right;">Back</a>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
		
			<?php echo $this->Form->create('DuroOrder',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data'));?>	
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Customer Order Number: </label>
					<div class="col-sm-9">
						<?php 
						
						echo $this->Form->input('DuroOrder.customer_order_no',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'customer_order_no','placeholder'=>'Customer Order Number','readonly'=>true,'style'=>'background-color: #ddd !important;','value'=>$docId))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date of Order: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('DuroOrder.date_of_order',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'datepicker col-xs-10 col-sm-5','id'=>'date_of_order','placeholder'=>'Date of Order','value'=>date('Y-m-d')))?>
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
						<textarea name="data[DuroOrder][deliver_address]" id="deliver_address" class="col-xs-10 col-sm-5" placeholder="Deliver Address"></textarea>
					
					</div>
				</div>
				
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Pallet: </label>
					<div class="col-sm-9">
							<textarea name="data[DuroOrder][delivery_instruction]" id="delivery_instruction" class="col-xs-10 col-sm-5" placeholder="Pallet"></textarea>									
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Delivery Date: </label>
					<div class="col-sm-9">
							<?php echo $this->Form->input('DuroOrder.delivery_date',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'datepicker col-xs-10 col-sm-5','id'=>'delivery_date','placeholder'=>'Deliver Date'))?>					
					</div>
				</div>
			
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Comment: </label>
					<div class="col-sm-9">
						<textarea name="data[DuroOrder][comment]" id="comment" class="col-xs-10 col-sm-5" placeholder="Comment"></textarea>
					
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Applicator/Dealer: </label>
					<div class="col-sm-9">
						<select name="data[DuroOrder][is_applicator]" class="col-xs-10 col-sm-5">
							<option value="0">Dealer</option>
							<option value="1">Applicator</option>							
						</select>							
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Is Sample: </label>
					<div class="col-sm-9">
						<select name="data[DuroOrder][is_sample]" class="col-xs-10 col-sm-5">
							<option value="0">Main Order</option>
							<option value="1">Sample Order</option>							
						</select>							
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Sale Rep: </label>
					<div class="col-sm-9">
							<?php echo $this->Form->input('DuroOrder.sale_rep',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'sale_rep','placeholder'=>'Sale Rep'))?>					
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
							<tbody>
								<tr>									
									<td>
									<select name="data[product_name][]" required>
									<?php if(!empty($ProductArr)){	foreach($ProductArr as $ProductArrs){
									?><option value="<?php echo $ProductArrs['Product']['id'];?>"><?php echo $ProductArrs['Product']['title'];?></option>
									<?php } } ?></select></td>									
									<td> <input type="text" name="data[color][]"  value="" placeholder="Product Color" style="margin:5px;"   /></td>					
									<td> <input type="text" name="data[size][]"  value="" placeholder="Product Size" style="margin:5px;"   /></td>						
									<td> <input type="text" name="data[qty][]"  value="" placeholder="Product Quantity" style="margin:5px;"  required /></td>					
									<td>&nbsp;&nbsp; </td>					
								</tr>
							</tbody>
														
						</table>
						<div class="tblrow"></div>
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
		var  tablevew = '<table style=" width:100% !important; " id="tbl_'+n+'"><tbody><tr><td> <select name="data[product_name][]" required><?php if(!empty($ProductArr)){	foreach($ProductArr as $ProductArrs){
									?><option value="<?php echo $ProductArrs['Product']['id'];?>"><?php echo $ProductArrs['Product']['title'];?></option><?php } } ?></select></td><td> <input type="text" name="data[color][]"  value="" placeholder="Product Color" style="margin:5px;"   /></td><td> <input type="text" name="data[size][]"  value="" placeholder="Product Size" style="margin:5px;"   /></td><td> <input type="text" name="data[qty][]"  value="" placeholder="Product Quantity" style="margin:5px;"  required /></td><td> <a href="#null" onclick="deletetrow('+n+')"><i class="fa fa-close" style="color:red;"> </i></a></td></tr></tbody></table>';
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
		});$("#contact_name").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter contact name"
		});$("#deliver_address").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter deliver address"
		});$("#auditee").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter auditee"
		}); 
	});
</script>