<div class="page-content">
		<div class="page-header">
			<h1>Login History Logs</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>				
					<th><?php echo $this->Paginator->sort('LoginHistory.id', __('Id')); ?></th>					
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Email'); ?></th>			
					<th><?php echo $this->Paginator->sort('LoginHistory.role', __('Role')); ?></th>			
					<th><?php echo $this->Paginator->sort('LoginHistory.logintime', __('Login time')); ?></th>									
					<th><?php echo $this->Paginator->sort('LoginHistory.logouttime', __('Logout time')); ?></th>	
					
				</tr>
				</thead>
				<tbody>
					<?php 
				
					foreach (($LoginHistoryArr ?? []) as $LoginHistoryArrs):
						$lh = $LoginHistoryArrs['LoginHistory'] ?? [];
						if (($lh['role'] ?? '') === 'Admin') {
							$u = $LoginHistoryArrs['User'] ?? [];
							$name = trim(($u['name'] ?? '') !== '' ? (string)$u['name'] : (string)($u['username'] ?? ''));
							$email = (string)($u['email'] ?? '');
						} else {
							$nu = $LoginHistoryArrs['NappUser'] ?? [];
							$name = trim((string)(($nu['name'] ?? '') . ' ' . ($nu['lname'] ?? '')));
							$email = (string)($nu['email'] ?? '');
						}
					?>
					<tr>								
						<td>#<?php echo h((string)($lh['id'] ?? '')); ?>&nbsp;</td>
						<td><?php echo h($name); ?>&nbsp;</td>			
						<td><?php echo h($email); ?>&nbsp;</td>			
						<td><?php echo h((string)($lh['role'] ?? '')); ?>&nbsp;</td>
						<td><?php echo h((string)($lh['logintime'] ?? '')); ?>&nbsp;</td>					
						<td><?php 
						$lo = (string)($lh['logouttime'] ?? '');
						if ($lo !== '' && $lo !== '0000-00-00 00:00:00') {
							echo h($lo);
						} else {
							echo '-'; 
						} 
						?>&nbsp;</td>	
					
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