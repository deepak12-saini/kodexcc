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
				
			<a href="<?php echo SITEURL.'admin/products/addlabel'?>" class="btn btn-mini btn-success">Add Label</a>	
			<form action="" method="post" style="float:right;">
				
				<select  name="data[label_cate_id]" id="category_id">
					<option value="">Please select category</option>
					<?php foreach($LabelCategory as $LabelCategoryArr){?>
						<option <?php if($label_cate_id == $LabelCategoryArr['LabelCategory']['id']){ echo 'selected'; }  ?>  value="<?php echo $LabelCategoryArr['LabelCategory']['id']?>" ><?php echo $LabelCategoryArr['LabelCategory']['category']?></option>
					<?php }?>
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
					<th><?php echo $this->Paginator->sort('Label Category'); ?></th>
					<th><?php echo $this->Paginator->sort('name','Label Name'); ?></th>
					<th><?php echo $this->Paginator->sort('weight'); ?></th>	
					<th><?php echo $this->Paginator->sort('Download Files'); ?></th>             			
					<th>Action</th>             			
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($labelArr)){ $i=1;  foreach ($labelArr as $labelArrs): ?>
					<tr>
						<td>#<?php echo $i; ?>&nbsp;</td>
						<td><?php echo h($labelArrs['LabelCategory']['category']); ?>&nbsp;</td>					
						<td><?php echo h($labelArrs['Label']['name']); ?>&nbsp;</td>					
						<td><?php echo h($labelArrs['Label']['weight']); ?>&nbsp;</td>
						<td><a href="<?php echo SITEURL.'products/download/'.$labelArrs['Label']['url']; ?>"><?php echo $labelArrs['Label']['url']; ?></a>&nbsp;</td>					
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