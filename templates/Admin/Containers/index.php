<?php
use App\Utility\ContainerTransit;

?>
<div class="page-content">
	<div class="page-header">
		<h1>Containers <a class="btn btn-primary btn-sm pull-right" href="<?php echo SITEURL; ?>admin/containers/form">Add</a></h1>
	</div>
	<p style="color:#6b7280;margin-top:0;">The timer starts on the day the container leaves the factory. Mark it delivered to stop the timer and keep the number of days.</p>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th style="width:60px;">S.No</th>
				<th>Container</th>
				<th>Customer</th>
				<th>Destination</th>
				<th>Left factory</th>
				<th>Shipping line</th>
				<th>Timer</th>
				<th>Status</th>
				<th>Days taken</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php $sno = 1; ?>
		<?php foreach ($items as $item): ?>
			<?php
			$left = ContainerTransit::date($item->dispatched_on);
			$inTransit = (string)$item->status !== 'delivered';
			$timerDays = $inTransit ? ContainerTransit::days($left) : (int)$item->days_taken;
			?>
			<tr>
				<td><?php echo $sno++; ?></td>
				<td><?php echo h($item->container_no); ?></td>
				<td><?php echo h($item->customer ?? ''); ?></td>
				<td><?php echo h($item->destination); ?></td>
				<td style="white-space:nowrap;"><?php echo h($left !== '' ? date('d M Y', strtotime($left)) : ''); ?></td>
				<td><?php echo h($item->shipping_line ?? ''); ?></td>
				<td style="white-space:nowrap;"><?php echo h(ContainerTransit::label($timerDays)); ?></td>
				<td><?php echo $inTransit ? 'In transit' : 'Delivered'; ?></td>
				<td><?php echo $inTransit ? '' : h(ContainerTransit::label((int)$item->days_taken)); ?></td>
				<td style="white-space:nowrap;">
					<a class="btn btn-mini btn-info" href="<?php echo SITEURL; ?>admin/containers/form/<?php echo (int)$item->id; ?>">Edit</a>
					<?php if ($inTransit): ?>
						<?php echo $this->Form->create(null, ['url' => ['action' => 'deliver', $item->id], 'style' => 'display:inline-flex;gap:4px;align-items:center;margin-left:4px;']); ?>
							<input type="date" name="delivered_on" value="<?php echo h(date('Y-m-d')); ?>" style="height:26px;">
							<button class="btn btn-mini btn-success" type="submit">Mark delivered</button>
						<?php echo $this->Form->end(); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="10">No containers yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
</div>
