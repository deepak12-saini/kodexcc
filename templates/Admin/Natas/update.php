<script src="<?php echo SITEURL; ?>theme/js/jquery.js"></script>
<script src="<?php echo SITEURL; ?>theme/js/jquery.validate.js"></script>
<script src="<?php echo SITEURL; ?>theme/js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo SITEURL; ?>theme/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>theme/css/font-awesome.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo SITEURL; ?>theme/css/ace-fonts.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo SITEURL; ?>theme/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
<style>
table {
    background-color: transparent;
    font-size: 12px;
}
.alert {
    padding: 0 8px;
}	
</style>
<div class="page-content" style="height:370px;">
		
		<?php echo $this->Flash->render(); ?>
		<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
		
			<div class="form-group">				
				<div class="col-sm-12 no-padding-right">
					Instrument Name: <b><?php echo $natacateArr['NataCategory']['make_model_type']; ?></b>				
				</div>
			</div>
			<div class="form-group">				
				<div class="col-sm-12 no-padding-right">
					Unique Code: <b>#<?php echo $natacateArr['NataCategory']['unique_id']; ?></b>			
				</div>
			</div>	

			<div class="form-group">				
				<div class="col-sm-12 no-padding-right">
					Date: <b><?php echo date('d F, Y',strtotime($natacateArr['NataEvent']['date'])); ?></b>			
				</div>
			</div>	
			<div class="form-group">				
				<div class="col-sm-12 no-padding-right">
					<?php echo $this->Form->input('NataEvent.description',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'description','placeholder'=>'Summary','value'=>$natacateArr['NataEvent']['description']))?>&nbsp;	
				</div>
			</div>		
			<div class="form-group">
			<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Status : </label>
				<div class="col-sm-9">
								
				<div class="radio"><label>
				<input type="radio"  value="0"  <?php if($natacateArr['NataEvent']['status'] == 0){ echo 'checked'; } ?>  class="ace" id="CategoryActive1" name="data[NataEvent][status]"><span class="lbl"> Pending</span>
				</label>
				<label><input type="radio"  value="1" <?php if($natacateArr['NataEvent']['status'] == 1){ echo 'checked'; } ?> class="ace" id="CategoryActive0" name="data[NataEvent][status]"><span class="lbl">Complete</span>	</label>
				</div>
							
				</div>
			</div>	
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Update status',array('div'=>false,'label'=>false, 'class' => 'btn btn-xs btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
</div>	
<script type="text/javascript">
	jQuery(function(){ 
		$("#description").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "please enter Summary"
		});
		
	});
	if(<?php echo (int)($status ?? 0); ?> === 1){
		$(document).ready(function(){
			setTimeout(function(){
				window.parent.closeModal();		
			},2000);			
		});
	}
</script>	