<div class="page-content">
		<div class="page-header">
			<h1>EOI Email Logs
			<?php echo $this->Html->link('Send Mail', ['controller' => 'Mailers', 'action' => 'eoisend'], ['class' => 'btn btn-info btn-xs top-button']); ?>

			</h1>
		</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('EoiMailer.id', 'ID'); ?></th>

					<th><?php echo $this->Paginator->sort('EoiMailer.inserindivdualname', 'Individual Name'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.insertcompanyname', 'Company Name'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.projectname', 'Project'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.date', 'Date'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.insertname', 'Insert Name'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.mobile', 'Mobile No.'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.landlineno', 'Landline No.'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.sender_email', 'Sender Email'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.subject', 'Subject'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.client_requested', 'Client requested'); ?></th>
					<th><?php echo $this->Paginator->sort('EoiMailer.created', 'Created'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($mailers as $mailer) :
						$e = $mailer['EoiMailer'] ?? [];
					?>
					<tr>
					<td>#<?php echo h($e['id'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['inserindivdualname'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['insertcompanyname'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['projectname'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['date'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['insertname'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['mobile'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['landlineno'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['sender_email'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['subject'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($e['client_requested'] ?? ''); ?>&nbsp;</td>

					<td><?php
						$cr = $e['created'] ?? null;
						echo $cr ? h(date('d-M-Y h:i a', strtotime((string)$cr))) : '';
					?></td>

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
				echo $this->Paginator->prev('< ' . __('previous'), ['class' => 'prev disabled']); ?></li>

				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >', ['class' => 'next']); ?></li>

			</ul>
			</div>
		</div>
	</div>
</div>
