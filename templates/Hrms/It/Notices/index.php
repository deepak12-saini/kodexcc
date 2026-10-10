<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<div class="notice-page">
	<div class="notice-intro">
		<h2>Notify employees</h2>
		<p>Each email includes the Kodex logo, the message, and that person’s portal username and a new password. Their previous password stops working after the email is sent. Your own admin password is left unchanged.</p>
	</div>
	<?php echo $this->Form->create(null, ['url' => ['action' => 'send'], 'class' => 'notice-grid', 'id' => 'notice-form']); ?>
		<section class="hrms-panel notice-card">
			<h3>Message</h3>
			<label for="notice-subject">Subject</label>
			<input id="notice-subject" type="text" name="subject" required value="<?php echo h($subject); ?>">
			<label for="notice-message">Message</label>
			<textarea id="notice-message" name="message" rows="12" required><?php echo h($message); ?></textarea>
			<div class="notice-preview">
				<div class="notice-preview-bar">
					<img src="<?php echo h($logoUrl); ?>" alt="Kodex">
				</div>
				<p>Hello employee name,</p>
				<p class="notice-preview-note">Your message appears here, then the username and new password, then a button to open <?php echo h($portalUrl); ?>.</p>
			</div>
		</section>
		<section class="hrms-panel notice-card">
			<h3>Who receives it</h3>
			<label class="notice-choice">
				<input type="radio" name="audience" value="all" checked>
				<span>All employees with an email and login <strong>(<?php echo count($recipients); ?>)</strong></span>
			</label>
			<label class="notice-choice">
				<input type="radio" name="audience" value="selected">
				<span>Only the employees I select</span>
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
				<p class="notice-skip">Left out: <?php echo h(implode(', ', $skipped)); ?></p>
			<?php endif; ?>
			<button class="hrms-btn hrms-btn-primary" id="notice-send" type="submit">Send to all</button>
		</section>
	<?php echo $this->Form->end(); ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
(function () {
	var form = document.getElementById('notice-form');
	var picker = form.querySelector('.notice-picker');
	var button = document.getElementById('notice-send');
	var total = <?php echo count($recipients); ?>;
	var $list = window.jQuery('#notice-employees');
	$list.select2({
		width: '100%',
		placeholder: 'Search employees',
		closeOnSelect: false
	});
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
		var count = ($list.val() || []).length;
		if (selected && count === 0) {
			event.preventDefault();
			window.alert('Select at least one employee.');
			return;
		}
		var ask = selected
			? 'Send this email to ' + count + ' selected employee' + (count === 1 ? '' : 's') + '? Each person gets a new password.'
			: 'Send this email to all ' + total + ' employees? Each person gets a new password.';
		if (!window.confirm(ask)) {
			event.preventDefault();
		}
	});
	refresh();
})();
</script>
