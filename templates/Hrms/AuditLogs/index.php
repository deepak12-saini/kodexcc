<div class="hrms-actions">
	<form method="get" class="hrms-form" style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:end;margin:0;max-width:none;">
		<div>
			<label>Action</label>
			<input type="text" name="action" value="<?php echo h($action); ?>" placeholder="e.g. login">
		</div>
		<div>
			<label>Entity</label>
			<input type="text" name="entity_type" value="<?php echo h($entity); ?>" placeholder="e.g. leave">
		</div>
		<div>
			<label>From</label>
			<input type="date" name="from" value="<?php echo h($from); ?>">
		</div>
		<div>
			<label>To</label>
			<input type="date" name="to" value="<?php echo h($to); ?>">
		</div>
		<button type="submit" class="hrms-btn hrms-btn-ghost">Filter</button>
	</form>
</div>

<?php echo $this->Form->create(null, [
	'url' => ['prefix' => 'Hrms', 'controller' => 'AuditLogs', 'action' => 'deleteSelected'],
	'id' => 'audit-delete-form',
]); ?>
<input type="hidden" name="filter_action" value="<?php echo h($action); ?>">
<input type="hidden" name="filter_entity" value="<?php echo h($entity); ?>">
<input type="hidden" name="filter_from" value="<?php echo h($from); ?>">
<input type="hidden" name="filter_to" value="<?php echo h($to); ?>">

<div class="hrms-actions" style="align-items:center;">
	<button type="submit" class="hrms-btn hrms-btn-danger" onclick="return confirm('Delete selected audit records?');">Delete Selected</button>
	<span style="font-size:.85rem;color:#5b6b7c;">Select rows below, or use Select all on this page.</span>
</div>

<div class="hrms-panel">
	<table class="hrms-table">
		<thead>
			<tr>
				<th style="width:2.5rem;">
					<input type="checkbox" id="audit-select-all" title="Select all on this page">
				</th>
				<th>When</th>
				<th>Actor</th>
				<th>Action</th>
				<th>Entity</th>
				<th>Summary</th>
				<th>IP</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $row): ?>
			<tr>
				<td>
					<input type="checkbox" class="audit-row-check" name="ids[]" value="<?php echo (int)$row->id; ?>">
				</td>
				<td><?php echo h(is_object($row->created) ? $row->created->format('Y-m-d H:i') : $row->created); ?></td>
				<td>
					<?php echo h($row->actor_user->username ?? '—'); ?>
					<span class="badge badge-muted"><?php echo h($row->actor_role ?: ''); ?></span>
				</td>
				<td><?php echo h($row->action); ?></td>
				<td><?php echo h($row->entity_type); ?><?php echo $row->entity_id ? ' #' . (int)$row->entity_id : ''; ?></td>
				<td><?php echo h($row->summary); ?></td>
				<td><?php echo h($row->ip); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (!count($items)): ?>
			<tr><td colspan="7">No audit entries.</td></tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo $this->element('hrms_pagination'); ?>
</div>
<?php echo $this->Form->end(); ?>

<div class="hrms-panel">
	<h2>Bulk delete</h2>
	<?php echo $this->Form->create(null, [
		'url' => ['prefix' => 'Hrms', 'controller' => 'AuditLogs', 'action' => 'deleteAll'],
		'class' => 'hrms-form grid-2',
	]); ?>
		<input type="hidden" name="filter_action" value="<?php echo h($action); ?>">
		<input type="hidden" name="filter_entity" value="<?php echo h($entity); ?>">
		<input type="hidden" name="filter_from" value="<?php echo h($from); ?>">
		<input type="hidden" name="filter_to" value="<?php echo h($to); ?>">
		<div>
			<label>Scope</label>
			<select name="scope" required>
				<option value="filtered">All matching current filters</option>
				<option value="all">Entire audit log (all records)</option>
			</select>
		</div>
		<div>
			<label>Type DELETE to confirm</label>
			<input type="text" name="confirm" placeholder="DELETE" required autocomplete="off">
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-danger" onclick="return confirm('This cannot be undone. Continue?');">Delete All in Scope</button>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

<script>
(function () {
	var all = document.getElementById('audit-select-all');
	if (!all) return;
	all.addEventListener('change', function () {
		document.querySelectorAll('.audit-row-check').forEach(function (cb) {
			cb.checked = all.checked;
		});
	});
})();
</script>
