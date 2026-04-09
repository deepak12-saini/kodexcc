	
	<style>
		.btn{margin-bottom: 5px; }
	</style>
	
	<div class="page-content">

		<div class="page-header">
			<h1>Price List</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>				
					<th><?php echo $this->Paginator->sort('id'); ?></th>				
					<th><?php echo $this->Paginator->sort('label','Level'); ?></th>				
					<th><?php echo $this->Paginator->sort('filename'); ?></th>
					<th><?php echo $this->Paginator->sort('password','File Password'); ?></th>				
					<th><?php echo $this->Paginator->sort('created','Last Modify Date'); ?></th>
					<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($staffArrArr as $labelArr): ?>
					<tr>
					<td><?php echo h($labelArr['LabFile']['id']); ?></td>
					<td><?php echo h($labelArr['LabFile']['label']); ?></td>
					<td>
					<?php if(!empty($labelArr['LabFile']['filename'])){ ?>
						<a target="_blank" href="<?php echo SITEURL.$labelArr['LabFile']['dir'].'/'.$labelArr['LabFile']['filename'] ?>">Download <?php echo h($labelArr['LabFile']['label']); ?></a>
					<?php }else{ ?>
						N/A
					<?php } ?>					
					</td>
					<td>
					
					<?php if(!empty($labelArr['LabFile']['password'])){ echo h($labelArr['LabFile']['password']); }else{ echo 'N/A'; }?></td>
								
                    <td><?php echo date('d M Y H:i a',strtotime($labelArr['LabFile']['created'])); ?></td>
                    <td>						
						<a class="btn btn-xs btn-success"href="<?php echo SITEURL.'admin/users/updatelabfile/'.$labelArr['LabFile']['id']?>">Update</a>						
					</td>					
					</tr>
					<?php endforeach; ?>

				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php	echo $this->Paginator->counter(array('format' => __('showing {:current} records out of {:count} entries')));?>	</div>
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