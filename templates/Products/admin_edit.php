<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>	
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Product<small><i class="ace-icon fa fa-angle-double-right"></i> Edit Product</small>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
	
		<?php echo $this->Form->create('Product',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data'));?>
		<input type="hidden"  name="data[Product][id]" value="<?php echo $product_arr['Product']['id'] ?>" >
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category: </label>
				<div class="col-sm-9">
				
					<select class="col-xs-10 col-sm-5" name="data[Product][category_id]" id="category_id">
					
						<?php foreach($category as $category_arr){?>
							<option value="<?php echo $category_arr['Category']['id']?>" <?php if($category_arr['Category']['id']== $product_arr['Product']['category_id']){ echo 'selected';}?>><?php echo $category_arr['Category']['category_name']?></option>
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
							<option value="<?php echo $subcategory_arr['Subcategory']['id']?>" <?php if($subcategory_arr['Subcategory']['id']== $product_arr['Product']['sub_category_id']){ echo 'selected';}?>><?php echo $subcategory_arr['Subcategory']['name']?></option>
						<?php }?>
					</select>
			
				
				</div>
			</div> -->
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product Name : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][title]" id="product_name" class="col-xs-10 col-sm-5" value="<?php echo $product_arr['Product']['title']?>"placeholder="Product Name">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product Code : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][product_code]" id="product_code" class="col-xs-10 col-sm-5" placeholder="Product Code" value="<?php echo $product_arr['Product']['product_code']?>">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Brief Description : </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][brief_description]" id="brief_description" class="col-xs-10 col-sm-5" placeholder="Brief Description"><?php echo $product_arr['Product']['brief_description']?></textarea>

				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description : </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][description]" id="description"  class="ckeditor col-xs-10 col-sm-5" placeholder="Description"><?php echo $product_arr['Product']['description']?></textarea>

				</div>
			</div>	
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Featured: </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][feature]" id="feature"  class="ckeditor col-xs-10 col-sm-5" placeholder="Description"><?php echo $product_arr['Product']['feature']?></textarea>

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User Area: </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][userarea]" id="userarea"  class="ckeditor col-xs-10 col-sm-5" placeholder="Description"><?php echo $product_arr['Product']['userarea']?></textarea>

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Instruction: </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][instruction]" id="instruction"  class="ckeditor col-xs-10 col-sm-5" placeholder="Instruction"><?php echo $product_arr['Product']['instruction']?></textarea>

				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Price Dollar: </label>
				<div class="col-sm-9">
					<input type="number" name="data[Product][price_dollar]" id="price_dollar" value="<?php echo $product_arr['Product']['price_dollar']?>" class="col-xs-10 col-sm-5" placeholder="Price Euro">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Stock: </label>
				<div class="col-sm-9">
					<input type="number" name="data[Product][stock]" id="stock" value="<?php echo $product_arr['Product']['stock']?>" class="col-xs-10 col-sm-5" placeholder="Stock">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Technical Literature : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][datasheet]" id="datasheet" class="col-xs-10 col-sm-5" value="<?php echo $product_arr['Product']['datasheet']?>"  placeholder="Technical Literature">

				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Product MSDS : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][msds]" id="msds" class="col-xs-10 col-sm-5" value="<?php echo $product_arr['Product']['msds']?>"  placeholder="Product MSDS">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Voc sheet : </label>
				<div class="col-sm-9">
					<input type="text"  value="<?php echo $product_arr['Product']['voc_pdf']?>"  name="data[Product][voc_pdf]" id="voc" class="col-xs-10 col-sm-5" placeholder="Voc sheet">

				</div>
			</div>
			
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Image : </label>
				<div class="col-sm-9">
					<input type="file" name="data[Product][image]"  class="col-xs-10 col-sm-5">

				</div>
			</div>
			<?php if(!empty($product_arr['Product']['image'])){ ?>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Preview Image : </label>
				<div class="col-sm-9">
					<img style="height:50px; width:50px"src="<?php echo SITEURL.'productimg/'.$product_arr['Product']['image']?>">

				</div>
			</div>				
			<?php }?>
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Image : </label>
				<div class="col-sm-9">
					<input type="file" name="data[Product][tds_image]"  class="col-xs-10 col-sm-5">

				</div>
			</div>
			<?php if(!empty($product_arr['Product']['tds_image'])){ ?>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> TDS Preview Image : </label>
				<div class="col-sm-9">
					<img style="height:50px; width:50px"src="<?php echo SITEURL.'productimg/'.$product_arr['Product']['tds_image']?>">

				</div>
			</div>				
			<?php }?>
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Product Size: </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][sizes]" id="sizes" value="<?php echo $product_arr['Product']['sizes']; ?>" class="col-xs-10 col-sm-5" placeholder="Size">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Title: </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][meta_title]" id="meta_title" value="<?php echo $product_arr['Product']['meta_title']; ?>" class="col-xs-10 col-sm-5" placeholder="Meta Title">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Keyword: </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][keyword]" id="meta_keyword" value="<?php echo $product_arr['Product']['keyword']; ?>" class="col-xs-10 col-sm-5" placeholder="Meta Keyword">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Description: </label>
				<div class="col-sm-9">
					<textarea name="data[Product][meta_description]" id="meta_description" class="col-xs-10 col-sm-5" placeholder="Meta Description" ><?php echo $product_arr['Product']['meta_description']; ?></textarea>
				
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Video Url : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][video_url]"  value="<?php echo $product_arr['Product']['video_url']?>" class="col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
					<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Product Type : </label>
						<div class="col-sm-9">
						<?php 
						
						if($product_arr['Product']['product_type'] == 1){  $normal_inactive=''; $product_active='checked';$product_inactive=''; }else if($product_arr['Product']['product_type'] == 2) {   $normal_inactive=''; $product_active='';$product_inactive='checked';}else{
						$product_active=''; $product_inactive=''; $normal_inactive='checked'; 
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
						<?php if($product_arr['Product']['status'] == 1) { $active='checked';$inactive='';}else{  $active='';$inactive='checked';}?>
						
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
						<?php if($product_arr['Product']['is_featured'] == 1) { $active='checked';$inactive='';}else{  $active='';$inactive='checked';}?>
						
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
				
				<?php if($product_arr['Product']['is_image'] == 1){ $inactives='checked'; $actives='';}else{  $inactives='';$actives='checked';}?>
						
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
