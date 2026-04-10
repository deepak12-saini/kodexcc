<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>	
<div class="page-content">
	<div class="page-header">
	<h1>
	Kodex orders 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
		 Feedback
	</small>
	<a href="<?php echo SITEURL; ?>duro_orders" class="btn btn-mini btn-inverse" style="float:right;">Back</a>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
		
			<?php echo $this->Form->create('DuroOrder',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data'));?>	
				
				
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Feedback: </label>
					<div class="col-sm-9">
						<textarea name="data[DuroOrder][feedback]" id="feedback" class="col-xs-10 col-sm-5" placeholder="feedback"><?php echo $docArr['DuroOrder']['feedback'];?></textarea>
					
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Who Give To: </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('DuroOrder.who_give_to',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'who_give_to','placeholder'=>'Customer Phone'))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">when use: </label>
					<div class="col-sm-9">
						<textarea name="data[DuroOrder][when_use]" id="when_use" class="col-xs-10 col-sm-5" placeholder="when use"><?php echo $docArr['DuroOrder']['when_use'];?></textarea>
					
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">where use: </label>
					<div class="col-sm-9">
						<textarea name="data[DuroOrder][where_use]" id="when_use" class="col-xs-10 col-sm-5" placeholder="where use"><?php echo $docArr['DuroOrder']['where_use'];?></textarea>
					
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
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',			
		});
		
		$("#machine_id").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter Machine Id Number"
		});$("#account_name").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter account name"
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