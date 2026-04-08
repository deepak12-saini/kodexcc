	<div class="page-content">
		<div class="page-header">
			<h1>Push Notification  <?php echo $this->Html->link('Send Notification',array('controller' => 'fronts','action' => 'admin_send'),array('class'=>'btn btn-info btn-xs top-button')); ?></h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
			    
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('type'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('description'); ?></th>
				<th><?php echo $this->Paginator->sort('Product'); ?></th>
				<th><?php echo $this->Paginator->sort('image'); ?></th>
				<th><?php echo $this->Paginator->sort('Date'); ?></th>
               
				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($notification as $notifications): ?>
					<tr>
					<td>#<?php echo h($notifications['Notification']['id']); ?>&nbsp;</td>
					<td>
					<?php 
					
					if($notifications['Notification']['type'] == 1){ 
						echo 'Text';
					}else if($notifications['Notification']['type'] == 2){ 
						echo 'Video URL';
					}else if($notifications['Notification']['type'] == 3){ 
						echo 'Emailer URL';
					}else if($notifications['Notification']['type'] == 4){ 
						echo 'Product';
					}					
					?></td>
					<td><?php echo h($notifications['Notification']['title']); ?>&nbsp;</td>
					<td><?php echo h($notifications['Notification']['description']); ?>&nbsp;</td>
					<td><?php echo h($notifications['Product']['title']); ?></td>
					
                    <td> 
					<?php if(!empty($notifications['Notification']['image'])){ ?><img src="<?php echo SITEURL.'product_image/'.$notifications['Notification']['image']; ?>" width="150"/><?php }else{ echo 'No Image'; } ?>
					</td>
					<td><?php echo h($notifications['Notification']['created']); ?>&nbsp;</td>
					 <td class="actions">
					
						<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $notifications['Notification']['id']), array('class'=>'btn btn-mini btn-danger'), __('Are you sure you want to delete # %s?', $notifications['Notification']['id'])); ?>
						
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