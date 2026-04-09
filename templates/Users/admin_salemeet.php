	
<style>
.btn{margin-bottom: 0px; }
.label {
    margin-bottom: 0px;
}
</style>
	
	<div class="page-content">

		<div class="page-header">
			<h1>Salemeet List
			<form action="" method="post" style="float:right;">
				<select name="saletreps" style="font-size: 15px;">
					<option value="">Show All</option>					
					<option <?php if($saletreps == 'Abhishek'){ echo 'selected'; } ?> value="Abhishek">Abhishek</option>
					<option <?php if($saletreps == 'Amandeep'){ echo 'selected'; } ?> value="Amandeep">Amandeep</option>
					<option <?php if($saletreps == 'Nakul'){ echo 'selected'; } ?> value="Nakul">Nakul</option>
					<option <?php if($saletreps == 'Mukesh'){ echo 'selected'; } ?> value="Mukesh">Mukesh</option>
					
				</select>
				<input type="text" name="startdate" value="<?php echo $startdate; ?>" class="datepicker" placeholder="Start Date" >
				<input type="text" name="enddate" value="<?php echo $enddate; ?>" class="datepicker1" placeholder="End Date" >
				<input type="submit" name="search" value="search" class="btn btn-info btn-xs" >	
				<a href="<?php echo SITEURL.'admin/users/salemeet/clearall' ?>" class="btn btn-warning btn-xs">Show All</a>
				<a href="<?php echo SITEURL.'admin/users/export' ?>" class="btn btn-danger btn-xs">Export</a>
			</form>		
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered" id="example" >
				<thead>
				<tr>
				
				<th>S.no</th>
				<th>Added By</th>
				<th>Name/Company name</th>
				<th>Email</th>				
				<th>Phone</th>			
				<th>Occupation</th>			
				<th>Existing</th>			
				<th>Interest</th>			
				<th>Location</th>			
				<th>Mail</th>			
				<th>DateTime</th>			
				<th>Action</th>			
				
				</tr>
				</thead>
				<tbody>
					<?php $i=1; foreach ($Salemeets as $NappUsers): ?>
					<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo ucfirst($NappUsers['Salemeet']['addedby']); ?></td>
					<td><?php echo ucfirst($NappUsers['Salemeet']['name']).'/'.$NappUsers['Salemeet']['company_name']; ?></td>					
					<td><?php echo h($NappUsers['Salemeet']['email']); ?>&nbsp;</td>
					<td><?php echo h($NappUsers['Salemeet']['phone']); ?>&nbsp;</td>
					<td><?php echo h($NappUsers['Salemeet']['occupation']); ?>&nbsp;</td>
					<td><?php echo h($NappUsers['Salemeet']['existing']); ?>&nbsp;</td>
					<td><?php echo h($NappUsers['Salemeet']['interest']); ?>&nbsp;</td>
					<td><?php echo h($NappUsers['Salemeet']['location']); ?>&nbsp;</td>				
					<td><?php if($NappUsers['Salemeet']['is_sent'] == 1){ echo 'Sent'; }else{ echo 'Not Sent'; } ?>&nbsp;</td>				
                    <td><?php echo date('d-M-Y',strtotime($NappUsers['Salemeet']['created'])); ?></td>
                    <td>
						<a href="<?php echo SITEURL.'admin/users/sendmailtocustomer/'.$NappUsers['Salemeet']['id'] ?>" onclick="return confirm('Are you sure to send mail?');" class="btn btn-mini btn-success">Send Mail</a>
                    </td>				
					</tr>
					<?php $i++; endforeach; ?>

				</tbody>
			</table>			
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