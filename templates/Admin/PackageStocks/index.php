<?php
/**
 * @var \App\View\AppView $this
 * @var array<int, array<string, array<string, mixed>>> $PackageStock
 */
?>
<div class="page-content">
	<div class="page-header">
		<h1>Package Stock List</h1>
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
				<th>Package item</th>
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
				foreach ($PackageStock as $row): ?>
				<tr class="<?php if($row['PackageStock']['minimum'] > $row['PackageStock']['instock']){ echo 'danger';  } ?>">
					<td><?php echo $k; ?>&nbsp;</td>
					<td><?php echo h($row['PackageStock']['label'] ?? ''); ?></td>
					<td><?php echo h($row['PackageStock']['size'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($row['PackageStock']['minimum'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($row['PackageStock']['instock'] ?? ''); ?>&nbsp;</td>
					<td><?php echo h($row['PackageStock']['name'] ?? ''); ?>&nbsp;</td>
					<td><?php echo !empty($row['PackageStock']['created']) ? date('d-M-Y', strtotime((string)$row['PackageStock']['created'])) : ''; ?></td>
				</tr>
				<?php $k++; endforeach; ?>
			</tbody>
		</table>
		</div>
	</div>
</div>
</div>
