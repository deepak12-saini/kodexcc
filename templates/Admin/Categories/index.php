<div class="page-content">
	<div class="page-header">
		<h1>Category Management
			<a href="<?php echo SITEURL; ?>admin/categories/add" class="btn btn-info btn-xs top-button">Add New</a>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('category_name'); ?></th>
				<th><?php echo $this->Paginator->sort('slug'); ?></th>
				<th><?php echo $this->Paginator->sort('description'); ?></th>
				<th><?php echo $this->Paginator->sort('image'); ?></th>
				 <th><?php echo $this->Paginator->sort('status', __('Active')); ?></th>
                  <th><?php echo $this->Paginator->sort('created'); ?></th>

				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
						<?php foreach ($categories as $category): ?>
					<tr>
					<td>#<?php echo h($category['Category']['id']); ?>&nbsp;</td>
					<td><?php echo h($category['Category']['category_name']); ?>&nbsp;</td>
					<td><?php echo h($category['Category']['slug']); ?>&nbsp;</td>
					<td><?php echo h($category['Category']['description'] ?? ''); ?>&nbsp;</td>
					<td><img src="<?php echo SITEURL . 'category/' . h($category['Category']['image']); ?>" width="150" alt=""/>&nbsp;</td>
					 <td>
						<?php if (($category['Category']['status'] ?? 0) == 1) { ?>
							<span class="label label-success">Active</span>
						<?php } else { ?>
						<span class="label label-danger">Inactive</span>
						<?php } ?>&nbsp;
						</td>
                    <td> <?php echo !empty($category['Category']['created']) ? date('d-M-Y', strtotime($category['Category']['created'])) : ''; ?></td>
					 <td class="actions">
						<a href="<?php echo SITEURL?>admin/categories/edit/<?php echo h($category['Category']['id']); ?>" class="btn btn-mini btn-info"><?php echo __('Edit'); ?></a>
						<?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $category['Category']['id']], ['class' => 'btn btn-mini btn-danger'], __('Are you sure you want to delete # %s?', $category['Category']['id'])); ?>

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
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php echo h($this->Paginator->counter('showing {{current}} records out of {{count}} entries')); ?></div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
			<ul class="pagination">
				<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
				echo $this->Paginator->prev('< ' . __('previous')); ?></li>

				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >'); ?></li>

			</ul>
			</div>
		</div>
	</div>
</div>
