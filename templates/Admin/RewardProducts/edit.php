
<div class="page-content">
	<div class="page-header">
	<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
	<h1>Reward Product <small><i class="ace-icon fa fa-angle-double-right"></i> Edit</small>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12">
	<?php echo $this->Form->create(null, ['class'=>'form-horizontal','enctype'=>'multipart/form-data']); ?>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category Name: <br> <small>The name is how it appears on your site.</small></label>
			<div class="col-sm-9">
				<select name="RewardProduct[product_id]" class="col-xs-10 col-sm-5">
					<?php foreach ($product as $products) { ?>
							<option <?php if ((int)($products['Product']['id'] ?? 0) === (int)($RewardProductArr['RewardProduct']['product_id'] ?? 0)) { echo 'selected'; } ?> value="<?php echo h($products['Product']['id']); ?>"><?php echo h($products['Product']['title']); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">size(kg): </label>
			<div class="col-sm-9">
				<?php echo $this->Form->input('RewardProduct.size', ['div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'size','placeholder'=>'size e.g 5kg']);?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Applicator Points: </label>
			<div class="col-sm-9">
				<?php echo $this->Form->input('RewardProduct.applicator_points', ['div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'applicator_points','placeholder'=>'Applicator Points']);?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Dealer Point: </label>
			<div class="col-sm-9">
				<?php echo $this->Form->input('RewardProduct.dealer_point', ['div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'dealer_point','placeholder'=>'Dealer Point']);?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-3 col-md-9">
				<?php echo $this->Form->submit('Update', ['div'=>false,'label'=>false, 'class' => 'btn btn-success','id'=>'add_ser_prd_btn','value'=>'Submit']);?>&nbsp;
				<?php echo $this->Html->link('Cancel','javascript:window.history.back();', ['class' => 'btn btn-danger']);?>

			</div>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
</div>
<script type="text/javascript">
			jQuery(function(){

				$("#category_name").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter category name"
                }); $("#imagse").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please select image"
                });
			});
			</script>
