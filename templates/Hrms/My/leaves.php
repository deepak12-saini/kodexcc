<?php
/**
 * Format leave timing for display lists/views.
 * @param object $item Leave request entity
 */
$fmtTime = function ($t): string {
	if ($t === null || $t === '') {
		return '';
	}
	if (is_object($t) && method_exists($t, 'format')) {
		return $t->format('H:i');
	}
	return substr((string)$t, 0, 5);
};
$fmtLeaveWhen = function ($item) use ($fmtTime): string {
	$start = is_object($item->start_date ?? null) ? $item->start_date->format('Y-m-d') : (string)($item->start_date ?? '');
	$end = is_object($item->end_date ?? null) ? $item->end_date->format('Y-m-d') : (string)($item->end_date ?? '');
	$dur = (string)($item->duration_type ?? 'full_day');
	if ($dur === 'half_day') {
		$session = ($item->half_day_session ?? '') === 'second_half' ? '2nd half' : '1st half';
		$st = $fmtTime($item->start_time ?? null);
		$et = $fmtTime($item->end_time ?? null);
		$range = ($st && $et) ? " {$st}–{$et}" : '';
		return $start . ' · Half day (' . $session . ')' . $range;
	}
	if ($dur === 'hourly') {
		return $start . ' · ' . $fmtTime($item->start_time) . '–' . $fmtTime($item->end_time);
	}
	if ($start === $end) {
		return $start . ' · Full day';
	}
	return $start . ' → ' . $end . ' · Full day';
};
?>
<div class="hrms-panel">
	<h2>Leave Balances (<?php echo date('Y'); ?>)</h2>
	<table class="hrms-table">
		<thead><tr><th>Type</th><th>Allocated</th><th>Used</th><th>Remaining</th></tr></thead>
		<tbody>
		<?php foreach ($balances as $b): ?>
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

<div class="hrms-panel">
	<h2>Apply Leave</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2', 'id' => 'leave-apply-form']); ?>
		<div>
			<label>Leave Type</label>
			<select name="leave_type_id" required>
				<?php foreach ($types as $t): ?>
					<option value="<?php echo (int)$t->id; ?>"><?php echo h($t->name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Duration</label>
			<select name="duration_type" id="leave-duration" required>
				<option value="full_day">Full day(s)</option>
				<option value="half_day">Half day</option>
				<option value="hourly">Hours (same day)</option>
			</select>
		</div>
		<div>
			<label>Start Date</label>
			<?php
			$leaveYearNow = (int)date('Y');
			$leaveYears = range($leaveYearNow - 2, $leaveYearNow + 1);
			$leaveMonths = [
				1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
				7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec',
			];
			$todayD = (int)date('j');
			$todayM = (int)date('n');
			$todayY = (int)date('Y');
			?>
			<div class="hrms-date-parts" data-date-group="start">
				<select id="leave-start-d" aria-label="Start day" required>
					<?php for ($d = 1; $d <= 31; $d++): ?>
						<option value="<?php echo $d; ?>"<?php echo $d === $todayD ? ' selected' : ''; ?>><?php echo sprintf('%02d', $d); ?></option>
					<?php endfor; ?>
				</select>
				<select id="leave-start-m" aria-label="Start month" required>
					<?php foreach ($leaveMonths as $num => $label): ?>
						<option value="<?php echo $num; ?>"<?php echo $num === $todayM ? ' selected' : ''; ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				</select>
				<select id="leave-start-y" aria-label="Start year" required>
					<?php foreach ($leaveYears as $y): ?>
						<option value="<?php echo $y; ?>"<?php echo $y === $todayY ? ' selected' : ''; ?>><?php echo $y; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<input type="hidden" name="start_date" id="leave-start-date" value="<?php echo date('Y-m-d'); ?>" required>
			<small class="hrms-field-hint">Past dates are allowed for backdated leave.</small>
		</div>
		<div id="leave-end-wrap">
			<label>End Date</label>
			<div class="hrms-date-parts" data-date-group="end">
				<select id="leave-end-d" aria-label="End day" required>
					<?php for ($d = 1; $d <= 31; $d++): ?>
						<option value="<?php echo $d; ?>"<?php echo $d === $todayD ? ' selected' : ''; ?>><?php echo sprintf('%02d', $d); ?></option>
					<?php endfor; ?>
				</select>
				<select id="leave-end-m" aria-label="End month" required>
					<?php foreach ($leaveMonths as $num => $label): ?>
						<option value="<?php echo $num; ?>"<?php echo $num === $todayM ? ' selected' : ''; ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				</select>
				<select id="leave-end-y" aria-label="End year" required>
					<?php foreach ($leaveYears as $y): ?>
						<option value="<?php echo $y; ?>"<?php echo $y === $todayY ? ' selected' : ''; ?>><?php echo $y; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<input type="hidden" name="end_date" id="leave-end-date" value="<?php echo date('Y-m-d'); ?>" required>
		</div>
		<div id="leave-half-wrap" style="display:none;">
			<label>Half Day Session</label>
			<select name="half_day_session" id="leave-half-session">
				<option value="first_half">1st half (09:00–13:00)</option>
				<option value="second_half">2nd half (14:00–18:00)</option>
			</select>
		</div>
		<div id="leave-start-time-wrap" style="display:none;">
			<label>Start Time</label>
			<input type="time" name="start_time" id="leave-start-time" value="09:00">
		</div>
		<div id="leave-end-time-wrap" style="display:none;">
			<label>End Time</label>
			<input type="time" name="end_time" id="leave-end-time" value="13:00">
		</div>
		<div class="full">
			<label>Reason</label>
			<textarea name="reason" rows="3" required placeholder="e.g. Fever / doctor visit — include timing if needed"></textarea>
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Submit Request</button>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

<div class="hrms-panel">
	<h2>My Requests</h2>
	<table class="hrms-table">
		<thead><tr><th>Type</th><th>When</th><th>Days</th><th>Status</th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><?php echo h($item->hr_leave_type->name ?? ''); ?></td>
				<td><?php echo h($fmtLeaveWhen($item)); ?></td>
				<td><?php echo h($item->days); ?></td>
				<td><span class="badge badge-muted"><?php echo h($item->status); ?></span></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
<script>
(function () {
	var dur = document.getElementById('leave-duration');
	var endWrap = document.getElementById('leave-end-wrap');
	var halfWrap = document.getElementById('leave-half-wrap');
	var stWrap = document.getElementById('leave-start-time-wrap');
	var etWrap = document.getElementById('leave-end-time-wrap');
	var endDate = document.getElementById('leave-end-date');
	var startDate = document.getElementById('leave-start-date');
	var startParts = {
		d: document.getElementById('leave-start-d'),
		m: document.getElementById('leave-start-m'),
		y: document.getElementById('leave-start-y')
	};
	var endParts = {
		d: document.getElementById('leave-end-d'),
		m: document.getElementById('leave-end-m'),
		y: document.getElementById('leave-end-y')
	};

	function daysInMonth(year, month) {
		return new Date(year, month, 0).getDate();
	}

	function clampDay(parts) {
		var y = parseInt(parts.y.value, 10);
		var m = parseInt(parts.m.value, 10);
		var max = daysInMonth(y, m);
		var opts = parts.d.options;
		for (var i = 0; i < opts.length; i++) {
			var dayNum = parseInt(opts[i].value, 10);
			opts[i].disabled = dayNum > max;
			opts[i].hidden = dayNum > max;
		}
		if (parseInt(parts.d.value, 10) > max) {
			parts.d.value = String(max);
		}
	}

	function partsToIso(parts) {
		clampDay(parts);
		var y = parts.y.value;
		var m = ('0' + parts.m.value).slice(-2);
		var d = ('0' + parts.d.value).slice(-2);
		return y + '-' + m + '-' + d;
	}

	function setPartsFromIso(parts, iso) {
		if (!iso || iso.length < 10) return;
		var bits = iso.split('-');
		parts.y.value = String(parseInt(bits[0], 10));
		parts.m.value = String(parseInt(bits[1], 10));
		parts.d.value = String(parseInt(bits[2], 10));
		clampDay(parts);
	}

	function syncHiddenFromParts() {
		startDate.value = partsToIso(startParts);
		if (dur.value !== 'full_day') {
			setPartsFromIso(endParts, startDate.value);
		}
		endDate.value = partsToIso(endParts);
		if (endDate.value < startDate.value) {
			setPartsFromIso(endParts, startDate.value);
			endDate.value = startDate.value;
		}
	}

	function syncDurationUi() {
		var v = dur.value;
		endWrap.style.display = v === 'full_day' ? '' : 'none';
		halfWrap.style.display = v === 'half_day' ? '' : 'none';
		stWrap.style.display = v === 'hourly' ? '' : 'none';
		etWrap.style.display = v === 'hourly' ? '' : 'none';
		endDate.required = v === 'full_day';
		endParts.d.required = v === 'full_day';
		endParts.m.required = v === 'full_day';
		endParts.y.required = v === 'full_day';
		syncHiddenFromParts();
	}

	['d', 'm', 'y'].forEach(function (key) {
		startParts[key].addEventListener('change', syncHiddenFromParts);
		endParts[key].addEventListener('change', syncHiddenFromParts);
	});
	dur.addEventListener('change', syncDurationUi);
	syncDurationUi();
})();
</script>
