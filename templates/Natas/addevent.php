	
	<div class="page-content">
		<div class="page-header">
			<div class="right_btn pull-right" ><a href="<?php echo SITEURL.'natas';?>" class="btn btn-xs btn-inverse " >Back</a></div>
			<h1><small>Add New Calibration</small></h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name of Instrument:</label>
				<div class="col-sm-9">
					<select name="data[NataEvent][cate_id]" id="cate_id" class ='col-xs-10 col-sm-5' >
						<option value="">Select Instrument</option>
						<?php if(!empty($natacateArr)){ foreach($natacateArr as $natacates){ ?>
						<option  value="<?php echo $natacates['NataCategory']['id'];?>"><?php echo $natacates['NataCategory']['name'];?></option>
						<?php } } ?>
					</select>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Next Calibration:</label>
				<div class="col-sm-9">
					<?php 	$dated = date('d-m-Y'); ?>
					<input type="text" id="alloteddate" name="data[NataEvent][month]" class="datepicker col-xs-10 col-sm-5" value="<?php echo $dated; ?>" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Description:</label>
				<div class="col-sm-9">
					
					<input type="text" id="alloteddate" name="data[NataEvent][description]" class="col-xs-10 col-sm-5" value="" >
				</div>
			</div>
			
				
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Add New',array('div'=>false,'label'=>false, 'class' => 'btn btn-xs btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
	jQuery(function(){ 
			
		$('.datepicker').datepicker({
			format: "dd-mm-yyyy",
			//viewMode: "months", 
			//minViewMode: "months"		
				
		});
		$("#cate_id").validate({
			 expression: "if (VAL > 0) return true; else return false;",
			message: "Please select instrument "
		});
	});
</script>	
