	<div class="page-content">
		<div class="page-header">
			<h1>
			
			Product List <?php echo $this->Html->link('Add New',array('controller' => 'products','action' => 'admin_add'),array('class'=>'btn btn-info btn-xs top-button')); ?>
			
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
				<th><?php echo $this->Paginator->sort('Category Name'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('SubCategory Name'); ?></th> -->
				<th><?php echo $this->Paginator->sort('brief_description'); ?></th>
				<th><?php echo $this->Paginator->sort('image'); ?></th>
			
				 <th><?php echo $this->Paginator->sort('active'); ?></th>
				 <th><?php echo $this->Paginator->sort('featured'); ?></th>
                  <th><?php echo $this->Paginator->sort('created'); ?></th>

				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($product)){ foreach ($product as $product_arr): ?>
					<tr>
					<td>#<?php echo h($product_arr['Product']['id']); ?>&nbsp;</td>
					<td><?php echo h($product_arr['Product']['title']); ?>&nbsp;</td>
					<td><?php echo h($product_arr['Product']['product_code']); ?>&nbsp;</td>
					<td><?php echo h($product_arr['Category']['category_name']); ?>&nbsp;</td>
					<!-- <td><?php echo h($product_arr['Subcategory']['name']); ?>&nbsp;</td> -->
					<td><?php echo h($product_arr['Product']['brief_description']); ?>&nbsp;</td>
					<td>
					<?php if(!empty($product_arr['Product']['image'])){?>
						<img style="height:50px; width:50px"src="<?php echo SITEURL.'productimg/'.$product_arr['Product']['image']?>">
					<?php }else{?>
						No Image
					<?php }?>
					</td>
					 <td>
						<?php if($product_arr['Product']['status']==1){?>
							<span class="label label-success">Active</span>
						<?php }else{ ?>
						<span class="label label-danger">Inactive</span>
						<?php }?>&nbsp;
					</td>
					 <td>
						<?php if($product_arr['Product']['is_featured']==1){?>
							<span class="label label-success">Featured</span>
						<?php }else{ ?>
						<span class="label label-danger">Non featured</span>
						<?php }?>&nbsp;
					</td>
                    <td> <?php echo date('d-M-Y',strtotime($product_arr['Product']['created'])); ?></td>
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