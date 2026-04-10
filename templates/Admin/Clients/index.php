	<style>
		select {
			padding: 0px 4px;
			height: 29px;
			width: 205px;
			font-size: 14px;
		}
		.label {
			margin-top: 5px;
		}
		.table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
			padding: 0px 5px 0px !important;
			line-height: 1px !important;
		}

	</style>
	<div class="page-content">
		<div class="page-header">
			<h1>Clients Management

			<?php echo $this->Html->link('Import csv', ['prefix' => 'Admin', 'controller' => 'Clients', 'action' => 'import'], ['class' => 'btn btn-danger btn-xs top-button']); ?>
			<?php echo $this->Html->link('Add New', ['prefix' => 'Admin', 'controller' => 'Clients', 'action' => 'add'], ['class' => 'btn btn-info btn-xs top-button']); ?>
			<button type="button" class="btn btn-warning btn-xs top-button" data-toggle="modal" data-target="#myModal">Client Category</button>


			<?php echo $this->Form->create(null, [
				'url' => ['prefix' => 'Admin', 'controller' => 'Clients', 'action' => 'index'],
				'type' => 'post',
				'style' => 'float:right;',
			]); ?>
				<select name="data[client_id]" style="width:160px;">
					<option value="">Select Sales Person </option>
					<?php foreach ($cuser as $cusers) { ?>
						<option <?php if ($client_id == $cusers['NappUser']['id']) {
							echo 'selected';
						} ?>  value="<?php echo $cusers['NappUser']['id']; ?>"><?php echo h($cusers['NappUser']['name'] . ' ' . ($cusers['NappUser']['lname'] ?? '')); ?></option>
					<?php } ?>
				</select>
				<select name="data[category_id]" style="width:160px;">
					<option value="">Select Category </option>
					<?php foreach ($ClientTypeArr as $ClientTypeArrs) { ?>
						<option <?php if ($category_id == $ClientTypeArrs['ClientType']['id']) {
							echo 'selected';
						} ?> value="<?php echo $ClientTypeArrs['ClientType']['id']; ?>"><?php echo h((string)$ClientTypeArrs['ClientType']['name']); ?></option>
					<?php } ?>
				</select>
				<input type="search" name="search" placeholder="Search  name or email or phone" value="<?php echo h($search); ?>" style="width:220px">
				<input type="submit"  class="btn btn-xs btn-primary" value="Search">

				<a class="btn btn-xs btn-warning" href="<?php echo SITEURL . 'admin/clients/index/clear'; ?>" >Clear All</a>
			<?php echo $this->Form->end(); ?>

			</h1>


		</div>

	<form action="<?php echo SITEURL . 'admin/clients/assign'?>" method="post">
	<div style="margin-bottom:10px;">
	<select name="data[client_id]" onchange="selected(this.value)">
		<option value="">Select Sales Person </option>
		<?php foreach ($cuser as $cusers) { ?>
			<option <?php if ($clientId == $cusers['NappUser']['id']) {
				echo 'selected';
			} ?>  value="<?php echo $cusers['NappUser']['id']; ?>"><?php echo h($cusers['NappUser']['name'] . ' ' . ($cusers['NappUser']['lname'] ?? '')); ?></option>
		<?php } ?>
	</select>
	<input type="submit"  class="btn btn-xs btn-info" value="Assign Client" style="margin-bottom:4px;">
	</div>
	<div class="row">

		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th><input type="checkbox" id="ckbCheckAll" ></th>
				<th></th>

				<th><?php echo $this->Paginator->sort('Client.category_id', __('Category Name')); ?></th>
				<th><?php echo $this->Paginator->sort('Client.fname', __('Name')); ?></th>
				<th><?php echo $this->Paginator->sort('Client.email', __('Email')); ?></th>
				<th>#<?php echo $this->Paginator->sort('Client.mobile', __('Mobile')); ?></th>
				<th>#<?php echo $this->Paginator->sort('Client.landline', __('Landline')); ?></th>
				<th><?php echo $this->Paginator->sort('Client.company', __('Company')); ?></th>
				<th><?php echo __('Assign Staff'); ?></th>
				<th><?php echo $this->Paginator->sort('Client.address1', __('Address')); ?></th>

                <th><?php echo $this->Paginator->sort('Client.status', __('Status')); ?></th>

				<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				</thead>
				<tbody>

						<?php
						$i = 1;
foreach ($clientArr as $clientArrs) :
	?>

							<tr>
							<td>
								<input type="checkbox" <?php if (in_array($clientArrs['Client']['id'], $StaffClientArr)) {
									echo 'checked';
								} ?> class="checkBoxClass"   value="<?php echo $clientArrs['Client']['id']; ?>" name="selectids[]" >
							</td>
							<td><a href="#null"  onclick="openpoup(<?php echo $clientArrs['Client']['id']; ?>)"><i class="fa fa-comment"></i></a></td>

							<td>
							<input type="text" value="<?php echo h($clientArrs['ClientType']['name'] ?? ''); ?>" style="border: 0px;width: 350px !important;"></td>
							<td>
							<input type="text" value="<?php echo h($clientArrs['Client']['fname'] ?? $clientArrs['Client']['name'] ?? ''); ?>" style="border: 0px;width: 150px !important;">
							</td>
							<td><input type="text" value="<?php echo h($clientArrs['Client']['email'] ?? ''); ?>" style="border: 0px;width: 350px !important;"></td>
							<td><input type="text" value="<?php echo h($clientArrs['Client']['mobile'] ?? $clientArrs['Client']['phone'] ?? ''); ?>" style="border: 0px;width: 130px !important;"></td>
							<td><input type="text" value="<?php echo h($clientArrs['Client']['landline'] ?? ''); ?>" style="border: 0px;width: 130px !important;"></td>
							<td><input type="text" value="<?php echo h($clientArrs['Client']['company'] ?? ''); ?>" style="border: 0px;width: 160px !important;"> </td>
							<td>
								<?php
								$cleintname = '';
	if (!empty($clientArrs['StaffClient'])) {
		foreach ($clientArrs['StaffClient'] as $StaffClients) {
			$cleintname .= ($StaffClients['NappUser']['name'] ?? '') . ' ' . ($StaffClients['NappUser']['lname'] ?? '') . ' , ';
		}
	}
	echo '<span class="label label-info">' . h($cleintname) . '</span>';
	?>
							</td>
							<td>
							<b><input type="text" value="<?php echo h($clientArrs['Client']['address1'] ?? ''); ?>" style="border: 0px;width: 400px !important;"></b>

							</td>

							<td><?php if (($clientArrs['Client']['status'] ?? null) == 1) {
								echo '<span class="label label-success">Active</span>';
							} else {
								echo '<span class="label label-danger">Deactive</span>';
							} ?>&nbsp;</td>
							<td>
								<?php echo $this->Html->link('Edit', ['prefix' => 'Admin', 'controller' => 'Clients', 'action' => 'edit', $clientArrs['Client']['id']], ['class' => 'label label-primary']); ?>
							</td>

							</tr>




						<?php $i++;
endforeach; ?>

				</tbody>
			</table>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php echo $this->Paginator->counter(
				__('Showing {{current}} records out of {{count}} entries')
			); ?>	</div>
		</div>
		<div class="col-xs-6">
			<div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
			<ul class="pagination">
				<li class="paginate_button previous disabled" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_previous"><?php
				echo $this->Paginator->prev('< ' . __('previous'), ['class' => 'prev disabled']); ?></li>

				<li class="paginate_button next" aria-controls="dynamic-table" tabindex="0" id="dynamic-table_next"><?php echo $this->Paginator->next(__('next') . ' >', ['class' => 'next disabled']); ?></li>

			</ul>
			</div>
		</div>
	</div>
</div>
<a href="#null"  data-toggle="modal" data-target="#myModal_new" id="myModalNew"><i></i></a>
<div id="myModal_new" class="modal fade" role="dialog" >
  <div class="modal-dialog">


	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Client Comments  </h4>
	  </div>
	  <div class="modal-body">
			<iframe src="" id="abc_frame" style="width:100%; border:none; height:400px;"></iframe>
	  </div>
	</div>

  </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="height:500px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Client Category </h4>
      </div>
      <div class="modal-body">
			<iframe src="<?php echo SITEURL . 'admin/clients/clienttype'; ?>" style="width:100%; border:none; height:400px;"></iframe>
      </div>
    </div>

  </div>
</div>
<script>

function openpoup(id){
	var url = '<?php echo SITEURL . 'admin/clients/comment/' ?>'+id;
	$('#abc_frame').attr('src', url);
	$("#myModalNew").trigger('click');
}

function selected(id){
	window.location.href = "<?php echo SITEURL . 'admin/clients/index/'?>"+id;
}

$(document).ready(function () {
	$('#ckbCheckAll').on('click',function(){
		if(this.checked){
			$('.checkBoxClass').each(function(){
				this.checked = true;
			});
		}else{
			 $('.checkBoxClass').each(function(){
				this.checked = false;
			});
		}
	});
});
</script>
