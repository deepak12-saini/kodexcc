<style>
	.btn{ margin-bottom:5px; }
</style>
<div class="page-content">
	<div class="page-header">
		<h1>DuroEzy Specification
			<?php echo $this->Html->link('Send Mail', ['prefix' => 'Admin', 'controller' => 'Mailers', 'action' => 'send'], ['class' => 'btn btn-info btn-xs top-button']); ?>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('Mailer.tracknumber', 'Track'); ?></th>
					<th><?php echo $this->Paginator->sort('User.name', 'Send Name'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.name', 'Name/Attn'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.email', 'Email'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.company', 'Company'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.address', 'Address'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.specification', 'Specification'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.date', 'Date'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.type', 'Type'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.subject', 'Subject'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.created', 'Created'); ?></th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($mailers as $mailer) :
						$m = $mailer['Mailer'] ?? [];
						$u = $mailer['User'] ?? [];
						$pdffilename = ($m['id'] ?? '') . '-' . ($m['tracknumber'] ?? '') . '.pdf';
					?>
					<tr>
					<td>#<?php echo h($m['tracknumber'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h(($u['name'] ?? '') . '(' . ($u['username'] ?? '') . ')'); ?>&nbsp;</td>
					<td><?php echo h($m['name'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['email'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['company'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['address'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['specification'] ?? ''); ?>&nbsp;</td>
					<td><?php
						$d = $m['date'] ?? null;
						echo $d ? h(date('d/m/Y', strtotime((string)$d))) : '';
					?>&nbsp;</td>
					<td><?php if (($m['type'] ?? '') == '3') {
						echo 'External Liquid WPM';
					} ?></td>
					<td><?php echo h($m['subject'] ?? ''); ?></td>
					<td><?php
						$cr = $m['created'] ?? null;
						echo $cr ? h(date('d-M-Y h:i a', strtotime((string)$cr))) : '';
					?></td>
					<td>
						<a class="btn btn-mini btn-info" href="<?php echo h(SITEURL . 'dompdf/' . $pdffilename); ?>" target="_blank" rel="noopener">Download</a>
						<a class="btn btn-mini btn-danger" onclick="return confirm('Are you sure send email?');" href="<?php echo h(SITEURL . 'admin/mailers/sendemail/' . ($m['id'] ?? '')); ?>">Send Mail</a>
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
