	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Product<small><i class="ace-icon fa fa-angle-double-right"></i> Add Product</small>
		</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
	
		<?php echo $this->Form->create('Product',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data'));?>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Category: </label>
				<div class="col-sm-9">
				
					<select class="col-xs-10 col-sm-5" name="data[Product][category_id]" id="category_id">
					<option value="">Please select  category </option>
						<?php foreach($category as $category_arr){?>
							<option value="<?php echo $category_arr['Category']['id']?>" ><?php echo $category_arr['Category']['category_name']?></option>
						<?php }?>
					</select>

				</div>
			</div>
			<!-- <div class="form-group" style="display:none;">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> SubCategory: </label>
				<div class="col-sm-9">
				
					<select class="col-xs-10 col-sm-5" name="data[Product][sub_category_id]" id="sub_category_id">
						<option value="">Please select sub category </option>
					</select>

				</div>
			</div> -->
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product Name : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][title]" id="product_name" class="col-xs-10 col-sm-5" placeholder="Product Name">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product Code : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][product_code]" id="product_code" class="col-xs-10 col-sm-5" placeholder="Product Code" value="<?php echo $product_code?>">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Brief Description : </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][brief_description]" id="brief_description" class="col-xs-10 col-sm-5" placeholder="Brief Description"></textarea>

				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description : </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][description]" id="description" class="ckeditor col-xs-10 col-sm-5" placeholder="Description"></textarea>

				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Featured : </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][feature]" id="feature"  class="ckeditor col-xs-10 col-sm-5" placeholder="Description"></textarea>

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User Area : </label>
				<div class="col-sm-9">
					<textarea type="text" name="data[Product][userarea]" id="userarea"  class="ckeditor col-xs-10 col-sm-5" placeholder="Description"></textarea>

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Price ($) : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][price_dollar]" id="price_dollar" class="col-xs-10 col-sm-5" placeholder="Price ($)">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Stock: </label>
				<div class="col-sm-9">
					<input type="number" name="data[Product][stock]" id="stock" class="col-xs-10 col-sm-5" placeholder="Stock">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Technical Literature : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][datasheet]" id="datasheet" class="col-xs-10 col-sm-5" placeholder="Technical Literature">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Product MSDS : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][msds]" id="msds" class="col-xs-10 col-sm-5" placeholder="Product MSDS">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Voc sheet : </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][voc_pdf]" id="voc" class="col-xs-10 col-sm-5" placeholder="Voc sheet">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Title: </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][meta_title]" id="meta_title" class="col-xs-10 col-sm-5" placeholder="Meta Title">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Keyword: </label>
				<div class="col-sm-9">
					<input type="text" name="data[Product][keyword]" id="meta_keyword" class="col-xs-10 col-sm-5" placeholder="Meta Keyword">

				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Meta Description: </label>
				<div class="col-sm-9">
					<textarea name="data[Product][meta_description]" id="meta_description" class="col-xs-10 col-sm-5" placeholder="Meta Description" ></textarea>
				
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">upload Product Image : </label>
				<div class="col-sm-9">
					<input type="file" name="data[Product][image]" id="image" class="col-xs-10 col-sm-5">

				</div>
			</div>										
			<div class="form-group">
			<label for="form-field-2" class="col-sm-3 control-label no-padding-right">Product Status : </label>
				<div class="col-sm-9">
				
				
				<div class="radio"><label>
				<input type="radio" checked="checked" value="1"  class="ace" id="CategoryActive1" name="data[Product][status]"><span class="lbl"> Active</span>
				</label></div>
				
				<div class="radio"><label>
					<input type="radio" value="0"  class="ace" id="CategoryActive0" name="data[Product][status]"><span class="lbl"> Inactive</span>
				</label></div>				
				</div>
			</div>
			<div class="form-group">
			<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Featured : </label>
				<div class="col-sm-9">
				
				
				<div class="radio"><label>
				<input type="radio"  value="1"  class="ace" id="CategoryActive1" name="data[Product][is_featured]"><span class="lbl"> featured</span>
				</label></div>
				
				<div class="radio"><label>
					<input type="radio" checked="checked" value="0"  class="ace" id="CategoryActive0" name="data[Product][is_featured]"><span class="lbl"> Non featured</span>
				</label></div>				
				</div>
			</div>	
				<div class="form-group">
			<label for="form-field-2" class="col-sm-3 control-label no-padding-right"> Is Image : </label>
				<div class="col-sm-9">
								
				<div class="radio"><label>
				<input type="radio"  value="0"  class="ace" id="CategoryActive1" name="data[Product][is_image]"><span class="lbl"> Yes Image</span>
				</label></div>
				
				<div class="radio"><label>
					<input type="radio" checked="checked" value="1"  class="ace" id="CategoryActive0" name="data[Product][is_image]"><span class="lbl">
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
		message: "Please enter product name"
	});$("#product_code").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter product code"
	}); $("#price_euro").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter price euro"
	}); $("#price_dollar").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter price dollar"
	});  $("#stock").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter stock"
	}); $("#category_id").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select category"
});
});
</script>
