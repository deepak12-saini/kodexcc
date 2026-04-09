	<div class="page-content">
		<div class="page-header">
			<h1>DuroLab:   <a href="<?php echo SITEURL.'admin/tasks/uploaddoc/'.$taskArr['Task']['task_id'] ?>" class="btn btn-mini btn-info">Upload Document</a> <a style="float:right;" href="<?php echo SITEURL.'admin/tasks' ?>" class="btn btn-mini btn-invser">Back</a></h1>
		</div>
	
	<p><span class="label label-danger">Task:</span> <br/><b><?php echo $taskArr['Task']['title'];?></b></p>
	<p><span class="label label-danger">Description:</span> <?php echo $taskArr['Task']['description'];?></p>
	<div class="row">
	
		<div class="col-xs-12">
			 
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<!-- <th><?php echo 'S.no'; ?></th> -->
				<th><?php echo 'Uploaded By'; ?></th>
				<th><?php echo 'Document'; ?></th>
				<th><?php echo 'File Type'; ?></th>
				<th><?php echo 'Created'; ?></th>
				</tr>
				</thead>
				<tbody>
					<?php $i=1; foreach ($taskArr['TaskDocument'] as $taskArrs): ?>
					<tr>
					<!-- <td><?php echo $i; ?>&nbsp;</td> -->
					<td>
					
					<?php 
					if($taskArrs['admin_id'] == 0){ echo '<span class="label label-info">'.$taskArrs['NappUser']['name'].' '.$taskArrs['NappUser']['lname'].'</span>'; }else{ echo  '<span class="label label-success">'.$taskArrs['User']['name'].'</span>'; } 
					
					//echo $taskArrs['NappUser']['name'].' '.$taskArrs['NappUser']['lname']; 
					
					?>&nbsp;</td>
					<td><a href="<?php echo SITEURL.'admin/tasks/download/'.$taskArrs['id']; ?>" target="blank">Download Document</a>&nbsp;</td>
					<td><?php echo $taskArrs['ext']; ?>&nbsp;</td>
					
                    <td> <?php echo date('d M Y h:i a',strtotime($taskArrs['created'])); ?></td>
					
					</tr>
					<?php $i++;  endforeach; ?>

				</tbody>
			</table>			
			</div>
		</div>
	</div>		
	
</div>		