<?php
/**
 * @var \App\View\AppView $this
 * @var array<int, array<string, array<string, mixed>>> $LabelStock
 */
?>
<div class="page-content">
	<div class="page-header">
		<h1>Label Stock List</h1>
	</div>
<style>
.danger { background: red; }
</style>
<div class="row">
	<div class="col-xs-12">
		<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="example" >
			<thead>
			<tr>
				<th></th>
				<th>Label</th>
				<th>Size</th>
				<th>Min Stock</th>
				<th>In Stock</th>
				<th>Updated By</th>
				<th>Last Update</th>
			</tr>
			</thead>
			<tbody>
				<?php
				$k = 1;
				foreach ($LabelStock as $LabelStocks): ?>
				<tr class="<?php if($LabelStocks['LabelStock']['minimum'] > $LabelStocks['LabelStock']['instock']){ echo 'danger';  } ?>">
					<td><?php echo $k; ?>&nbsp;</td>
					<td><?php echo h($LabelStocks['LabelStock']['label'] ?? ''); ?></td>
					<td><?php echo h($LabelStocks['LabelStock']['size'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($LabelStocks['LabelStock']['minimum'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($LabelStocks['LabelStock']['instock'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($LabelStocks['LabelStock']['name'] ?? ''); ?>&nbsp;</td>
					<td><?php echo !empty($LabelStocks['LabelStock']['created']) ? date('d-M-Y', strtotime((string)$LabelStocks['LabelStock']['created'])) : ''; ?></td>
				</tr>
				<?php $k++; endforeach; ?>
			</tbody>
		</table>
		</div>
	</div>
</div>
</div>
