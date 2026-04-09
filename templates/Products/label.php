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
		</form>				
		</h1>
	</div>
	
	<?php if(empty($UserPermission)){ ?>
	
		<br><br>
			<div class="durolab_product_section col-xs-12">		
				<div class="span3 widget-container-span ui-sortable">
					<div class="widget-box light-border">
					
						<div class="widget-body">
							<div class="widget-main padding-6">
								
								<div class="alert alert-danger" style=" font-size:20px; padding:50px; ">
								Sorry, you don't have access to this folder. You can request the admin to give you access. <a href="<?php echo SITEURL.'products/permissionrequest'; ?>">Click here</a>
								
								
							</div>
						</div>
					</div>
				</div>

			</div>
	
	<?php }else if(!empty($UserPermission)){ ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('id'); ?></th>
					<th><?php echo $this->Paginator->sort('Label Category'); ?></th>
						
					<th><?php echo $this->Paginator->sort('name','Label Name'); ?></th>
					<th><?php echo $this->Paginator->sort('weight'); ?></th>	
					<th><?php echo $this->Paginator->sort('Download Files'); ?></th>             			
					        			
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
	<?php } ?>
</div>		