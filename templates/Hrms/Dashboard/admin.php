<?php
$this->set('pageTitle', 'Admin Dashboard');
?>
<div class="hrms-cards">
	<div class="hrms-card"><div class="label">Total Employees</div><div class="value"><?php echo (int)$total; ?></div></div>
	<?php /* Leave cards commented until the client confirms the leave module.
	<div class="hrms-card"><div class="label">On Leave</div><div class="value"><?php echo (int)$onLeave; ?></div></div>
	<div class="hrms-card"><div class="label">Pending Leaves</div><div class="value"><?php echo (int)$pendingLeave; ?></div></div>
	*/ ?>
</div>
<?php /* Attendance cards and chart removed. */ ?>

<div class="hrms-panel">
	<h2>Department-wise headcount</h2>
	<table class="hrms-table">
		<thead><tr><th>Department</th><th>Employees</th></tr></thead>
		<tbody>
		<?php foreach ($deptCounts as $row): ?>
			<tr>
				<td><?php echo h($row['name'] ?? ''); ?></td>
				<td><?php echo (int)($row['cnt'] ?? 0); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
	<div class="hrms-panel">
		<h2>Upcoming Birthdays</h2>
		<table class="hrms-table">
			<tbody>
			<?php foreach ($birthdays as $e): ?>
				<tr>
					<td><?php echo h($e->full_name); ?></td>
					<td><?php echo $e->date_of_birth ? h($e->date_of_birth->format('d M')) : '—'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="hrms-panel">
		<h2>New Employees (30 days)</h2>
		<table class="hrms-table">
			<tbody>
			<?php foreach ($newJoiners as $e): ?>
				<tr>
					<td><?php echo h($e->full_name); ?></td>
					<td><?php echo $e->joining_date ? h($e->joining_date->format('d M Y')) : '—'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php /* Attendance chart removed. */ ?>
