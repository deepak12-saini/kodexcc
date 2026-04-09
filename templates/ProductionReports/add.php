<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>	
<div class="page-content">
	<div class="page-header">
	<h1>
	Production Report 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
		Add New Report
	</small>
	<a href="<?php echo SITEURL; ?>production-reports" class="btn btn-mini btn-inverse" style="float:right;">Back</a>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
		
			<?php echo $this->Form->create('DuroOrder',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data'));?>	
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Manufacturing Product: </label>
					<div class="col-sm-9">
						<textarea name="data[ProductionReport][manufacturing_product]" id="manufacturing_product" class="col-xs-10 col-sm-5 ckeditor" placeholder="Production Report"></textarea>
					
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Manufacturing Packaged: </label>
					<div class="col-sm-9">
						<textarea name="data[ProductionReport][manufacturing_packaged]" id="manufacturing_packaged" class="col-xs-10 col-sm-5 ckeditor" placeholder="Manufacturing Packaged"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Manufacturing Label: </label>
					<div class="col-sm-9">
						<textarea name="data[ProductionReport][manufacturing_labelled]" id="manufacturing_labelled" class="col-xs-10 col-sm-5 ckeditor" placeholder="Manufacturing labeled"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">WAREHOUSE RECEIVED GOODS -- SUPPLIER PODS: </label>
					<div class="col-sm-9">
						<textarea name="data[ProductionReport][warehouse_supplier_pod]" id="warehouse_supplier_pod" class="col-xs-10 col-sm-5 ckeditor" placeholder="WAREHOUSE RECEIVED GOODS -- SUPPLIER PODS"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">DESPATCHED GOODS --  CUSTOMER PODS: </label>
					<div class="col-sm-9">
						<textarea name="data[ProductionReport][warehouse_customer_pod]" id="warehouse_customer_pod" class="col-xs-10 col-sm-5 ckeditor" placeholder="WAREHOUSE RECEIVED GOODS -- SUPPLIER PODS"></textarea>
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
	jQuery(function(){ 			
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