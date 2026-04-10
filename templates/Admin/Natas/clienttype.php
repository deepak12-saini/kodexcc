<div class="page-content">
		<div class="page-header">
			<h1>National Association of Testing Authorities  <small>>>Instrument List</small>
			<?php echo $this->Html->link('Add New', ['prefix' => 'Admin', 'controller' => 'Natas', 'action' => 'clienttypeAdd'], ['class' => 'btn btn-info btn-xs top-button']); ?>	
			

			 <a href="<?php echo SITEURL.'admin/natas'?>" class="btn btn-xs btn-inverse " >Back</a>
			</h1>
		</div>	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th>Id</th>
				<th>Unique Code</th>
				<th>Instrument Name</th>
				<th>Make Model Type</th>
				<!-- <th>Parent Name</th> -->
				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
						<?php foreach ($ClientTypeArr as $ClientTypeArrs): ?>
					<tr>
					<td>#<?php echo h($ClientTypeArrs['NataCategory']['id']); ?>&nbsp;</td>
					<td>#<?php echo h($ClientTypeArrs['NataCategory']['unique_id']); ?>&nbsp;</td>
					<td><?php echo $ClientTypeArrs['NataCategory']['name']; ?>&nbsp;</td>
					<td><?php echo $ClientTypeArrs['NataCategory']['make_model_type']; ?>&nbsp;</td>
					<!-- <td><?php if(isset($natacate[$ClientTypeArrs['NataCategory']['parent_id']])){ echo $natacate[$ClientTypeArrs['NataCategory']['parent_id']]; }else{  echo '--'; } ?>&nbsp;</td> -->
				
					<td>
						<?php echo $this->Html->link('Edit', ['prefix' => 'Admin', 'controller' => 'Natas', 'action' => 'clienttypeEdit', $ClientTypeArrs['NataCategory']['id']], ['class' => 'btn btn-primary btn-xs top-button']); ?>
					</td>
					
					</tr>
					<?php endforeach; ?>

				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	
</div>		
