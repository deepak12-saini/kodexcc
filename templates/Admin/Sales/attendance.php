	<div class="page-content">
		<div class="page-header">
			<h1>
				Sale Person Attdendance 			
				
				<?php echo $this->Form->create(null, ['url' => ['action' => 'attendance'], 'style' => 'float:right;']); ?>
					<input type="text" name="date" value="<?php echo h($date); ?>" class="datepicker" placeholder="Start Date" >
					<input type="submit" name="search" value="search" class="btn btn-info btn-xs" >
				<?php echo $this->Form->end(); ?>
			</h1>
			
			
		</div>
	
	<div class="row">
		<span class="label label-danger" style="text-align:center;">Attdendance Day: <?php echo $date; ?></span>
		<br/><br/>  
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo __('#'); ?></th>
				<th><?php echo $this->Paginator->sort('NappUser.name', __('Name')); ?></th>
				<th><?php echo $this->Paginator->sort('NappUser.email', __('Email')); ?></th>
				<th><?php echo $this->Paginator->sort('Attendance.address', __('Address')); ?></th>
				<th><?php echo $this->Paginator->sort('Attendance.created', __('Login Time')); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php 
				
					$i=1;					
					foreach ($AttendanceArr as $AttendanceArrs): 
					?>
					<tr>
					<td>#<?php echo $i; ?>&nbsp;</td>
					<td><?php echo $AttendanceArrs['NappUser']['name'].' '.$AttendanceArrs['NappUser']['lname']; ?>&nbsp;</td>
					<td><?php echo h($AttendanceArrs['NappUser']['email']); ?>&nbsp;</td>
					<td><?php echo h($AttendanceArrs['Attendance']['address']); ?>&nbsp;</td>
					<td><?php echo date('d M Y h:i a',strtotime($AttendanceArrs['Attendance']['created'])); ?>&nbsp;</td>				
				
					<?php $i++;  endforeach; ?>

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
<script>
jQuery(function(){ 
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',			
	});$('.datepicker1').datepicker({
		format: 'yyyy-mm-dd',			
	});
});
</script>