<?php
$ts = strtotime($month . '-01');
$daysInMonth = (int)date('t', $ts);
$startDow = (int)date('N', $ts);
$prev = date('Y-m', strtotime($month . '-01 -1 month'));
$next = date('Y-m', strtotime($month . '-01 +1 month'));

$byDay = [];
foreach ($holidays as $h) {
	$key = is_object($h->holiday_date) ? $h->holiday_date->format('Y-m-d') : (string)$h->holiday_date;
	$byDay[$key]['holidays'][] = $h;
}
foreach ($leaves as $item) {
	$s = strtotime(is_object($item->start_date) ? $item->start_date->format('Y-m-d') : (string)$item->start_date);
	$e = strtotime(is_object($item->end_date) ? $item->end_date->format('Y-m-d') : (string)$item->end_date);
	for ($d = $s; $d <= $e; $d += 86400) {
		$key = date('Y-m-d', $d);
		if (str_starts_with($key, $month)) {
			$byDay[$key]['leaves'][] = $item;
		}
	}
}
?>
<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/holidays/calendar?month=<?php echo $prev; ?>">← Prev</a>
	<strong><?php echo date('F Y', $ts); ?></strong>
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/holidays/calendar?month=<?php echo $next; ?>">Next →</a>
</div>
<div class="hrms-panel">
	<table class="hrms-table" style="table-layout:fixed;">
		<thead>
			<tr>
				<?php foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d): ?>
					<th><?php echo $d; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
		<?php
		$day = 1;
		$cells = $startDow - 1;
		echo '<tr>';
		for ($i = 0; $i < $cells; $i++) {
			echo '<td></td>';
		}
		while ($day <= $daysInMonth) {
			if (($cells % 7) === 0 && $cells > 0) {
				echo '</tr><tr>';
			}
			$key = $month . '-' . str_pad((string)$day, 2, '0', STR_PAD_LEFT);
			echo '<td style="vertical-align:top;min-height:4.5rem;">';
			echo '<strong>' . $day . '</strong>';
			foreach ($byDay[$key]['holidays'] ?? [] as $h) {
				echo '<div style="font-size:.72rem;margin-top:.25rem;background:#ecfdf3;color:#0f7a56;padding:.15rem .3rem;border-radius:4px;">'
					. h($h->name) . '</div>';
			}
			foreach ($byDay[$key]['leaves'] ?? [] as $leave) {
				echo '<div style="font-size:.72rem;margin-top:.25rem;background:#e8eef5;padding:.15rem .3rem;border-radius:4px;">'
					. h($leave->hr_employee->full_name ?? 'Leave')
					. '</div>';
			}
			echo '</td>';
			$day++;
			$cells++;
		}
		while (($cells % 7) !== 0) {
			echo '<td></td>';
			$cells++;
		}
		echo '</tr>';
		?>
		</tbody>
	</table>
</div>
