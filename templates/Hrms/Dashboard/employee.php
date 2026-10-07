<?php
$this->set('pageTitle', 'My Dashboard');
$base = SITEURL . 'hrms/';
?>
<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $base; ?>my/leaves">Apply Leave</a>
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
