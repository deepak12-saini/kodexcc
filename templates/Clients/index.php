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
			<?php echo $this->Html->link('Add New',array('controller' => 'clients','action' => 'add'),array('class'=>'btn btn-info btn-xs top-button')); ?>
			
			<?php echo $this->Form->create(null, [
				'url' => ['controller' => 'Clients', 'action' => 'index'],
				'type' => 'post',
				'style' => 'float:right;',
			]); ?>
				<select name="data[category_id]" style="width:160px;">
					<option value="">Select Category </option>
					<?php foreach($ClientTypeArr as $ClientTypeArrs){ ?>
						<option <?php if($category_id == $ClientTypeArrs['ClientType']['id']){ echo 'selected'; } ?> value="<?php echo $ClientTypeArrs['ClientType']['id']; ?>"><?php echo $ClientTypeArrs['ClientType']['name']; ?></option>
					<?php  } ?>
				</select>
				<input type="search" name="search" placeholder="Search  name or email or phone" value="<?php echo h($search); ?>" style="width:220px">
				<input type="submit"  class="btn btn-xs btn-primary" value="Search">
				
				<a class="btn btn-xs btn-warning" href="<?php echo SITEURL.'clients/index/clear'; ?>" >Clear All</a>
				<a class="btn btn-xs btn-info" href="#null" onclick="return dialclients()">Dial Number</a>
			<?php echo $this->Form->end(); ?>
				
			</h1>
			
			
		</div>
	
	<?php echo $this->Form->create(null, [
		'url' => ['prefix' => 'Admin', 'controller' => 'Clients', 'action' => 'assign'],
		'type' => 'post',
	]); ?>

	<div class="row">
	
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>				
				<th>Dial</th>
				<th></th>
				
				<th><?php echo $this->Paginator->sort('Client.category_id', __('Category Name')); ?></th>
				<th><?php echo $this->Paginator->sort('Client.fname', __('Name')); ?></th>
				<th><?php echo $this->Paginator->sort('Client.email', __('Email')); ?></th>
				<th>#<?php echo $this->Paginator->sort('Client.mobile', __('Mobile')); ?></th>
				<th>#<?php echo $this->Paginator->sort('Client.landline', __('Landline')); ?></th>
				<th><?php echo $this->Paginator->sort('Client.company', __('Company')); ?></th>
		
				<th><?php echo __('Assign Staff'); ?></th>				
				<th><?php echo __('Action'); ?></th>				
               
				</tr>
				</thead>
				<tbody>
					
						<?php $i=1; foreach ($clientArr as $clientArrs): ?>
						 
							<tr>							
							<td><input type="checkbox" value="<?php echo $clientArrs['Client']['id']; ?>" name="dialnumber" class="ckbCheckAll"></td>
							<td><a href="#null"  onclick="openpoup(<?php echo $clientArrs['Client']['id']; ?>)"><i class="fa fa-comment"></i></a></td>
							
							<!-- <td>#<?php echo h($clientArrs['Client']['id']); ?>&nbsp;</td> -->
							<td><input type="text" value="<?php echo h($clientArrs['ClientType']['name']); ?>" style="border: 0px;width: 350px !important;"></td>&nbsp;</td>
							<td>
							<input type="text" value="<?php echo h($clientArrs['Client']['fname'] ?? $clientArrs['Client']['name'] ?? ''); ?>" style="border: 0px;width: 150px !important;">
							</td>
							<td><input type="text" value="<?php echo h($clientArrs['Client']['email'] ?? ''); ?>" style="border: 0px;width: 350px !important;"></td>
							<td><input type="text" value="<?php echo h($clientArrs['Client']['mobile'] ?? $clientArrs['Client']['phone'] ?? ''); ?>" style="border: 0px;width: 130px !important;"></td>
							<td><input type="text" value="<?php echo h($clientArrs['Client']['landline'] ?? ''); ?>" style="border: 0px;width: 130px !important;"></td>
							<td><input type="text" value="<?php echo h($clientArrs['Client']['company']); ?>" style="border: 0px;width: 160px !important;"> </td>
							<td>
								<?php
								$cleintname = '';	
								if(!empty($clientArrs['StaffClient'])){
									foreach($clientArrs['StaffClient'] as $StaffClients){									
									$cleintname .= $StaffClients['NappUser']['name'].' '.$StaffClients['NappUser']['lname'].' , ';
								} } 
								echo '<span class="label label-info">'.$cleintname.'</span>';
								?>
							</td>
							<td>
							<?php 
								if($clientArrs['Client']['category_id'] == 13){
									echo $this->Html->link('Edit', ['controller' => 'Clients', 'action' => 'edit', $clientArrs['Client']['id']], ['class' => 'btn btn-primary btn-xs top-button']); 
								}else{
									echo 'N/A';
								}
							?>
							</td>
							</tr>					
						<?php $i++; endforeach; ?>
					
				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	<?php echo $this->Form->end(); ?>
	<div class="row">
		<div class="col-xs-6">
			<div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite"><?php echo $this->Paginator->counter(__('Showing {{current}} records out of {{count}} entries')); ?>	</div>
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
<div id="myModal_new" class="modal fade" role="dialog">
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

<script>

function openpoup(id){
	var url = '<?php echo SITEURL.'clients/comment/' ?>'+id;
	$('#abc_frame').attr('src', url);
	$("#myModalNew").trigger('click');	
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
var favorite = [];
$(document).ready(function () {
	$(".ckbCheckAll").click(function(){
		$.each($("input[name='dialnumber']:checked"), function(){            
			favorite.push($(this).val());
		});
	});
});
function onlyUnique(value, index, self) { 
    return self.indexOf(value) === index;
}
function dialclients(){
	var unique = favorite.filter( onlyUnique );
	if (unique.length !== 0) {
		$.ajax({
			url: "<?php  echo SITEURL ?>clients/addclientdialers",
			type: "POST",
			data: {client_list:unique},
			dataType: "html",
			success: function(response) {
				window.location.href="<?php echo SITEURL;?>dialers";
			}
		});
	}else{
		alert("Please select clients");
		return false;
	}
}
</script>