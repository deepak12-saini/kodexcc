<?php
$this->set('pageTitle', 'Admin Dashboard');
?>
<div class="hrms-cards">
	<div class="hrms-card"><div class="label">Total Employees</div><div class="value"><?php echo (int)$total; ?></div></div>
	<div class="hrms-card"><div class="label">Present Today</div><div class="value"><?php echo (int)$present; ?></div></div>
	<div class="hrms-card"><div class="label">Absent Today</div><div class="value"><?php echo (int)$absent; ?></div></div>
	<div class="hrms-card"><div class="label">On Leave</div><div class="value"><?php echo (int)$onLeave; ?></div></div>
	<div class="hrms-card"><div class="label">Late Today</div><div class="value"><?php echo (int)$late; ?></div></div>
	<div class="hrms-card"><div class="label">Pending Leaves</div><div class="value"><?php echo (int)$pendingLeave; ?></div></div>
	<div class="hrms-card"><div class="label">Pending Approvals</div><div class="value"><?php echo (int)$pendingCorrections; ?></div></div>
</div>

<div class="hrms-panel">
	<h2>Attendance (last 7 days)</h2>
	<div class="hrms-chart-wrap">
		<canvas id="attChart"></canvas>
	</div>
</div>

<div class="hrms-cards">
	<div class="hrms-card"><div class="label">Month Present</div><div class="value"><?php echo (int)$monthlySummary['present']; ?></div></div>
	<div class="hrms-card"><div class="label">Month Absent</div><div class="value"><?php echo (int)$monthlySummary['absent']; ?></div></div>
	<div class="hrms-card"><div class="label">Month Half Day</div><div class="value"><?php echo (int)$monthlySummary['half_day']; ?></div></div>
	<div class="hrms-card"><div class="label">Month Late</div><div class="value"><?php echo (int)$monthlySummary['late']; ?></div></div>
</div>

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

<script>
const ctx = document.getElementById('attChart');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?php echo json_encode($chartLabels); ?>,
    datasets: [{
      label: 'Present',
      data: <?php echo json_encode($chartPresent); ?>,
      borderColor: '#1a4a73',
      backgroundColor: 'rgba(26,74,115,.15)',
      tension: .3,
      fill: true
    }]
  },
  options: { responsive: true, maintainAspectRatio: false }
});
</script>
