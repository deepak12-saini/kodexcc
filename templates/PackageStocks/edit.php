<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="page-content">
	<div class="page-header">
	<h1>
	Package  Stock List
	<small>
		<i class="ace-icon fa fa-angle-double-right"></i>
		Edit
	</small>
	<a href="<?php echo SITEURL; ?>hrs" class="btn btn-mini btn-inverse" style="float:right;">Back</a>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">

			<?php echo $this->Form->create('PackageStock', ['class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data']); ?>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Package Item: </label>
					<div class="col-sm-9">
						<?php
						echo $this->Form->input('PackageStock.label', ['type' => 'text', 'div' => false, 'label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'label', 'placeholder' => 'Package Item']);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Size: </label>
					<div class="col-sm-9">
						<?php
						echo $this->Form->input('PackageStock.size', ['type' => 'text', 'div' => false, 'label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'size', 'placeholder' => 'Size']);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Minimum Stock: </label>
					<div class="col-sm-9">
						<?php
						echo $this->Form->input('PackageStock.minimum', ['type' => 'text', 'div' => false, 'label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'minimum', 'placeholder' => 'Minimum Stock']);
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Update Type: </label>
					<div class="col-sm-9">
						<select name="updatetype">
							<option value="0">Minus</option>
							<option value="1">Plus</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Update Stock: </label>
					<div class="col-sm-9">
						<?php
						echo $this->Form->input('PackageStock.update_stock', ['type' => 'text', 'div' => false, 'label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'update_stock', 'placeholder' => 'Update Stock']);
						?>
					</div>
				</div>

				<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit', ['div' => false, 'label' => false, 'class' => 'btn btn-xs btn-success', 'id' => 'add_ser_prd_btn', 'value' => 'Submit']); ?>&nbsp;
					<?php echo $this->Html->link('Cancel', 'javascript:window.history.back();', ['class' => 'btn btn-xs btn-danger']); ?>

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
