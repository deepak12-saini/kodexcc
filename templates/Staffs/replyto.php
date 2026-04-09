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
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Non Conformance: </label>
					<div class="col-sm-9">
						<textarea style="width:90%; height:120px;  background:#e2e2e2;"   class='col-xs-10 col-sm-5' placeholder="Non Conformance" id='non_conforance' disabled style="background:#e2e2e2;"><?php echo $conformanceArr['Conformance']['non_conforance'];?></textarea>
						
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Route Cause and Analysis: </label>
					<div class="col-sm-9">
						<textarea style="width:90%; height:50px; margin-bottom:10px;" name="data[Conformance][why_1]" class='col-xs-10 col-sm-5' placeholder="Ask Why ?" id='why_1'><?php echo $conformanceArr['Conformance']['why_1'];?></textarea>	
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
					<div class="col-sm-9">	
						<textarea style="width:90%; height:50px;  margin-bottom:10px;" name="data[Conformance][why_2]" class='col-xs-10 col-sm-5' placeholder="Ask Why ?" id='why_2'><?php echo $conformanceArr['Conformance']['why_2'];?></textarea>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
					<div class="col-sm-9">	
						<textarea style="width:90%; height:50px;  margin-bottom:10px;" name="data[Conformance][why_3]" class='col-xs-10 col-sm-5' placeholder="Ask Why ?" id='why_3'><?php echo $conformanceArr['Conformance']['why_3'];?></textarea>	
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
					<div class="col-sm-9">		
						<textarea style="width:90%; height:50px;  margin-bottom:10px;" name="data[Conformance][why_4]" class='col-xs-10 col-sm-5' placeholder="Ask Why ?" id='why_4'><?php echo $conformanceArr['Conformance']['why_4'];?></textarea>	
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
					<div class="col-sm-9">	
						<textarea style="width:90%; height:50px;" name="data[Conformance][why_5]" class='col-xs-10 col-sm-5' placeholder="Ask Why ?" id='why_5'><?php echo $conformanceArr['Conformance']['why_5'];?></textarea>	
					</div>
				</div>			
						
				</div>
				 <div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Corrective Action Taken: </label>
					<div class="col-sm-9">
						<textarea style="width:90%; height:120px; margin-bottom:10px;" name="data[Conformance][corrective_action_taken]" class='col-xs-10 col-sm-5' placeholder="Corrective Action Taken" id='corrective_action_taken'><?php echo $conformanceArr['Conformance']['corrective_action_taken'];?></textarea>			
						
					</div>
				</div> 
				
				
				<!--div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Preventive Action: </label>
					<div class="col-sm-9">
						<textarea  style="width:90%; height:120px;"  name="data[Conformance][preventive_action]" class="col-xs-10 col-sm-5" 
						placeholder="Preventive Action" id='preventive_action'><?php //echo $conformanceArr['Conformance']['preventive_action'];?></textarea>						
					</div>
				</div-->
				<?php if($conformanceArr['Conformance']['compaint_to'] == $user_id){?>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Assign To Employee: <br><small>Assign task to another employe for prevantive action.</small></label>
					<div class="col-sm-9">
						<select id="emp_id" name="data[ConformanceRelationnew][emp_id]" class="col-xs-10 col-sm-5" >
							<option value="">Select Employee</option>
							<?php foreach($NappUser as $NappUsers){ ?>
								<option value="<?php echo $NappUsers['NappUser']['id'];?>"><?php echo $NappUsers['NappUser']['name'].' '.$NappUsers['NappUser']['lname'];?></option>
							<?php  }  ?>
						</select>
					</div>
				</div>
				<?php } ?>
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
		
		$("#corrective_action_takens").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter corrective action taken"
		});  $("#why_1").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter answer"
		}); $("#why_2").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter answer"
		});  $("#why_3").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter answer"
		});  $("#why_4").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter answer"
		});  $("#why_5").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter answer"
		}); 
	});
</script>