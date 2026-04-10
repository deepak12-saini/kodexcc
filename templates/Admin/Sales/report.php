	<div class="page-content">
		<div class="page-header">
			<h1>
			Duro Sales Report >> <small><?php echo $napuser['NappUser']['name'].' '.$napuser['NappUser']['lname']; ?>
			
			<?php echo $this->Form->create(null, ['url' => ['action' => 'report', $napuser['NappUser']['id'] ?? null], 'style' => 'float:right;']); ?>
				<select name="slaetype">
					<option value="">Show All</option>
					<?php foreach ($slatestype as $slatestypes) { ?>
						<option <?php if ((string)$slaetype === (string)$slatestypes['SaleQuestion']['id']) { echo 'selected'; } ?> value="<?php echo h($slatestypes['SaleQuestion']['id']); ?>"><?php echo h($slatestypes['SaleQuestion']['title']); ?></option>
					<?php } ?>
				</select>
				<input type="text" name="startdate" value="<?php echo h($startdate); ?>" class="datepicker" placeholder="Start Date" >
				<input type="text" name="enddate" value="<?php echo h($enddate); ?>" class="datepicker1" placeholder="End Date" >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs" >
				<a href="<?php echo SITEURL; ?>admin/sales/report/<?php echo h($napuser['NappUser']['id'] ?? ''); ?>/clearall" class="btn btn-warning btn-xs">Show All</a>
				<a href="<?php echo SITEURL; ?>admin/sales" class="btn btn-nverse btn-xs">Back</a>
			<?php echo $this->Form->end(); ?>
			
			</h1>
			<br><h1>
			<?php
			$totalsale = array();
			foreach ($SaleRepArr as $SaleRepArrs){
				if(!empty($SaleRepArrs['SaleQuestion']['id'])){
					$totalsale[$SaleRepArrs['SaleQuestion']['id']][] = $SaleRepArrs['SaleRep']['name'];
				}
			}
						
			$color = array('warning','success','info','danger','info','primary','inverse');
			$i=0; 
			foreach($slatestype as $slatestypes){ ?>
				<span class="label label-<?php echo $color[$i]; ?>">
				<?php echo $slatestypes['SaleQuestion']['title']; ?> (<?php  if(!empty($totalsale[$slatestypes['SaleQuestion']['id']])){ echo count($totalsale[$slatestypes['SaleQuestion']['id']]); }else{  echo '0'; }  ?>)</span>
			<?php  $i++; }  ?>
			
			<span class="label label-inverse">
				Dailer Called (<?php  echo count($totallogs);  ?>)</span>			
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo $this->Paginator->sort('SaleRep.id', __('#')); ?></th>
				<th><?php echo __('Contact Type'); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.name', __('Name')); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.email', __('Email')); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.phone', __('Phone')); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.company', __('Company')); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.spoken_to', __('Spoken to')); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.sample_given_request', __('Sample')); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.comment', __('Comment')); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.address', __('Address')); ?></th>
				<th><?php echo __('Call Duration'); ?></th>
				<th><?php echo __('Recording'); ?></th>
				<th><?php echo $this->Paginator->sort('SaleRep.created', __('Created')); ?></th>
               

				
				</tr>
				</thead>
				<tbody>
					<?php $i=1; foreach ($totallogs as $totallogss): ?>
					<tr>
					<td>#<?php echo $i; ?>&nbsp;</td>
					<td>
						<span class="label label-danger">Dailer Called</span>					
					</td>
					<td><?php echo $totallogss['Client']['fname'].' '.$totallogss['Client']['lname']; ?>&nbsp;</td>
					<td><?php echo $totallogss['Client']['email']; ?>&nbsp;</td>
					<td><?php echo $totallogss['Log']['phone_number']; ?>&nbsp;</td>
					<td><?php echo $totallogss['Client']['company']; ?>&nbsp;</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<?php if(!empty($totallogss['Log']['voice_url'])){ ?>
							<?php echo $totallogss['Log']['call_duration']; ?> Sec
						<?php  }else{	echo '--';	}	?>
					</td>
					<td style="text-align:center;">
						<?php if(!empty($totallogss['Log']['voice_url'])){ ?>							
							<a href="<?php echo $totallogss['Log']['voice_url']; ?>" target="_blank" ><i class="fa fa-play" style="font-size:20px;"></i></a>
						<?php  }else{	echo '--';	}	?>
					</td>
					<td><?php echo date('d M Y h:i a',strtotime($totallogss['Log']['created'])); ?></td>
					<?php $i++; endforeach; ?>
					
					<?php  $j = $i + 1; foreach ($SaleRepArr as $SaleRepArrs): ?>
					<tr>
					<td>#<?php echo $j; ?>&nbsp;</td>
					<td>
					<?php 
					if($SaleRepArrs['SaleQuestion']['id'] == 1){
						echo '<span class="label label-warning">'.$SaleRepArrs['SaleQuestion']['title'].'</span>';
					}else if($SaleRepArrs['SaleQuestion']['id'] == 2){
							echo '<span class="label label-success">'.$SaleRepArrs['SaleQuestion']['title'].'</span>';
					}else if($SaleRepArrs['SaleQuestion']['id'] == 3){
						echo '<span class="label label-info">'.$SaleRepArrs['SaleQuestion']['title'].'</span>';
					}else{
						echo '<span class="label label-danger">'.$SaleRepArrs['SaleQuestion']['title'].'</span>';
					}
					
					?>
					
					
					</td>
					<td><?php echo $SaleRepArrs['SaleRep']['name']; ?>&nbsp;</td>
					<td><?php echo h($SaleRepArrs['SaleRep']['email']); ?>&nbsp;</td>
					<td><?php echo h($SaleRepArrs['SaleRep']['phone']); ?>&nbsp;</td>
					<td><?php echo h($SaleRepArrs['SaleRep']['company']); ?>&nbsp;</td>					
					<td><?php echo h($SaleRepArrs['SaleRep']['spoken_to']); ?>&nbsp;</td>					
					<td><?php echo h($SaleRepArrs['SaleRep']['sample_given_request']); ?>&nbsp;</td>					
					<td><?php echo h($SaleRepArrs['SaleRep']['comment']); ?>&nbsp;</td>					
					<td><?php echo h($SaleRepArrs['SaleRep']['address']); ?>&nbsp;</td>	
					<td>N/A</td>
					<td>N/A</td>					
					<td><?php echo date('d M Y h:i a',strtotime($SaleRepArrs['SaleRep']['created'])); ?>&nbsp;</td>					
					
					</tr>
					<?php $j++; endforeach; ?>

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