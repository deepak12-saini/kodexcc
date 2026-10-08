<div class="hrms-cards">
	<a class="hrms-card" href="<?php echo SITEURL; ?>hrms/employees"><div class="label">Total Employees</div><div class="value"><?php echo (int)$totalEmployees; ?></div></a>
	<a class="hrms-card" href="<?php echo $itBase; ?>assets"><div class="label">Total Assets</div><div class="value"><?php echo (int)$totalAssets; ?></div></a>
	<a class="hrms-card" href="<?php echo $itBase; ?>assets?status=assigned"><div class="label">Assigned Assets</div><div class="value"><?php echo (int)$assignedAssets; ?></div></a>
	<a class="hrms-card" href="<?php echo $itBase; ?>assets?status=available"><div class="label">Available Assets</div><div class="value"><?php echo (int)$availableAssets; ?></div></a>
	<a class="hrms-card" href="<?php echo $itBase; ?>assets?status=faulty"><div class="label">Faulty Assets</div><div class="value"><?php echo (int)$faultyAssets; ?></div></a>
	<a class="hrms-card" href="<?php echo $itBase; ?>assets?status=under_repair"><div class="label">Assets Under Repair</div><div class="value"><?php echo (int)$underRepair; ?></div></a>
	<a class="hrms-card" href="<?php echo $itBase; ?>tickets?status=active"><div class="label">Open IT Tickets</div><div class="value"><?php echo (int)$openTickets; ?></div></a>
	<a class="hrms-card" href="<?php echo $itBase; ?>purchases?status=pending"><div class="label">Pending Purchase Requests</div><div class="value"><?php echo (int)$pendingPurchases; ?></div></a>
	<a class="hrms-card" href="<?php echo $itBase; ?>reports/repair-costs"><div class="label">Total Repair Cost</div><div class="value" style="font-size:1.15rem;"><?php echo $this->It->money($repairCost); ?></div></a>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
	<div class="hrms-panel">
		<h2>Recent IT tickets</h2>
		<table class="hrms-table">
			<tbody>
			<?php foreach ($recentTickets as $row): ?>
				<tr>
					<td><a href="<?php echo $itBase; ?>tickets/view/<?php echo (int)$row->id; ?>"><?php echo h($row->ticket_no); ?></a></td>
					<td><?php echo h($row->hr_employee->full_name ?? '—'); ?></td>
					<td><?php echo $this->It->badge($row->status); ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if (!count($recentTickets)): ?><tr><td>No tickets yet.</td></tr><?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class="hrms-panel">
		<h2>Recent repairs</h2>
		<table class="hrms-table">
			<tbody>
			<?php foreach ($recentRepairs as $row): ?>
				<tr>
					<td><a href="<?php echo $itBase; ?>repairs/view/<?php echo (int)$row->id; ?>"><?php echo h($row->repair_no); ?></a></td>
					<td><?php echo h($row->it_asset->asset_code ?? '—'); ?></td>
					<td><?php echo $this->It->badge($row->status); ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if (!count($recentRepairs)): ?><tr><td>No repairs yet.</td></tr><?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class="hrms-panel">
		<h2>Recent purchases</h2>
		<table class="hrms-table">
			<tbody>
			<?php foreach ($recentPurchases as $row): ?>
				<tr>
					<td><a href="<?php echo $itBase; ?>purchases/view/<?php echo (int)$row->id; ?>"><?php echo h($row->request_no); ?></a></td>
					<td><?php echo h($row->item_name); ?></td>
					<td><?php echo $this->It->badge($row->approval_status); ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if (!count($recentPurchases)): ?><tr><td>No purchase requests yet.</td></tr><?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class="hrms-panel">
		<h2>Recent maintenance</h2>
		<table class="hrms-table">
			<tbody>
			<?php foreach ($recentMaintenance as $row): ?>
				<tr>
					<td><?php echo h($row->it_asset->asset_code ?? '—'); ?></td>
					<td><?php echo h($row->maintenance_type); ?></td>
					<td><?php echo $this->It->badge($row->status); ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if (!count($recentMaintenance)): ?><tr><td>No maintenance yet.</td></tr><?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
