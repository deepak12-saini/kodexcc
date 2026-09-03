<div class="hrms-panel">
	<h2>Submit Request</h2>
	<?php
	$typeNeeds = [];
	foreach ($types as $t) {
		$typeNeeds[(int)$t->id] = (int)$t->needs_asset;
	}
	?>
	<?php echo $this->Form->create(null, ['class' => 'hrms-form grid-2', 'id' => 'helpdesk-form']); ?>
		<div>
			<label>Request Type</label>
			<select name="request_type_id" id="req-type" required>
				<?php foreach ($types as $t): ?>
					<option value="<?php echo (int)$t->id; ?>" data-needs-asset="<?php echo (int)$t->needs_asset; ?>"><?php echo h($t->name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div>
			<label>Priority</label>
			<select name="priority">
				<option value="normal">Normal</option>
				<option value="high">High</option>
				<option value="low">Low</option>
			</select>
		</div>
		<div class="full">
			<label>Title</label>
			<input type="text" name="title" required placeholder="Short summary">
		</div>
		<div id="asset-cat-wrap" class="full" style="display:none;">
			<label>Asset Category</label>
			<select name="asset_category">
				<option value="Laptop">Laptop</option>
				<option value="Mouse">Mouse</option>
				<option value="Keyboard">Keyboard</option>
				<option value="Monitor">Monitor</option>
				<option value="Phone">Phone</option>
				<option value="Other">Other</option>
			</select>
		</div>
		<div class="full">
			<label>Description</label>
			<textarea name="description" rows="4" required></textarea>
		</div>
		<div class="full">
			<button type="submit" class="hrms-btn hrms-btn-primary">Submit</button>
			<a class="hrms-btn hrms-btn-ghost" href="<?php echo SITEURL; ?>hrms/my/requests">Cancel</a>
		</div>
	<?php echo $this->Form->end(); ?>
</div>
<script>
(function () {
	var sel = document.getElementById('req-type');
	var wrap = document.getElementById('asset-cat-wrap');
	function sync() {
		var opt = sel.options[sel.selectedIndex];
		wrap.style.display = opt && opt.getAttribute('data-needs-asset') === '1' ? '' : 'none';
	}
	sel.addEventListener('change', sync);
	sync();
})();
</script>
