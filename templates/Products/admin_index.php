	<div class="page-content">
		<div class="page-header">
			<h1>
			
			Product List <a href="<?php echo SITEURL; ?>admin/products/add" class="btn btn-info btn-xs top-button">Add New</a>
			
			<form action="" method="post" style="float:right;">
				<input type="text" name="productname" value="<?php echo $name; ?>" placeholder="Search By Product Name" >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs top-button" >	
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
				<th><?php echo $this->Paginator->sort('title','Product Name'); ?></th>
				<th><?php echo $this->Paginator->sort('product_code'); ?></th>
				<th><?php echo $this->Paginator->sort('Category.category_name', __('Category Name')); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('SubCategory Name'); ?></th> -->
				<th><?php echo $this->Paginator->sort('brief_description'); ?></th>
				<th><?php echo $this->Paginator->sort('image'); ?></th>
			
				 <th><?php echo $this->Paginator->sort('status', __('Active')); ?></th>
				 <th><?php echo $this->Paginator->sort('is_featured', __('Featured')); ?></th>
                  <th><?php echo $this->Paginator->sort('created'); ?></th>

				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($product)){ foreach ($product as $product_arr): ?>
					<tr>
					<td>#<?php echo h($product_arr['Product']['id'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($product_arr['Product']['title'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($product_arr['Product']['product_code'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($product_arr['Category']['category_name'] ?? ''); ?>&nbsp;</td>
					<!-- <td><?php echo h($product_arr['Subcategory']['name']); ?>&nbsp;</td> -->
					<td><?php echo h($product_arr['Product']['brief_description'] ?? ''); ?>&nbsp;</td>
					<td>
					<?php if(!empty($product_arr['Product']['image'])){?>
						<img style="height:50px; width:50px"src="<?php echo SITEURL.'productimg/'.$product_arr['Product']['image']?>">
					<?php }else{?>
						No Image
					<?php }?>
					</td>
					 <td>
						<?php if ((int)($product_arr['Product']['status'] ?? 0) === 1) { ?>
							<span class="label label-success">Active</span>
						<?php } else { ?>
						<span class="label label-danger">Inactive</span>
						<?php } ?>&nbsp;
					</td>
					 <td>
						<?php if ((int)($product_arr['Product']['is_featured'] ?? 0) === 1) { ?>
							<span class="label label-success">Featured</span>
						<?php } else { ?>
						<span class="label label-danger">Non featured</span>
						<?php } ?>&nbsp;
					</td>
                    <td><?php
						$created = $product_arr['Product']['created'] ?? null;
						echo !empty($created) ? h(date('d-M-Y', strtotime((string)$created))) : '';
					?></td>
					 <td class="actions">
						<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $product_arr['Product']['id']),array('class'=>'btn btn-mini btn-info')); ?>
						<?php echo $this->Html->link(__('Project Image'), array('action' => 'project', $product_arr['Product']['id']),array('class'=>'btn btn-mini btn-primary')); ?>
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $product_arr['Product']['id']), array('class'=>'btn btn-mini btn-danger','style'=>'margin-top:2px;'), __('Are you sure you want to delete # %s?', $product_arr['Product']['id'])); ?>
						
						</td>
					</tr>
					<?php endforeach; }else{ ?>
						<tr>
							 <td colspan="10" style="text-align:center; color:red; font-weight:bold;">No Product Found</td>
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