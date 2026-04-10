<style>
	.btn{ margin-bottom:2px;}
</style>
<div class="page-content">
	<div class="page-header">
		<h1>Open Feedback List</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('Feedback.addedby', 'addedby'); ?></th>
					<th><?php echo $this->Paginator->sort('Feedback.customer_name', 'customer_name'); ?></th>
					<th><?php echo $this->Paginator->sort('Feedback.company_name', 'company_name'); ?></th>
					<th><?php echo $this->Paginator->sort('Feedback.contact', 'Phone'); ?></th>
					<th><?php echo $this->Paginator->sort('Feedback.sample_given', 'sample_given'); ?></th>
					<th><?php echo $this->Paginator->sort('Feedback.feedback', 'feedback'); ?></th>
					<th><?php echo $this->Paginator->sort('Feedback.created', 'Last Update'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($FeedbackArr as $row): $f = $row['Feedback']; ?>
					<tr>
						<td><?php echo h($f['addedby'] ?? ''); ?></td>
						<td><?php echo h($f['customer_name'] ?? ''); ?></td>
						<td><?php echo h($f['company_name'] ?? ''); ?></td>
						<td><?php echo h($f['contact'] ?? ''); ?></td>
						<td><?php echo h($f['sample_given'] ?? ''); ?></td>
						<td><?php echo h($f['feedback'] ?? ''); ?></td>
						<td><?php echo !empty($f['created']) ? h(date('d-M-Y', strtotime((string)$f['created']))) : ''; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php echo $this->Paginator->counter(
				__('Showing {{current}} records out of {{count}} entries')
			); ?></div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
			<ul class="pagination">
				<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
				echo $this->Paginator->prev('< ' . __('previous'), ['class' => 'prev disabled']);?></li>
				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >', ['class' => 'next']);?></li>
			</ul>
			</div>
		</div>
	</div>
</div>

