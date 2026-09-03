<?php
$this->set('pageTitle', 'My Dashboard');
$base = SITEURL . 'hrms/';
?>
<div class="hrms-cards">
	<div class="hrms-card">
		<div class="label">Today</div>
		<div class="value" style="font-size:1.1rem;">
			<?php if (!empty($todayAtt)): ?>
				<span class="badge badge-ok"><?php echo h(strtoupper($todayAtt->status)); ?></span>
				<div style="margin-top:.5rem;font-size:.85rem;color:#667085;">
					In: <?php echo $todayAtt->clock_in ? h($todayAtt->clock_in->format('H:i')) : '—'; ?>
					· Out: <?php echo $todayAtt->clock_out ? h($todayAtt->clock_out->format('H:i')) : '—'; ?>
				</div>
			<?php else: ?>
				<span class="badge badge-muted">Not marked</span>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $base; ?>my/attendance">Clock In / Out</a>
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $base; ?>my/leaves">Apply Leave</a>
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $base; ?>my/profile">My Profile</a>
</div>

<div class="hrms-panel">
	<h2>Leave Balance</h2>
	<table class="hrms-table">
		<thead><tr><th>Type</th><th>Allocated</th><th>Used</th><th>Remaining</th></tr></thead>
		<tbody>
		<?php foreach (($balances ?? []) as $b): ?>
			<tr>
				<td><?php echo h($b->hr_leave_type->name ?? ''); ?></td>
				<td><?php echo h($b->allocated); ?></td>
				<td><?php echo h($b->used); ?></td>
				<td><?php echo h((float)$b->allocated - (float)$b->used); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php if (!empty($teamPending) && count($teamPending)): ?>
<div class="hrms-panel">
	<h2>Team Leave Approvals</h2>
	<table class="hrms-table">
		<thead><tr><th>Employee</th><th>Type</th><th>Dates</th><th></th></tr></thead>
		<tbody>
		<?php foreach ($teamPending as $l): ?>
			<tr>
				<td><?php echo h($l->hr_employee->full_name ?? ''); ?></td>
				<td><?php echo h($l->hr_leave_type->name ?? ''); ?></td>
				<td><?php echo h($l->start_date) . ' → ' . h($l->end_date); ?></td>
				<td><a href="<?php echo $base; ?>leaves/view/<?php echo (int)$l->id; ?>">Review</a></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>

<div class="hrms-panel">
	<h2>Recent Leave Requests</h2>
	<table class="hrms-table">
		<thead><tr><th>Type</th><th>Dates</th><th>Status</th></tr></thead>
		<tbody>
		<?php foreach (($myLeaves ?? []) as $l): ?>
			<tr>
				<td><?php echo h($l->hr_leave_type->name ?? ''); ?></td>
				<td><?php echo h($l->start_date) . ' → ' . h($l->end_date); ?></td>
				<td><span class="badge badge-muted"><?php echo h($l->status); ?></span></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
