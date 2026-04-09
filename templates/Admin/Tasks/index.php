<style>
	.btn{ margin-bottom:5px;}
</style>

<div class="page-content">
	<div class="page-header">
		<h1>
			DuroLab (<?php echo h((string)$durolabType); ?>)
			<a href="<?php echo $this->Url->build(['prefix' => 'Admin', 'controller' => 'Tasks', 'action' => 'type']); ?>" class="btn btn-warning btn-xs top-button">Change Type</a>
			<form action="" method="post" style="float:right;">
				<input type="text" name="keyowrd" value="<?php echo h((string)$keyowrd); ?>" placeholder="Search By TaskID, title" style="width:174px !important">
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs" style="margin-top:10px">
				<a href="<?php echo $this->Url->build(['prefix' => 'Admin', 'controller' => 'Tasks', 'action' => 'index']); ?>" class="btn btn-warning btn-xs" style="margin-top:10px">Show All</a>
			</form>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="simple-table">
					<thead>
						<tr>
							<th><?php echo $this->Paginator->sort('SheetTaskCreate.id', 'ID'); ?></th>
							<th><?php echo $this->Paginator->sort('SheetTaskCreate.task_id', 'Task ID'); ?></th>
							<th><?php echo $this->Paginator->sort('SheetTaskCreate.title', 'Title'); ?></th>
							<th><?php echo $this->Paginator->sort('SheetTaskCreate.type', 'Type'); ?></th>
							<th><?php echo $this->Paginator->sort('SheetTaskCreate.status', 'Status'); ?></th>
							<th><?php echo $this->Paginator->sort('SheetTaskCreate.created', 'Created'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php if (empty($task)) : ?>
						<tr>
							<td colspan="6" class="text-center">No durolab tasks found for selected type.</td>
						</tr>
					<?php else : ?>
						<?php foreach ($task as $row) :
							$t = $row['Task'] ?? [];
						?>
						<tr>
							<td><?php echo h($t['id'] ?? ''); ?></td>
							<td><?php echo h($t['task_id'] ?? ''); ?></td>
							<td><?php echo h($t['title'] ?? ''); ?></td>
							<td><?php echo h($t['type'] ?? ''); ?></td>
							<td><?php echo h($t['status'] ?? ''); ?></td>
							<td><?php
								$created = $t['created'] ?? null;
								echo $created ? h(date('d-M-Y H:i', strtotime((string)$created))) : '';
							?></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite">
				<?php echo $this->Paginator->counter(__('Showing {{current}} records out of {{count}} entries')); ?>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
				<ul class="pagination">
					<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous">
						<?php echo $this->Paginator->prev('< ' . __('previous'), ['class' => 'prev disabled']); ?>
					</li>
					<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next">
						<?php echo $this->Paginator->next(__('next') . ' >', ['class' => 'next']); ?>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

