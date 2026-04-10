<div class="page-content">
		<div class="page-header">
			<h1>Material List <?php echo $this->Html->link(__('Add New'), ['prefix' => 'Admin', 'controller' => 'Materials', 'action' => 'add'], ['class' => 'btn btn-info btn-xs top-button']); ?></h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>				
					<th><?php echo $this->Paginator->sort('Material.id', __('Unique Code')); ?></th>					
					<th><?php echo $this->Paginator->sort('Material.material_type', __('Material type')); ?></th>
					<th><?php echo $this->Paginator->sort('Material.weight', __('Weight/dimensions')); ?></th>			
					<th><?php echo $this->Paginator->sort('Material.quantity', __('Quantity')); ?></th>									
					<th><?php echo $this->Paginator->sort('Material.description', __('Description')); ?></th>									
					<th><?php echo $this->Paginator->sort('Material.created', __('Created')); ?></th>
					<th></th>
				</tr>
				</thead>
				<tbody>
					<?php foreach (($materials ?? []) as $material): ?>
					<tr>								
					<td>#<?php echo h((string)($material['Material']['id'] ?? '')); ?>&nbsp;</td>
					<td><?php echo h((string)($material['Material']['material_type'] ?? '')); ?>&nbsp;</td>
					<td><?php echo h((string)($material['Material']['weight'] ?? '')); ?>&nbsp;</td>
					<td><?php echo h((string)($material['Material']['quantity'] ?? '')); ?>&nbsp;</td>					
					<td><?php echo h((string)($material['Material']['description'] ?? '')); ?>&nbsp;</td>					
                    <td><?php $c = $material['Material']['created'] ?? null; echo $c ? h(date('d-M-Y h:i a', strtotime((string)$c))) : ''; ?></td>
					<td>
					<?php echo $this->Html->link(__('Edit'), ['prefix' => 'Admin', 'controller' => 'Materials', 'action' => 'edit', $material['Material']['id'] ?? ''], ['class' => 'btn btn-mini btn-info']); ?>
					<a href="javascript:void(0)" onclick="qrcode('<?php echo h((string)($material['Material']['id'] ?? '')); ?>')" class="btn btn-mini btn-danger">QR Code</a>
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
<a href="javascript:void(0)"  data-toggle="modal" data-target="#mySharedModals" id="linkmySharedModald">&nbsp;</a>
<div id="mySharedModals" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content" style="text-align: center;">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Scan This QR Code</h4>
	  </div>
	  <div class="modal-body" style="text-align:center;" >
		<iframe frameborder="0" id="iframe-id" scrolling="yes" src="" style="width: 100%;height: 200px; margin-left: 33%;" ></iframe>	
	  </div>	 
	</div>
  </div>
</div>
<script>
	function qrcode(argu){
		var base = '<?php echo h(rtrim((string)SITEURL, '/')); ?>';
		var url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(base + '/materials/readqrcode/' + argu);
		$('#iframe-id').attr('src', url);
		$("#linkmySharedModald").trigger('click');
	}	
</script>
