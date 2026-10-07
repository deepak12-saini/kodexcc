<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>tickets/add">New ticket</a>
	<form method="get" style="display:flex;gap:.5rem;flex:1;margin:0;flex-wrap:wrap;">
		<input type="search" name="q" value="<?php echo h($q ?? ''); ?>" placeholder="Ticket, problem, employee">
		<select name="status">
			<option value="">All statuses</option>
			<?php foreach ($ticketStatuses as $s): ?>
				<option value="<?php echo h($s); ?>" <?php echo ($status ?? '') === $s ? 'selected' : ''; ?>><?php echo h($this->It->label($s)); ?></option>
			<?php endforeach; ?>
		</select>
		<select name="priority">
			<option value="">All priorities</option>
			<?php foreach ($ticketPriorities as $s): ?>
				<option value="<?php echo h($s); ?>" <?php echo ($priority ?? '') === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
			<?php endforeach; ?>
		</select>
		<button class="hrms-btn hrms-btn-ghost" type="submit">Filter</button>
	</form>
</div>
<div class="hrms-panel">
	<table class="hrms-table">
		<thead><tr><th>Ticket</th><th>Employee</th><th>Asset</th><th>Category</th><th>Priority</th><th>Status</th><th>Opened</th></tr></thead>
		<tbody>
		<?php foreach ($items as $item): ?>
			<tr>
				<td><a href="<?php echo $itBase; ?>tickets/view/<?php echo (int)$item->id; ?>"><?php echo h($item->ticket_no); ?></a></td>
				<td><?php echo h($item->hr_employee->full_name ?? '—'); ?></td>
				<td><?php echo h($item->it_asset->asset_code ?? '—'); ?></td>
				<td><?php echo h($this->It->label($item->category)); ?></td>
				<td><?php echo $this->It->badge($item->priority); ?></td>
				<td><?php echo $this->It->badge($item->status); ?></td>
				<td><?php echo $this->It->date($item->created); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?><tr><td colspan="7">No tickets yet.</td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
