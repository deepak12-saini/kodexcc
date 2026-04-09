<div class="page-content">
	<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>SubCategory<small><i class="ace-icon fa fa-angle-double-right"></i> Add SubCategory</small>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal']); ?>
		<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category: </label>
				<div class="col-sm-9">

					<select class="col-xs-10 col-sm-5" name="data[Subcategory][category_id]">
						<?php foreach ($category as $category_arr) { ?>
							<option value="<?php echo h($category_arr['Category']['id']); ?>" ><?php echo h($category_arr['Category']['category_name']); ?></option>
						<?php } ?>
					</select>

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Subcategory][name]" id="category_name" class="col-xs-10 col-sm-5" placeholder="Sub Category Name">

				</div>
			</div>
			<div class="form-group">
			<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Status : </label>
				<div class="col-sm-9">


				<div class="radio"><label>
				<input type="radio" checked="checked" value="1"  class="ace" id="CategoryActive1" name="data[Subcategory][status]"><span class="lbl"> Active</span>
				</label></div>

				<div class="radio"><label>
					<input type="radio" value="0"  class="ace" id="CategoryActive0" name="data[Subcategory][status]"><span class="lbl"> Inactive</span>
				</label></div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit', ['div' => false, 'label' => false, 'class' => 'btn btn-success', 'id' => 'add_ser_prd_btn', 'value' => 'Submit']); ?>&nbsp;
					<?php echo $this->Html->link('Cancel', 'javascript:window.history.back();', ['class' => 'btn btn-danger']); ?>

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
                    message: "Please enter subcategory name"
                });
			});
			</script>
