<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
<div class="page-content">
	<div class="page-header">
	<h1>
	Non Conformance 
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
		
	</small>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<?php echo $this->Form->create('Conformance',array('class'=>'form-horizontal')); ?>
				
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Non Conformance Description: </label>
					<label class="col-sm-9" style="padding-top: 7px; font-weight:bold;"><?php echo $conformanceArr['Conformance']['non_conforance'];?></label>					
				</div>				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Customer Name: </label>
					<label class="col-sm-9" style="padding-top: 7px; font-weight:bold;"><?php echo $conformanceArr['Conformance']['customer_name'];?></label>				
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Route Cause and Analysis: </label>
					<label class="col-sm-9" style="padding-top: 7px; font-weight:bold;">
						<?php if(!empty($conformanceArr['Conformance']['why_1'])){ echo '<p>1. '. $conformanceArr['Conformance']['why_1'].'</p>'; } ?>
						<?php if(!empty($conformanceArr['Conformance']['why_2'])){ echo '<p>2. '. $conformanceArr['Conformance']['why_2'].'</p>'; } ?>
						<?php if(!empty($conformanceArr['Conformance']['why_3'])){ echo '<p>3. '. $conformanceArr['Conformance']['why_3'].'</p>'; } ?>
						<?php if(!empty($conformanceArr['Conformance']['why_4'])){ echo '<p>4. '. $conformanceArr['Conformance']['why_4'].'</p>'; } ?>
						<?php if(!empty($conformanceArr['Conformance']['why_5'])){ echo '<p>5. '. $conformanceArr['Conformance']['why_5'].'</p>'; } ?>
					
					</label>
					
				</div>	
				
				<!--div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Non Conformance Raised: </label>
					<div class="col-sm-9">
						<textarea  name="data[Conformance][non_conforance_raised]"  style="width:90%; height:120px;" class='col-xs-10 col-sm-5' placeholder="Non Conformance Raised" id='non_conforance_raised' ><?php echo $conformanceArr['Conformance']['non_conforance_raised'];?></textarea>
						
					</div>
				</div-->
				
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Corrective Action Taken: </label>
					<label class="col-sm-9" style="padding-top: 7px; font-weight:bold;"><?php if($conformanceArr['Conformance']['corrective_action_taken']){  echo $conformanceArr['Conformance']['corrective_action_taken']; }else{ echo 'N/A'; }?></label>
					<!--div class="col-sm-9">
						<textarea  name="data[Conformance][non_conforance]"   class='col-xs-10 col-sm-5' style="width:90%; height:70px;" placeholder="Non Conformance" id='non_conforance' ><?php echo $conformanceArr['Conformance']['corrective_action_taken'];?></textarea>					
					</div--->
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Short Term / Containment Action: </label>
					<div class="col-sm-9">
						<textarea  name="data[Conformance][short_term]"   class='col-xs-10 col-sm-5' style="width:90%; height:70px;" placeholder="Short Term / Containment Action" id='short_term' ><?php echo $conformanceArr['Conformance']['short_term'];?></textarea>				
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Follow Up Investigation / Continuous Improvement: </label>
					<div class="col-sm-9">
						<textarea  name="data[Conformance][follow_up]"   class='col-xs-10 col-sm-5' style="width:90%; height:70px;" placeholder="Follow Up Investigation / Continuous Improvement" id='follow_up' ><?php echo $conformanceArr['Conformance']['follow_up'];?></textarea>				
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Corrective Action Successfull: </label>
					<div class="col-sm-9">								
					<div class="radio"><label>
					<input type="radio"  value="1" <?php if($conformanceArr['Conformance']['corrective_action_successfull'] == 1){ echo 'checked="checked"'; } ?>  class="ace"  name="data[Conformance][corrective_action_successfull]"><span class="lbl"> Yes</span>
					</label></div>
					
					<div class="radio"><label>
						<input type="radio"  value="0"  <?php if($conformanceArr['Conformance']['corrective_action_successfull'] == 0){ echo 'checked="checked"'; } ?> class="ace"  name="data[Conformance][corrective_action_successfull]"><span class="lbl">
						No</span>
					</label></div>				
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Supporting Documents Attached: </label>
					<div class="col-sm-9">								
					<div class="radio"><label>
					<input type="radio"  value="1"  class="ace" <?php if($conformanceArr['Conformance']['support_document'] == 1){ echo 'checked="checked"'; } ?>   name="data[Conformance][support_document]"><span class="lbl"> Yes</span>
					</label></div>
					
					<div class="radio"><label>
						<input type="radio"  value="0"  <?php if($conformanceArr['Conformance']['support_document'] == 0){ echo 'checked="checked"'; } ?>  class="ace"  name="data[Conformance][support_document]"><span class="lbl">
						No</span>
					</label></div>				
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Date NCR Closed: </label>
					<div class="col-sm-9">	
						<?php  $ncr_closed_date = $conformanceArr['Conformance']['ncr_closed_date'];	?>
						<input type="text" id="alloteddate" name="data[Conformance][ncr_closed_date]" class="datepicker col-xs-10 col-sm-5" value="<?php if($ncr_closed_date != '0000-00-00'){  echo $ncr_closed_date; } ?>" placeholder="Date NCR Closed" >					
							
					</div>
				</div>
				
							
				<!--div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Preventive Action: </label>
					<div class="col-sm-9">
						<textarea  readonly style="width:90%; height:120px;  background:#e2e2e2;"  class="col-xs-10 col-sm-5" 
						placeholder="Preventive Action" id='preventive_action'><?php echo $conformanceArr['Conformance']['preventive_action'];?></textarea>						
					</div>
				</div-->
				
				<!--div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Management: </label>
					<div class="col-sm-9">
						<textarea  style="width:90%; height:120px;" name="data[Conformance][management_representive]" class='col-xs-10 col-sm-5' placeholder="Management" id='management_representive'><?php echo $conformanceArr['Conformance']['management_representive'];?></textarea>			
						
					</div>
				</div-->
				<div class="form-group">
					<label for="form-field-2" class="col-sm-3 control-label no-padding-right">NC Status : </label>
						<div class="col-sm-9">
						<?php 
						if($conformanceArr['Conformance']['status'] == 1) { 
							$sactive='checked'; 
							$active=''; 
							$inactive='';
						}else if($conformanceArr['Conformance']['status'] == 2) {
							$sactive=''; 
							$active='checked';
							$inactive='';
						}else if($conformanceArr['Conformance']['status'] == 3) { 
							$sactive=''; 
							$active='';
							$inactive='checked';
						}else{
							$sactive=''; 
							$active='';
							$inactive='';
						}	
						?>
						
						<div class="radio"><label>
						<input type="radio"  value="1" <?php echo $sactive; ?>  class="ace" id="ServiceCategoryActive1" name="data[Conformance][status]"><span class="lbl"> Pending</span>
						</label></div>
						
						<div class="radio"><label>
						<input type="radio"  value="2" <?php echo $active; ?>  class="ace" id="ServiceCategoryActive1" name="data[Conformance][status]"><span class="lbl"> In-Process</span>
						</label></div>
						
						<div class="radio"><label>
							<input type="radio" value="3" class="ace" <?php echo $inactive; ?> id="ServiceCategoryActive0" name="data[Conformance][status]"><span class="lbl"> Completed</span>
						</label></div>
						
						
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
		
		$("#management_representived").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter management representive"
		});  
	});
</script>