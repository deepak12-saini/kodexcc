	<div class="page-content">
		<div class="page-header">
			<h1>
			Duro Sales Team
				<a href="<?php echo SITEURL.'admin/users/staff'?>" class="btn btn-mini btn-danger" style="float:right;">Give Sale Permssion To Staff</a>
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo $this->Paginator->sort('NappUser.id', __('ID')); ?></th>
				<th><?php echo $this->Paginator->sort('NappUser.name', __('Name')); ?></th>
				<th><?php echo $this->Paginator->sort('NappUser.email', __('Email')); ?></th>
				<th><?php echo $this->Paginator->sort('NappUser.mobile_number', __('Mobile')); ?></th>
				<th><?php echo __('Department'); ?></th>
				<th><?php echo $this->Paginator->sort('NappUser.is_approved', __('Status')); ?></th>
               

				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach ($staff as $staffs): ?>
					<tr>
					<td>#<?php echo h($staffs['NappUser']['id']); ?>&nbsp;</td>
					<td><?php echo $staffs['NappUser']['name'].' '.$staffs['NappUser']['lname']; ?>&nbsp;</td>
					<td><?php echo h($staffs['NappUser']['email']); ?>&nbsp;</td>
					<td><?php echo h($staffs['NappUser']['mobile_number']); ?>&nbsp;</td>
					<td><?php echo h($staffs['Department']['department_title']); ?>&nbsp;</td>					
					<td>
					<?php if($staffs['NappUser']['is_approved']==1){?>
						<span class="label label-success">Approved</span>
					<?php }else{ ?>
					<span class="label label-danger">Not Approve</span>
					<?php }?>&nbsp;
					</td>
                    <td>
						<a href="<?php echo SITEURL.'admin/sales/settarget/'.$staffs['NappUser']['id']; ?>" class="btn btn-mini btn-warning"  >Set Target</a>
						<a href="<?php echo SITEURL.'admin/sales/report/'.$staffs['NappUser']['id']; ?>" class="btn btn-mini btn-danger"  >Report</a>
						<a href="<?php echo SITEURL.'admin/sales/attendance_report/'.$staffs['NappUser']['id']; ?>" class="btn btn-mini btn-info"  >Attendence Report</a>
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
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php echo h($this->Paginator->counter(__('showing {{current}} records out of {{count}} entries'))); ?></div>
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