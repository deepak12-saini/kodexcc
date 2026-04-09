	<style>	.btn{margin-bottom: 5px; }	</style>	
	<div class="page-content">

		<div class="page-header">
			<h1><b><?php echo ucfirst($NappUser['NappUser']['name']).' '. ucfirst($NappUser['NappUser']['lname']);?></b>: <small>Price List Access</small>
				<a href="<?php echo SITEURL.'admin/users/customer'?>" style="float:right" class="btn btn-xs btn-inverse">Back</a>
			</h1>
		</div>
	
		<div class="row">
			<div class="col-xs-12">
			<?php
			
			$labsid = array();
			if(!empty($LabAssign)){
				foreach($LabAssign as $LabAssigns){
					$labsid[] = $LabAssigns['LabAssign']['lab_id'];
				}
			}			
			echo $this->Form->create(null,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
				<input type="hidden" name="data[new]" value="1">
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Assign Price List</small> </label>
					<div class="col-sm-9">
						<?php foreach($labfile as $labfiles){ if(!empty($labfiles['LabFile']['filename'])){ ?>
						
							<label class="block">
								<input class="ace input-lg"  <?php if(in_array($labfiles['LabFile']['id'],$labsid)){ echo 'checked'; }?> value="<?php echo $labfiles['LabFile']['id']; ?>" name="lab_id[]" type="checkbox">
								<span class="lbl bigger-120"> <?php echo $labfiles['LabFile']['label']; ?></span>
							</label>
						<div style="clear:both;"></div>
						<?php } } ?>					
					</div>
				</div>						
				<div class="form-group">
					<div class="col-md-offset-3 col-md-9">
						<?php echo $this->Form->submit('Update',array('div'=>false,'label'=>false, 'class' => 'btn btn-xs btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
						
					</div>
				</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>		
		
</div>		