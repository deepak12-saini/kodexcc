<div class="page-content">
	<div class="page-header">
		<h1>Production Report</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="example" >
				<thead>
				<tr>
					<th></th>
					<th>ReportId</th>
					<th>Date</th>
					<th>Added By</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					<?php
					$k = 1000;
					foreach ($ProductionReport as $ProductionReports): ?>
					<tr>
						<td><?php echo $k; ?>&nbsp;</td>
						<td><a data-toggle="modal" data-target="#myModal_<?php echo $k; ?>" href="#null"><?php echo h($ProductionReports['ProductionReport']['uniqueid']); ?></a>&nbsp;</td>
						<td><?php echo date('d-M-Y', strtotime($ProductionReports['ProductionReport']['date'])); ?>&nbsp;</td>
						<td><?php echo h(($ProductionReports['NappUser']['name'] ?? '') . ' ' . ($ProductionReports['NappUser']['lname'] ?? '')); ?></td>

						<td>
						<?php if (!empty($adminView)): ?>
							<a href="<?php echo SITEURL . 'production-reports/edit/' . $ProductionReports['ProductionReport']['id']; ?>" class="btn btn-mini btn-info">Edit</a>
						<?php elseif (isset($ProductionReports['ProductionReport']['user_id'], $user_id) && $ProductionReports['ProductionReport']['user_id'] == $user_id): ?>
							<a href="<?php echo SITEURL . 'production-reports/edit/' . $ProductionReports['ProductionReport']['id']; ?>" class="btn btn-mini btn-info">Edit</a>
						<?php endif; ?>
						</td>
					</tr>
					<?php $k++; endforeach; ?>

				</tbody>
			</table>
			</div>
		</div>
	</div>

</div>

<?php
	$j = 1000;
	foreach ($ProductionReport as $DuroOrderArrs) :
	?>
	<div id="myModal_<?php echo $j; ?>" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">ReportId: <?php echo h($DuroOrderArrs['ProductionReport']['uniqueid']); ?></h4>
		  </div>
		  <div class="modal-body">
			<div class="row">
			<div class="col-xs-12">
				<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover"  >
					<tbody>
					<tr><th>ReportId: </th><td><span class="label label-inverse"><?php echo h($DuroOrderArrs['ProductionReport']['uniqueid']); ?></span></td></tr>

					<tr><th>Added By: </th><td><?php echo h(($DuroOrderArrs['NappUser']['name'] ?? '') . ' ' . ($DuroOrderArrs['NappUser']['lname'] ?? '')); ?></td></tr>

					<tr><th>Date: </th><td><?php echo date('d-M-Y', strtotime($DuroOrderArrs['ProductionReport']['date'])); ?></td></tr>
					</tbody>
				</table>
				<h4>Manufacturing Product</h4>
				<p><?php echo htmlspecialchars_decode($DuroOrderArrs['ProductionReport']['manufacturing_product'] ?? ''); ?></p>

				<h4>Manufacturing Packaged</h4>
				<p><?php echo htmlspecialchars_decode($DuroOrderArrs['ProductionReport']['manufacturing_packaged'] ?? ''); ?></p>

				<h4>Manufacturing labeled</h4>
				<p><?php echo htmlspecialchars_decode($DuroOrderArrs['ProductionReport']['manufacturing_labelled'] ?? ''); ?></p>

				<h4>WAREHOUSE RECEIVED GOODS -- SUPPLIER PODS</h4>
				<p><?php echo htmlspecialchars_decode($DuroOrderArrs['ProductionReport']['warehouse_supplier_pod'] ?? ''); ?></p>

				<h4>DESPATCHED GOODS --  CUSTOMER PODS</h4>
				<p><?php echo htmlspecialchars_decode($DuroOrderArrs['ProductionReport']['warehouse_customer_pod'] ?? ''); ?></p>

				</div>
			</div>
		</div>
		  </div>

		</div>

	  </div>
	</div>
	<?php $j++; endforeach; ?>
