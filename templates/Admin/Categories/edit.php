	<div class="page-content">
		<div class="page-header">
			<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
			<h1>Category<small><i class="ace-icon fa fa-angle-double-right"></i> Edit Category</small>
			</h1>
		</div>

	<div class="row">
		<div class="col-xs-12">
			<?php echo $this->Form->create(null, ['class'=>'form-horizontal','enctype'=>'multipart/form-data', 'type' => 'file']);
			echo $this->Form->input('Category.id', ['type'=>'hidden']); ?>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category name: <br> <small>The name is how it appears on your site.</small></label>
					<div class="col-sm-8">
						<?php echo $this->Form->input('Category.category_name', ['div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'category_name','placeholder'=>'Category Name']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description: <br> <small>The description is not prominent by default; however, some themes may show it.</small></label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('Category.description', ['div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'description','placeholder'=>'Description']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Title: </label>
					<div class="col-sm-9">
						<input type="text" name="data[Category][meta_title]" id="meta_title" value="<?php echo h($Category['Category']['meta_title'] ?? ''); ?>" class="col-xs-10 col-sm-5" placeholder="Meta Title">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Keyword: </label>
					<div class="col-sm-9">
						<input type="text" name="data[Category][meta_keyword]" id="meta_keyword" value="<?php echo h($Category['Category']['meta_keyword'] ?? ''); ?>" class="col-xs-10 col-sm-5" placeholder="Meta Keyword">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Description: </label>
					<div class="col-sm-9">
						<textarea name="data[Category][meta_description]" id="meta_description" class="col-xs-10 col-sm-5" placeholder="Meta Description" ><?php echo h($Category['Category']['meta_description'] ?? ''); ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category Image: </label>
					<div class="col-sm-9">
						<input type="file" name="data[image]" id="image" >
						<br/>
						<p><img src="<?php echo SITEURL . 'category/' . h($Category['Category']['image'] ?? ''); ?>" alt=""/></p>
					</div>
				</div>
				<div class="form-group">
					<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Status: </label>
						<div class="col-sm-9">
						<?php if (($Category['Category']['status'] ?? 0) == 1) { $active='checked';$inactive='';} else {  $active='';$inactive='checked';} ?>

						<div class="radio"><label>
						<input type="radio"  value="1" <?php echo $active; ?>  class="ace" id="ServiceCategoryActive1" name="data[Category][status]"><span class="lbl"> Active</span>
						</label></div>

						<div class="radio">
							<label>
							<input type="radio" value="0" class="ace" <?php echo $inactive; ?> id="ServiceCategoryActive0" name="data[Category][status]"><span class="lbl"> Inactive</span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Is Brand: </label>
						<div class="col-sm-9">
						<?php if (($Category['Category']['is_brand'] ?? 0) == 1) { $bactive='checked';$binactive='';} else {  $bactive='';$binactive='checked';} ?>

						<div class="radio"><label>
						<input type="radio"  value="1" <?php echo $bactive; ?>  class="ace" id="BrandYes" name="data[Category][is_brand]"><span class="lbl"> Yes</span>
						</label></div>

						<div class="radio"><label>
							<input type="radio" value="0" class="ace" <?php echo $binactive; ?> id="BrandNo" name="data[Category][is_brand]"><span class="lbl"> No</span>
						</label></div>


					</div>
				</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-3 col-md-9">
				<?php echo $this->Form->submit('Submit', ['div'=>false,'label'=>false, 'class' => 'btn btn-success','id'=>'add_ser_prd_btn','value'=>'Submit']);?>&nbsp;
				<?php echo $this->Html->link('Cancel','javascript:window.history.back();', ['class' => 'btn btn-danger']);?>

			</div>
		</div>
		<?php echo $this->Form->end(); ?>
		</div>
		</div>
	<script type="text/javascript">
			jQuery(function(){

				$("#category_name").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter service category name"
                });
			});
			</script>
