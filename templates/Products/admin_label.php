	<style>
		select {
			padding: 0px 4px;
			height: 29px;
			width: 205px;
			font-size: 14px;
		}
		.label {			
			margin-top: 5px;
		}
	</style>
	<div class="page-content">
		<div class="page-header">
			<h1>			
			Product Label List
				
			<a href="<?php echo SITEURL; ?>admin/products/add-label" class="btn btn-mini btn-success">Add Label</a>	
			<form action="" method="post" style="float:right;">
				
				<?php $filterCatId = $category_id ?? $label_cate_id ?? ''; ?>
				<select name="category_id" id="category_id">
					<option value="">Please select category</option>
					<?php foreach ($LabelCategory as $LabelCategoryArr) { ?>
						<option <?php if ((string)$filterCatId === (string)($LabelCategoryArr['LabelCategory']['id'] ?? '')) { echo 'selected'; } ?> value="<?php echo h($LabelCategoryArr['LabelCategory']['id'] ?? ''); ?>"><?php echo h($LabelCategoryArr['LabelCategory']['category'] ?? $LabelCategoryArr['LabelCategory']['name'] ?? ''); ?></option>
					<?php } ?>
				</select>

				<input type="text" name="label" value="<?php echo $label; ?>" placeholder="Search By Label" >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs top-button" >	
				<a href="<?php echo SITEURL.'admin/products/label'?>" class="btn btn-warning btn-xs top-button">Clear All</a>
			</form>				
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('id'); ?></th>
					<th><?php echo $this->Paginator->sort('LabelCategory.name', __('Label Category')); ?></th>
					<th><?php echo $this->Paginator->sort('Label.name', __('Label Name')); ?></th>
					<th><?php echo __('Weight'); ?></th>
					<th><?php echo __('Download Files'); ?></th>             			
					<th>Action</th>             			
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($labelArr)){ $i=1;  foreach ($labelArr as $labelArrs): ?>
					<tr>
						<td>#<?php echo $i; ?>&nbsp;</td>
						<td><?php echo h($labelArrs['LabelCategory']['category'] ?? $labelArrs['LabelCategory']['name'] ?? ''); ?>&nbsp;</td>
						<td><?php echo h($labelArrs['Label']['name'] ?? ''); ?>&nbsp;</td>
						<td><?php echo h($labelArrs['Label']['weight'] ?? ''); ?>&nbsp;</td>
						<td><?php $u = $labelArrs['Label']['url'] ?? ''; ?><?php if ($u !== '') { ?><a href="<?php echo SITEURL . 'products/download/' . h($u); ?>"><?php echo h($u); ?></a><?php } ?>&nbsp;</td>					
						<td>
						<a href="<?php echo SITEURL.'admin/products/editlabel/'.$labelArrs['Label']['id']; ?>" class="btn btn-mini btn-info">Edit</a>&nbsp;
						
						<a href="<?php echo SITEURL.'admin/products/deletelabel/'.$labelArrs['Label']['id']; ?>" onclick="return confirm('Are you sure delete?')" class="btn btn-mini btn-danger">Delete</a>&nbsp;
						
						</td>					
					</tr>
					<?php $i++; endforeach; }else{ ?>
						<tr>
							 <td colspan="10" style="text-align:center; color:red; font-weight:bold;">No Product label Found</td>
						</tr>
					<?php } ?>

				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php echo h($this->Paginator->counter('showing {{current}} records out of {{count}} entries')); ?></div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
			<ul class="pagination">
				<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
				echo $this->Paginator->prev('< ' . __('previous')); ?></li>
				
				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >'); ?></li>
				
			</ul>
			</div>
		</div>
	</div>	
</div>		