<div class="hrms-actions">
	<a class="hrms-btn hrms-btn-ghost" href="<?php echo $itBase; ?>tickets">All tickets</a>
	<a class="hrms-btn hrms-btn-primary" href="<?php echo $itBase; ?>repairs/add?ticket_id=<?php echo (int)$ticket->id; ?><?php echo $ticket->asset_id ? '&asset_id=' . (int)$ticket->asset_id : ''; ?>">Create repair</a>
</div>
<div class="hrms-cards">
	<div class="hrms-card"><div class="label">Status</div><div class="value" style="font-size:1rem;"><?php echo $this->It->badge($ticket->status); ?></div></div>
	<div class="hrms-card"><div class="label">Priority</div><div class="value" style="font-size:1rem;"><?php echo $this->It->badge($ticket->priority); ?></div></div>
	<div class="hrms-card"><div class="label">Opened</div><div class="value" style="font-size:1rem;"><?php echo $this->It->date($ticket->created, true); ?></div></div>
	<div class="hrms-card"><div class="label">Resolved</div><div class="value" style="font-size:1rem;"><?php echo $this->It->date($ticket->resolved_at, true); ?></div></div>
</div>
<div class="hrms-panel">
	<h2><?php echo h($ticket->ticket_no); ?></h2>
	<table class="hrms-table">
		<tbody>
			<tr><th>Employee</th><td><?php echo h($ticket->hr_employee->full_name ?? '—'); ?></td></tr>
			<tr><th>Asset</th><td><?php if ($ticket->it_asset): ?><a href="<?php echo $itBase; ?>assets/view/<?php echo (int)$ticket->asset_id; ?>"><?php echo h($ticket->it_asset->asset_code); ?></a><?php else: ?>—<?php endif; ?></td></tr>
			<tr><th>Category</th><td><?php echo h($categories[$ticket->category] ?? $ticket->category); ?></td></tr>
			<tr><th>Assigned to</th><td><?php echo h($ticket->assigned_user->hr_employee->full_name ?? $ticket->assigned_user->username ?? '—'); ?></td></tr>
			<tr><th>Problem</th><td><?php echo nl2br(h($ticket->problem)); ?></td></tr>
			<tr><th>Resolution</th><td><?php echo nl2br(h($ticket->resolution ?: '—')); ?></td></tr>
			<tr><th>Closed</th><td><?php echo $this->It->date($ticket->closed_at, true); ?></td></tr>
		</tbody>
	</table>
</div>
<div class="hrms-panel">
	<h2>Update ticket</h2>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2']); ?>
		<div>
			<label>Status</label>
			<select name="status">
				<?php foreach ($ticketStatuses as $s): ?>
					<option value="<?php echo h($s); ?>" <?php echo $ticket->status === $s ? 'selected' : ''; ?>><?php echo h($this->It->label($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Priority</label>
			<select name="priority">
				<?php foreach ($ticketPriorities as $s): ?>
					<option value="<?php echo h($s); ?>" <?php echo $ticket->priority === $s ? 'selected' : ''; ?>><?php echo h(ucfirst($s)); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Assigned IT person</label>
			<select name="assigned_user_id">
				<option value="">Unassigned</option>
				<?php foreach ($itUsers as $id => $name): ?>
					<option value="<?php echo (int)$id; ?>" <?php echo (int)$ticket->assigned_user_id === (int)$id ? 'selected' : ''; ?>><?php echo h($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="full">
			<label>Resolution</label>
			<textarea name="resolution" rows="3"><?php echo h($ticket->resolution ?? ''); ?></textarea>
		</div>
		<div class="full">
			<label>Update note</label>
			<textarea name="note" rows="2" placeholder="What changed"></textarea>
		</div>
		<div class="full">
			<button class="hrms-btn hrms-btn-primary" type="submit">Save update</button>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
<div class="hrms-panel">
	<h2>Ticket history</h2>
	<table class="hrms-table">
		<thead><tr><th>When</th><th>Status</th><th>Note</th><th>By</th></tr></thead>
		<tbody>
		<?php foreach ($updates as $row): ?>
			<tr>
				<td><?php echo $this->It->date($row->created, true); ?></td>
				<td><?php echo $this->It->badge($row->status); ?></td>
				<td><?php echo h($row->note); ?></td>
				<td><?php echo h($row->hr_user->username ?? '—'); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
