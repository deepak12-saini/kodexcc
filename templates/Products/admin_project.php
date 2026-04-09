	<div class="page-content">
		<div class="page-header">
			<h1>			
			Product Project Images	 <a href="<?php echo SITEURL; ?>admin/products/project-add/<?php echo h($product_id); ?>" class="btn btn-info btn-xs top-button">Add Images</a>	

			<a href="<?php echo SITEURL; ?>admin/products" class='btn btn-info btn-xs top-button' style="float:right;">Back</a>	
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="simple-table" >
				<thead>
				<tr>
				<th>Id</th>				
				<th>Image</th>			
                <th>Created</th>
				<th class="actions">Actions</th>
				</tr>
				</thead>
				<tbody>
						<?php if(!empty($imageArr)){ foreach ($imageArr as $imageArrs): ?>
					<tr>
					<td>#<?php echo h($imageArrs['Project']['id']); ?>&nbsp;</td>
					<td><?php if(!empty($imageArrs['Project']['images'])){?>
						<img style="height:50px; width:50px"src="<?php echo SITEURL.'productimg/'.$imageArrs['Project']['images']?>">
					<?php }else{?>
						No Image
					<?php }?>
					</td>
					
                    <td> <?php echo date('d-M-Y',strtotime($imageArrs['Project']['created'])); ?></td>
					 <td class="actions">
						<?php echo $this->Form->postLink(__('Delete'), ['action' => 'projectDelete', $product_id, $imageArrs['Project']['id']], ['class'=>'btn btn-mini btn-danger','style'=>'margin-top:2px;'], __('Are you sure you want to delete # %s?', $imageArrs['Project']['id'])); ?>
						
						</td>
					</tr>
					<?php endforeach; }else{ ?>
						<tr>
							 <td colspan="10" style="text-align:center; color:red; font-weight:bold;">No Product Found</td>
						</tr>
					<?php } ?>

				</tbody>
			</table>			
			</div>
		</div>
	</div>		

</div>		