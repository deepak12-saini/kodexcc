<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo SITEURL; ?>hrms/assets/add">Add Asset</a>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th>Code</th>
				<th>Name</th>
				<th>Serial</th>
				<th>Condition</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<?php
			$activeAssign = null;
			foreach ($item->hr_asset_assignments ?? [] as $asg) {
				if ($asg->status === 'assigned') {
					$activeAssign = $asg;
					break;
				}
			}
			?>
			<tr>
				<td><?php echo h($item->asset_code); ?></td>
				<td><?php echo h($item->name); ?></td>
				<td><?php echo h($item->serial_number); ?></td>
				<td><?php echo h($item->condition_label); ?></td>
				<td><span class="badge badge-muted"><?php echo h($item->status); ?></span></td>
				<td>
					<a href="<?php echo SITEURL; ?>hrms/assets/edit/<?php echo (int)$item->id; ?>">Edit</a>
					<?php if ($item->status === 'available'): ?>
						| <a href="<?php echo SITEURL; ?>hrms/assets/assign/<?php echo (int)$item->id; ?>">Assign</a>
					<?php elseif ($activeAssign): ?>
						| <a href="<?php echo SITEURL; ?>hrms/assets/return-asset/<?php echo (int)$activeAssign->id; ?>">Return</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="6">No assets yet.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
