	<div class="page-content">
		<div class="page-header">
			<h1>National Association of Testing Authorities
			<a href="<?php echo SITEURL.'admin/natas/clienttype'; ?>" class="btn btn-info btn-xs top-button"> Instrument List </a>	
			
			<?php echo $this->Html->link('Add New', ['prefix' => 'Admin', 'controller' => 'Natas', 'action' => 'addevent'], ['class' => 'btn btn-primary btn-xs top-button']); ?>	
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<?php
				$lastyear = $nextdate-1;
				$nextyear = $nextdate+1;
			?>
			<a href="<?php echo SITEURL.'admin/natas/index/'.$lastyear;?>" class="btn btn-mini btn-info "><< <?php echo $lastyear; ?> </a> 
			
			<a href="<?php echo SITEURL.'admin/natas/index/'.$nextyear;?>" class="btn btn-mini btn-inverse" style="float:right;">>> <?php echo $nextyear; ?> </a>
			<center>  <h3><b>NATA Report <?php echo $nextdate; ?> </b></h3></center>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>		
					<tr>
						<th></th>
						<th>January</th>
						<th>February</th>
						<th>March</th>
						<th>April</th>
						<th>May</th>
						<th>June</th>
						<th>July</th>
						<th>August</th>
						<th>September</th>
						<th>October</th>
						<th>November</th>
						<th>December</th>
					</tr>				
				</thead>
				<tbody>
					<?php 
					if(!empty($natacategory)){
					foreach($natacategory as $natacategories){	
					?>
					<tr>
						<td><?php echo $natacategories['NataCategory']['name']; ?></td>
						<?php 		
							$nataevent = array();
							foreach($natacategories['NataEvent'] as $nataevents){
								$month = explode('-',$nataevents['month']);								
								$sr = ltrim($month[0],0);
								$nataevent[$sr]['id'] = $nataevents['id'];
								$nataevent[$sr]['month'] = $month[0];
								$nataevent[$sr]['status'] = $nataevents['status'];
								$nataevent[$sr]['date'] = $nataevents['date'];
								$nataevent[$sr]['description'] = $nataevents['description'];
							}							
							for($i=1; $i <=12; $i++){							
						?>
							<td>
								<?php 
								if(isset($nataevent[$i]['description'])){ 								
									
									$curdate  = date('Y-m-d');
									$date = $nataevent[$i]['date'];
									
									if(($nataevent[$i]['status'] == 0) && (strtotime($date) < strtotime($curdate))){
										$class = 'label label-danger';
									}else if($nataevent[$i]['status'] == 0){
										$class = 'label label-info';
									}else if($nataevent[$i]['status'] == 1){
										$class = 'label label-success';
									}
								?>	
									<a href="#null" onclick="openpoup(<?php echo $nataevent[$i]['id']; ?>)"><span class="<?php echo $class; ?>"><?php echo $nataevent[$i]['description'] ?></span></a>
									<!-- Modal -->
									
								<?php } ?>
							</td>
						<?php  } ?>
					</tr>
					<?php } } ?>
				</tbody>
			</table>
			
		</div>
	</div>		
	
</div>	
<a href="#null"  data-toggle="modal" data-target="#myModal_new" id="myModalNew"><i></i></a>
<div id="myModal_new" class="modal fade" role="dialog">
  <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content" style="height:450px;">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Update Status</h4>
	  </div>
	  <div class="modal-body">
			<iframe src="" id="abc_frame" style="width:100%; border:none; height:370px;"></iframe>
	  </div>      
	</div>

  </div>
</div>	
<script>

function openpoup(id){
	var url = '<?php echo SITEURL.'admin/natas/update/' ?>'+id;
	$('#abc_frame').attr('src', url);
	$("#myModalNew").trigger('click');	
}

function closeModal(){
	window.location.href = '<?php echo SITEURL.'admin/natas' ?>';
}

</script>
