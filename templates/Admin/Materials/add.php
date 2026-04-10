	
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Material <small><i class="ace-icon fa fa-angle-double-right"></i> Add New Material</small>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Material Type:</label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('Material.material_type', ['div' => false, 'label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'material_type', 'placeholder' => 'Omya Carb 10']); ?>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Package Type: </label>
				<div class="col-sm-9">
					<select name="Material[package_type]" class="col-xs-10 col-sm-5" >
						<option value="Pallet">Pallet</option>
						<option value="IBC">IBC</option>
					</select>					
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Weight/dimensions: <br><small>25kg bags or 1000kg IBC</small></label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('Material.weight', ['div' => false, 'label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'weight', 'placeholder' => '25kg bags']); ?>
				</div>
			</div>
		
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Quantity: <br><small>40 bags on a Pallet or 1 IBC</small></label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('Material.quantity', ['div' => false, 'label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'quantity', 'placeholder' => '40 bags on a Pallet or 2 IBC']); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Description: </label>
				<div class="col-sm-9">
					<textarea name="Material[description]" id="description" class="col-xs-10 col-sm-5" placeholder="Description" ></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit(__('Submit'), ['div' => false, 'label' => false, 'class' => 'btn btn-success', 'id' => 'add_ser_prd_btn']); ?>&nbsp;
					<?php echo $this->Html->link(__('Cancel'), 'javascript:window.history.back();', ['class' => 'btn btn-danger']); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
			jQuery(function(){
				$("#material_type").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter material type"
                });
			});
			</script>
