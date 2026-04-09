<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
<div class="page-content">
	<div class="page-header">
	<h1>
	Non Conformance 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>Update Non Conformance  
		
	</small>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'type' => 'file']); ?>
				
				<?php //echo '<PRE>'; print_r($ConformanceArr); die();?>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Non Conformance Description: </label>
					<div class="col-sm-9">
						<textarea style="width:90%; height:120px;"  name="data[Conformance][non_conforance]" class='col-xs-10 col-sm-5' placeholder="Non Conformance" id='non_conforance'  style="background:#e2e2e2;"><?php echo $ConformanceArr['Conformance']['non_conforance'];?></textarea>
						
					</div>
				</div>
				<!--div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Non Conformance Raised: </label>
					<div class="col-sm-9">
						<textarea style="width:90%; height:120px;"   name="data[Conformance][non_conforance_raised]"  class='col-xs-10 col-sm-5' placeholder="Non Conformance Raised" id='non_conforance_raised' style="background:#e2e2e2;"><?php echo $ConformanceArr['Conformance']['non_conforance_raised'];?></textarea>
						
					</div>
				</div-->
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Customer Name: </label>
					<div class="col-sm-9">
						<input type="text" name="data[Conformance][customer_name]" value="<?php echo $ConformanceArr['Conformance']['customer_name'];?>" class='col-xs-10 col-sm-5'>
										
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Client Deatil: </label>
					<div class="col-sm-9">
						<textarea  style="width:90%; height:120px;"  name="data[Conformance][client_detail]"  class='col-xs-10 col-sm-5' placeholder="Client Detail" id="client_detail" style="background:#e2e2e2;"><?php echo $ConformanceArr['Conformance']['client_detail'];?></textarea>	
					</div>
				</div>		
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Other Deatil: </label>
					<div class="col-sm-9">
						<textarea  style="width:90%; height:120px;"  name="data[Conformance][other_detail]"  class='col-xs-10 col-sm-5' placeholder="Client Detail" id="other_detail" style="background:#e2e2e2;"><?php echo $ConformanceArr['Conformance']['other_detail'];?></textarea>	
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Upload Document: <br/><small>if you want to upload multiple files</small></label>
					<div class="col-sm-9">
						<input type="file" name="document[]" multiple class="col-xs-10 col-sm-5" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Download Doc</label>
					<div class="col-sm-9">
						<?php if(!empty($ConformanceArr)){ $i=1; foreach($ConformanceArr['TaskDocument'] as $ConformanceArrs){ ?>
							<a href="<?php echo SITEURL.'tasks/download/'.$ConformanceArrs['id']; ?>" class="label label-danger" target="_blank">Download <?php echo $i; ?></a><br/>
						<?php $i++;  } } ?>
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
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd',			
		});
		
		$("#non_conforance").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter Non Conformance"
		});$("#'non_conforance_raised'").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter non conformance raised"
		});    
	});</script>