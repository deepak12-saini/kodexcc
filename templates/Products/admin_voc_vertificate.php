	<style>
		.btn-primary{ margin-top:5px;}
	</style>
	<div class="page-content">
		<div class="page-header">
			<h1>
			
			Voc Certificate <?php echo $this->Html->link('Add New',array('controller' => 'products','action' => 'admin_vocadd'),array('class'=>'btn btn-info btn-xs top-button')); ?>
			
				
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('certificate_number'); ?></th>
				<th><?php echo $this->Paginator->sort('date'); ?></th>
				<th><?php echo $this->Paginator->sort('simple_description'); ?></th>
				<th><?php echo $this->Paginator->sort('date_tested'); ?></th> 
				<th><?php echo $this->Paginator->sort('test_method'); ?></th>
				<th><?php echo $this->Paginator->sort('sepecification'); ?></th>
				<th><?php echo $this->Paginator->sort('product_name'); ?></th>
				<th><?php echo $this->Paginator->sort('sepecification_2'); ?></th>
				<th><?php echo $this->Paginator->sort('size'); ?></th>
				<th><?php echo $this->Paginator->sort('description'); ?></th>
			    <th><?php echo $this->Paginator->sort('created'); ?></th>

				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php if(!empty($VocCertificate)){ foreach ($VocCertificate as $VocCertificates): ?>
					<tr>
					<td>#<?php echo h($VocCertificates['VocCertificate']['id']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['certificate_number']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['date']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['simple_description']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['date_tested']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['test_method']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['sepecification']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['product_name']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['sepecification_2']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['weight']); ?>&nbsp;</td>
					<td><?php echo h($VocCertificates['VocCertificate']['description']); ?>&nbsp;</td>
					
                    <td> <?php echo date('d-M-Y',strtotime($VocCertificates['VocCertificate']['created'])); ?></td>
					 <td class="actions">
						<?php echo $this->Html->link(__('Edit'), array('action' => 'vocedit', $VocCertificates['VocCertificate']['id']),array('class'=>'btn btn-mini btn-info')); ?>						
						<a target="_blank" href="<?php echo SITEURL.'pdf/FPDI/Voc Certificate.php?id='.base64_encode($VocCertificates['VocCertificate']['id']); ?>" class='btn btn-mini btn-primary'>Voc Certificate</a>						
						</td>
					</tr>
					<?php endforeach; }else{ ?>
						<tr>
							 <td colspan="13" style="text-align:center; color:red; font-weight:bold;">No Product Found</td>
						</tr>
					<?php } ?>

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
				echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));?></li>
				
				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));?></li>
				
			</ul>
			</div>
		</div>
	</div>	
</div>		