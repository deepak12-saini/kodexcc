	<style>
		.btn{ margin-bottom:2px;} 
	</style>
	<div class="page-content">
		<div class="page-header">
			<h1>Open Feedback List </h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					
					<th><?php echo $this->Paginator->sort('addedby'); ?></th>					
					<th><?php echo $this->Paginator->sort('customer_name'); ?></th>					
					<th><?php echo $this->Paginator->sort('company_name'); ?></th>		
					<th><?php echo $this->Paginator->sort('contact','Phone'); ?></th>		
					<th><?php echo $this->Paginator->sort('sample_given'); ?></th>		
					<th><?php echo $this->Paginator->sort('feedback'); ?></th>	
					<th><?php echo $this->Paginator->sort('created','Last Update'); ?></th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					<?php
					$k = 1;
					foreach ($FeedbackArr as $FeedbackArrs): ?>
					<tr>
						
						<td><?php echo $FeedbackArrs['Feedback']['addedby']; ?></td>
						<td><?php echo $FeedbackArrs['Feedback']['customer_name']; ?>&nbsp;</td>	
						<td><?php echo $FeedbackArrs['Feedback']['company_name']; ?>&nbsp;</td>						
						<td><?php echo $FeedbackArrs['Feedback']['contact']; ?>&nbsp;</td>						
						<td><?php echo h($FeedbackArrs['Feedback']['sample_given']); ?>&nbsp;</td>
						<td><?php echo h($FeedbackArrs['Feedback']['feedback']); ?>&nbsp;</td>
									
						<td> <?php echo date('d-M-Y',strtotime($FeedbackArrs['Feedback']['created'])); ?></td>
						<td></td>
									
					</tr>
					<?php $k++; endforeach; ?>
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
