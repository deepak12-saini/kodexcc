<?php
$balance = function (array $row): float {
	return round((float)$row['debit'] - (float)$row['credit'], 2);
};
$income = 0.0;
$expense = 0.0;
$asset = 0.0;
$liability = 0.0;
$equity = 0.0;
foreach ($rows as $row) {
	$net = $balance($row);
	if ($row['account_type'] === 'income') {
		$income += -$net;
	} elseif ($row['account_type'] === 'expense') {
		$expense += $net;
	} elseif ($row['account_type'] === 'asset') {
		$asset += $net;
	} elseif ($row['account_type'] === 'liability') {
		$liability += -$net;
	} elseif ($row['account_type'] === 'equity') {
		$equity += -$net;
	}
}
$profit = round($income - $expense, 2);
?>
<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet"><div class="kx-card">
		<h1><?php echo h($title); ?></h1>
		<form method="get" class="form-inline" style="margin-bottom:12px;">
			<input type="date" name="from" value="<?php echo h($from); ?>" class="form-control">
			<input type="date" name="to" value="<?php echo h($to); ?>" class="form-control">
			<button class="btn btn-sm" type="submit">Filter</button>
		</form>
		<table class="kx-table">
			<thead><tr><th>Account</th><th>Debit</th><th>Credit</th></tr></thead>
			<tbody>
				<?php foreach ($rows as $row): ?>
					<?php
					if ($kind === 'pl' && !in_array($row['account_type'], ['income', 'expense'], true)) {
						continue;
					}
					if ($kind === 'bs' && !in_array($row['account_type'], ['asset', 'liability', 'equity'], true)) {
						continue;
					}
					?>
					<tr>
						<td><?php echo h($row['code'] . ' ' . $row['name']); ?></td>
						<td><?php echo h(number_format((float)$row['debit'], 2)); ?></td>
						<td><?php echo h(number_format((float)$row['credit'], 2)); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php if ($kind === 'trial'): ?>
			<p class="kx-lead">Debit <?php echo h(number_format(array_sum(array_column($rows, 'debit')), 2)); ?> · Credit <?php echo h(number_format(array_sum(array_column($rows, 'credit')), 2)); ?></p>
		<?php elseif ($kind === 'pl'): ?>
			<p class="kx-lead">Income <?php echo h(number_format($income, 2)); ?> · Expense <?php echo h(number_format($expense, 2)); ?> · Profit <?php echo h(number_format($profit, 2)); ?></p>
		<?php else: ?>
			<p class="kx-lead">Assets <?php echo h(number_format($asset, 2)); ?> · Liabilities <?php echo h(number_format($liability, 2)); ?> · Equity <?php echo h(number_format($equity + $profit, 2)); ?></p>
		<?php endif; ?>
		<div class="kx-actions"><a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL; ?>admin/erp/financials">Back</a></div>
	</div></div>
</div>
