	
	<style>
		.btn{margin-bottom: 5px; }
		select {
	padding: 3px 4px;
	height: 31px;
	float: left;
	font-size: 13px;
	margin: 0px 20px;
}
input {
	float: left;
}
	</style>
	
	<div class="page-content">
	<div class="page-header">
		<h1>Product Documents
		
			<form action="" method="post" style="float:right;">
				<select name="category_id" >
					<option value="">Select Category</option>
					<?php
						foreach($categoryArr as $categoryArrs){
					?>	
						<option <?php if($category_id == $categoryArrs['Category']['id']){ echo 'selected'; }?> value="<?php echo $categoryArrs['Category']['id']?>"><?php echo $categoryArrs['Category']['category_name']?></option>
					<?php  } ?>
				</select>
				<input type="text" name="productname" style="padding:7px 11px !important;" value="<?php echo $name; ?>" placeholder="Search By Product Name" >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs top-button" style="margin-left:4px;">	
				<a href="<?php echo SITEURL.'staffs/datasheet'; ?>"  class="btn btn-warning btn-xs top-button" >Clear All</a>	
			</form>		
		</h1>
	</div>
	<?php
		$is_msds =0;
		$is_datasheet =0;
		$is_label =0;
		$is_voc =0;
		if(!empty($upermission)){
			foreach($upermission as $upermissions){
				if($upermissions['UserPermission']['meta_val'] == 'datasheet'){
					$is_datasheet =1;
				}
				if($upermissions['UserPermission']['meta_val'] == 'msds'){
					$is_msds =1;
				}
				if($upermissions['UserPermission']['meta_val'] == 'voc'){
					$is_voc =1;
				}
				if($upermissions['UserPermission']['meta_val'] == 'label'){
					$is_label =1;
				}
			}
		}	
	?>
	
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>						
					<th><?php echo $this->Paginator->sort('title','Product Name'); ?></th>				
					<th><?php echo $this->Paginator->sort('category_id','Category Name'); ?></th>
					<th><?php echo $this->Paginator->sort('image','Image'); ?></th>				
					<th><?php echo $this->Paginator->sort('datasheet','Datasheet'); ?></th>				
					<th><?php echo $this->Paginator->sort('msds','MSDS'); ?></th>				
					<th><?php echo $this->Paginator->sort('voc_pdf','VOC'); ?></th>				
					<!-- <th><?php echo $this->Paginator->sort('product_label'); ?></th>			 -->	
					
				</tr>
				</thead>
				<tbody>
					<?php 
						if(!empty($productArr)){
					foreach ($productArr as $productArr): ?>
					<tr>
					<td><?php echo h($productArr['Product']['title']); ?></td>
					<td><?php echo h($productArr['Category']['category_name']); ?></td>
					<td><img src="<?php echo SITEURL.'productimg/'.$productArr['Product']['image']; ?>" width="100" /></td>
					<td>
					<?php 
					if($is_datasheet == 1){
						if(!empty($productArr['Product']['datasheet'])){
							$datasheet = explode("##",$productArr['Product']['datasheet']);
							if(count($datasheet) == 2){
						?>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/datasheet/0'; ?>" class="label label-primary">Datasheet 1 </a>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/datasheet/1'; ?>" class="label label-primary">Datasheet 1 </a>
						<?php	}else{ ?>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/datasheet/0'; ?>" class="label label-primary">Datasheet </a>
						<?php 
							}						
						}else{
							echo '<label class="label label-danger">No Datasheet Avilable</label>';
						}
					}else{
						echo '<label class="label label-danger">No Access</label>';
					}	
					?>
					
					</td>
					<td>
					<?php 
					if($is_msds == 1){
						if(!empty($productArr['Product']['msds'])){
							$msds = explode("##",$productArr['Product']['msds']);
							if(count($msds) == 2){
						?>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/msds/0'; ?>" class="label label-info">MSDS 1 </a>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/msds/1'; ?>" class="label label-info">MSDS 2 </a>
						<?php	}else{ ?>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/msds/0'; ?>" class="label label-info">MSDS </a>
						<?php 
							}						
						}else{
							echo '<label class="label label-danger">No MSDS Avilable</label>';
						}
					}else{
						echo '<label class="label label-danger">No Access</label>';
					}		
					?>
					</td>
					<td>
					<?php 
					if($is_voc == 1){
						if(!empty($productArr['Product']['voc_pdf'])){
							$voc_pdf = explode("##",$productArr['Product']['voc_pdf']);
							if(count($voc_pdf) == 2){
						?>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/voc/0'; ?>" class="label label-success">VOC 1 </a>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/voc/1'; ?>" class="label label-success">VOC 1 </a>
						<?php	}else{ ?>
							<a href="<?php echo SITEURL.'staffs/document/'.base64_encode($productArr['Product']['product_code']).'/voc/0'; ?>" class="label label-success">VOC </a>
						<?php 
							}						
						}else{
							echo '<label class="label label-danger">No VOC Avilable</label>';
						}
					}else{
						echo '<label class="label label-danger">No Access</label>';
					}			
					?>
					
					</td>
				   	<!--td>
					<?php 
					if($is_label == 1){
						if(!empty($productArr['Product']['product_label'])){
													
						}else{
							echo '<label class="label label-danger">No Label Avilable</label>';
						}
					}else{
						echo '<label class="label label-danger">No Access</label>';
					}					
					?>
					
					</td-->			
					</tr>
					<?php endforeach; }else{ ?>
						<tr><td colspan="7" style="text-align:center">No Product Found</td></tr>
					<?php }?>

				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php	echo $this->Paginator->counter(array(
'format' => __('showing {:current} records out of {:count} entries')));?>	</div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
			<ul class="pagination">
				<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
				echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));?></li>
				
				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));?></li>
				
			</ul>
			</div>
		</div>
	</div>	
	
</div>		