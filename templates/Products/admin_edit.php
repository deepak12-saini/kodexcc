<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>	
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Product<small><i class="ace-icon fa fa-angle-double-right"></i> Edit Product</small>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
	<?php $p = $product_arr['Product'] ?? []; ?>
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'role' => 'form', 'type' => 'file', 'secure' => false]); ?>
		<input type="hidden"  name="data[Product][id]" value="<?php echo h($p['id'] ?? ''); ?>" >
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category: </label>
				<div class="col-sm-9">
				
					<select class="col-xs-10 col-sm-5" name="data[Product][category_id]" id="category_id">
					
						<?php foreach($category as $category_arr){?>
							<option value="<?php echo $category_arr['Category']['id']?>" <?php if (($category_arr['Category']['id'] ?? '') == ($p['category_id'] ?? '')) { echo 'selected'; } ?>><?php echo $category_arr['Category']['category_name']?></option>
						<?php }?>
					</select>

				</div>
			</div>
			<!-- <div class="form-group" style="display:none;">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> SubCategory: </label>
				<div class="col-sm-9">
				
					<select class="col-xs-10 col-sm-5" name="data[Product][sub_category_id]" id="sub_category_id">
						
							<option value="" >Please select sub category</option>
							<?php foreach($subcategory as $subcategory_arr){?>
							<option value="<?php echo $subcategory_arr['Subcategory']['id']?>" <?php if (($subcategory_arr['Subcategory']['id'] ?? '') == ($p['sub_category_id'] ?? '')) { echo 'selected'; } ?>><?php echo $subcategory_arr['Subcategory']['name']?></option>
						<?php }?>
					</select>
			
				
				</div>
			</div> -->
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product Name : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][title]" id="product_name" class="col-xs-10 col-sm-5" value="<?php echo h($p['title'] ?? ''); ?>" placeholder="Product Name">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product Code : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][product_code]" id="product_code" class="col-xs-10 col-sm-5" placeholder="Product Code" value="<?php echo h($p['product_code'] ?? ''); ?>">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Brief Description : </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][brief_description]" id="brief_description" class="col-xs-10 col-sm-5" placeholder="Brief Description"><?php echo h($p['brief_description'] ?? ''); ?></textarea>

				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description : </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][description]" id="description"  class="ckeditor col-xs-10 col-sm-5" placeholder="Description"><?php echo $p['description'] ?? ''; ?></textarea>

				</div>
			</div>	
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Featured: </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][feature]" id="feature"  class="ckeditor col-xs-10 col-sm-5" placeholder="Description"><?php echo $p['feature'] ?? ''; ?></textarea>

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User Area: </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][userarea]" id="userarea"  class="ckeditor col-xs-10 col-sm-5" placeholder="Description"><?php echo $p['userarea'] ?? ''; ?></textarea>

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Instruction: </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][instruction]" id="instruction"  class="ckeditor col-xs-10 col-sm-5" placeholder="Instruction"><?php echo $p['instruction'] ?? ''; ?></textarea>

				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Price Dollar: </label>
				<div class="col-sm-9">
					<input type="number" name="data[Product][price_dollar]" id="price_dollar" value="<?php echo h($p['price_dollar'] ?? ''); ?>" class="col-xs-10 col-sm-5" placeholder="Price Euro">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Stock: </label>
				<div class="col-sm-9">
					<input type="number" name="data[Product][stock]" id="stock" value="<?php echo h($p['stock'] ?? ''); ?>" class="col-xs-10 col-sm-5" placeholder="Stock">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Technical Literature : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][datasheet]" id="datasheet" class="col-xs-10 col-sm-5" value="<?php echo h($p['datasheet'] ?? ''); ?>"  placeholder="Technical Literature">

				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Product MSDS : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][msds]" id="msds" class="col-xs-10 col-sm-5" value="<?php echo h($p['msds'] ?? ''); ?>"  placeholder="Product MSDS">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Voc sheet : </label>
				<div class="col-sm-9">
					<input type="text"  value="<?php echo h($p['voc_pdf'] ?? ''); ?>"  name="data[Product][voc_pdf]" id="voc" class="col-xs-10 col-sm-5" placeholder="Voc sheet">

				</div>
			</div>
			
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="product-main-image">Main product image</label>
				<div class="col-sm-9">
					<input type="file" name="data[Product][image]" id="product-main-image" class="col-xs-10 col-sm-5" accept="image/*">

				</div>
			</div>
			<?php if (!empty($p['image'])) { ?>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Main image preview</label>
				<div class="col-sm-9">
					<img style="height:50px; width:50px"src="<?php echo SITEURL.'productimg/'.h($p['image']); ?>">

				</div>
			</div>				
			<?php }?>
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="product-tds-image">TDS / datasheet image</label>
				<div class="col-sm-9">
					<input type="file" name="data[Product][tds_image]" id="product-tds-image" class="col-xs-10 col-sm-5" accept="image/*">

				</div>
			</div>
			<?php if (!empty($p['tds_image'])) { ?>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">TDS image preview</label>
				<div class="col-sm-9">
					<img style="height:50px; width:50px"src="<?php echo SITEURL.'productimg/'.h($p['tds_image']); ?>">

				</div>
			</div>				
			<?php }?>
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Product Size: </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][sizes]" id="sizes" value="<?php echo h($p['sizes'] ?? ''); ?>" class="col-xs-10 col-sm-5" placeholder="Size">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Title: </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][meta_title]" id="meta_title" value="<?php echo h($p['meta_title'] ?? ''); ?>" class="col-xs-10 col-sm-5" placeholder="Meta Title">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Keyword: </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][keyword]" id="meta_keyword" value="<?php echo h($p['keyword'] ?? ''); ?>" class="col-xs-10 col-sm-5" placeholder="Meta Keyword">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Description: </label>
				<div class="col-sm-9">
					<textarea name="data[Product][meta_description]" id="meta_description" class="col-xs-10 col-sm-5" placeholder="Meta Description" ><?php echo h($p['meta_description'] ?? ''); ?></textarea>
				
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Video Url : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][video_url]"  value="<?php echo h($p['video_url'] ?? ''); ?>" class="col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
					<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Product Type : </label>
						<div class="col-sm-9">
						<?php
						$productType = (int)($p['product_type'] ?? 0);
						if ($productType === 1) {
							$normal_inactive = '';
							$product_active = 'checked';
							$product_inactive = '';
						} elseif ($productType === 2) {
							$normal_inactive = '';
							$product_active = '';
							$product_inactive = 'checked';
						} else {
							$product_active = '';
							$product_inactive = '';
							$normal_inactive = 'checked';
						}
						?>
						<div class="radio"><label>
						<input type="radio"  value="0" <?php echo $normal_inactive; ?>  class="ace" id="ServiceCategoryActive1" name="data[Product][product_type]"><span class="lbl"> Normal Product</span>
						</label></div>
						
						<div class="radio"><label>
						<input type="radio"  value="1" <?php echo $product_active; ?>  class="ace" id="ServiceCategoryActive1" name="data[Product][product_type]"><span class="lbl"> Product Type</span>
						</label></div>
						
						<div class="radio"><label>
							<input type="radio" value="2" class="ace" <?php echo $product_inactive; ?> id="ServiceCategoryActive0" name="data[Product][product_type]"><span class="lbl"> By Use</span>
						</label></div>
						
						
					</div>
				</div>
				<div class="form-group">
					<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Status : </label>
						<div class="col-sm-9">
						<?php if ((int)($p['status'] ?? 0) === 1) { $active = 'checked'; $inactive = ''; } else { $active = ''; $inactive = 'checked'; } ?>
						
						<div class="radio"><label>
						<input type="radio"  value="1" <?php echo $active; ?>  class="ace" id="ServiceCategoryActive1" name="data[Product][status]"><span class="lbl"> Active</span>
						</label></div>
						
						<div class="radio"><label>
							<input type="radio" value="0" class="ace" <?php echo $inactive; ?> id="ServiceCategoryActive0" name="data[Product][status]"><span class="lbl"> Inactive</span>
						</label></div>
						
						
					</div>
				</div>
				<div class="form-group">
					<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Featured : </label>
						<div class="col-sm-9">
						<?php if ((int)($p['is_featured'] ?? 0) === 1) { $active = 'checked'; $inactive = ''; } else { $active = ''; $inactive = 'checked'; } ?>
						
						<div class="radio"><label>
						<input type="radio"  value="1" <?php echo $active; ?>  class="ace" id="ServiceCategoryActive1" name="data[Product][is_featured]"><span class="lbl"> Featured</span>
						</label></div>
						
						<div class="radio"><label>
							<input type="radio" value="0" class="ace" <?php echo $inactive; ?> id="ServiceCategoryActive0" name="data[Product][is_featured]"><span class="lbl"> Non featured</span>
						</label></div>
						
						
					</div>
				</div>
				<div class="form-group">
			<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Is Image : </label>
				<div class="col-sm-9">
				
				<?php if ((int)($p['is_image'] ?? 0) === 1) { $inactives = 'checked'; $actives = ''; } else { $inactives = ''; $actives = 'checked'; } ?>
						
				<div class="radio"><label>
				<input type="radio"  value="0"  <?php echo $actives; ?> class="ace" id="CategoryActive1" name="data[Product][is_image]"><span class="lbl"> Yes Image</span>
				</label></div>
				
				<div class="radio"><label>
					<input type="radio"  <?php echo $inactives; ?> value="1"  class="ace" id="CategoryActive0" name="data[Product][is_image]"><span class="lbl">
					No Image</span>
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
	$("#product_name").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter  product name"
	});  $("#price_euro").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter price_euro"
	}); $("#price_dollar").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter price_dollar"
	});  $("#stock").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter stock"
	}); $("#category_id").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select category"
	});  $("#sub_category_id").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select sub category"
	}); 
});
</script>
