<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
.kx-notify { max-width: 1080px; }
.kx-notify-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 18px; align-items: start; }
.kx-choice { display: flex; gap: 10px; align-items: flex-start; margin: 0 0 12px; font-weight: 600; }
.kx-choice input { margin-top: 3px; }
.kx-preview { border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; background: #f8fafc; }
.kx-preview-bar { background: #07111f; padding: 12px 16px; }
.kx-preview-bar img { height: 36px; width: auto; display: block; }
.kx-preview-body { padding: 14px 16px; color: #374151; font-size: 14px; }
.kx-preview-body p { margin: 0 0 8px; }
.kx-skip { color: #9a3412; font-size: 13px; }
.select2-container { width: 100% !important; margin-bottom: 12px; }
.select2-container .select2-selection--multiple { min-height: 46px; border-color: #d1d5db; border-radius: 8px; }
@media (max-width: 900px) { .kx-notify-grid { grid-template-columns: 1fr; } }
</style>
<div class="page-content">
	<div class="kx-sheet kx-notify">
		<?php echo $this->Form->create(null, ['url' => ['action' => 'notifySend'], 'id' => 'notice-form']); ?>
			<div class="kx-notify-grid">
				<div class="kx-card">
					<h1>Notify employees</h1>
					<p class="kx-lead">Send the ISO portal link, each person’s username, and a button so they can set their own password. Their current password stays until they use that link.</p>
					<label class="kx-field"><span>Subject</span><input id="notice-subject" name="subject" required value="<?php echo h($subject); ?>"></label>
					<label class="kx-field" style="margin-top:14px;"><span>Message</span><textarea id="notice-message" name="message" rows="10" required><?php echo h($message); ?></textarea></label>
					<div class="kx-preview">
						<div class="kx-preview-bar"><img src="<?php echo h($logoUrl); ?>" alt="Kodex"></div>
						<div class="kx-preview-body">
							<p><strong>Hello employee name,</strong></p>
							<p>Your message appears here.</p>
							<p>Username: their own username</p>
							<p>Then two buttons: open <?php echo h($portalUrl); ?>, and set their password.</p>
						</div>
					</div>
				</div>
				<div class="kx-card">
					<p class="kx-section">Who receives it</p>
					<label class="kx-choice">
						<input type="radio" name="audience" value="all" checked>
						<span>All staff with an email and login <strong>(<?php echo count($recipients); ?>)</strong></span>
					</label>
					<label class="kx-choice">
						<input type="radio" name="audience" value="selected">
						<span>Only the people I select</span>
					</label>
					<div class="notice-picker" hidden>
						<label for="notice-employees">Employees</label>
						<select id="notice-employees" name="employee_ids[]" multiple>
							<?php foreach ($recipients as $person): ?>
								<option value="<?php echo (int)$person['id']; ?>"><?php echo h($person['name'] . ' — ' . $person['username'] . ' — ' . $person['email']); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<?php if ($skipped): ?>
						<p class="kx-skip">Left out: <?php echo h(implode(', ', $skipped)); ?></p>
					<?php endif; ?>
					<div class="kx-actions">
						<button class="kx-btn kx-btn-primary" id="notice-send" type="submit">Send to all</button>
					</div>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
(function () {
	var form = document.getElementById('notice-form');
	var picker = form.querySelector('.notice-picker');
	var button = document.getElementById('notice-send');
	var total = <?php echo count($recipients); ?>;
	var list = window.jQuery('#notice-employees');
	list.select2({ width: '100%', placeholder: 'Search employees', closeOnSelect: false });
	function selectedMode() {
		var picked = form.querySelector('input[name="audience"]:checked');
		return picked ? picked.value : 'all';
	}
	function refresh() {
		var selected = selectedMode() === 'selected';
		picker.hidden = !selected;
		button.textContent = selected ? 'Send to selected' : 'Send to all (' + total + ')';
	}
	form.addEventListener('change', refresh);
	form.addEventListener('submit', function (event) {
		var selected = selectedMode() === 'selected';
		var count = (list.val() || []).length;
		if (selected && count === 0) {
			event.preventDefault();
			window.alert('Select at least one employee.');
			return;
		}
		var ask = selected
			? 'Send this email to ' + count + ' selected employee' + (count === 1 ? '' : 's') + '?'
			: 'Send this email to all ' + total + ' employees?';
		if (!window.confirm(ask)) {
			event.preventDefault();
		}
	});
	refresh();
})();
</script>
