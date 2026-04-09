<div class="page-content">
		<div class="page-header">
			<h1>DuroEzy Specification
			<?php echo $this->Html->link('Send Mail', ['controller' => 'Mailers', 'action' => 'send'], ['class' => 'btn btn-info btn-xs top-button']); ?>

			</h1>
		</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('Mailer.id', 'ID'); ?></th>

					<th><?php echo $this->Paginator->sort('Mailer.name', 'Name'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.email', 'Email'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.phone', 'Phone'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.company', 'Company'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.project', 'Project'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.type', 'Type'); ?></th>
					<th><?php echo $this->Paginator->sort('Mailer.created', 'Created'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($mailers as $mailer) :
						$m = $mailer['Mailer'] ?? [];
					?>
					<tr>
					<td>#<?php echo h($m['id'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['name'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['email'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['phone'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['company'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($m['project'] ?? ''); ?>&nbsp;</td>
					<td><?php
					if (!empty($m['type'])) {
						echo '<a href="' . h(SITEURL . 'DuroEzy/Method statement duromastic P15.pdf') . '" target="_blank" rel="noopener" >INSTALLATION OF DUROMASTIC P-15 WATERPROOFING MEMBRANE</a>';
					} else {
						echo '<a href="' . h(SITEURL . 'DuroEzy/4 seasons Saba tiling.pdf') . '" target="_blank" rel="noopener" >Waterproofing Specification</a>';
					}
					?>&nbsp;</td>
					<td><?php
						$cr = $m['created'] ?? null;
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
