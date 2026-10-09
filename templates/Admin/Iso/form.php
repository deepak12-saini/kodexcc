<?php
$dashed = function (string $action): string {
	return strtolower((string)preg_replace('/([a-z])([A-Z])/', '$1-$2', $action));
};
?>
<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=3">
<div class="page-content">
	<div class="kx-sheet">
		<?php echo $this->Form->create(null, ['class' => 'kx-form', 'enctype' => 'multipart/form-data']); ?>
			<div class="kx-card">
				<h1><?php echo h($title); ?></h1>
				<p class="kx-lead">Fill in the details, then save. Fields marked with * are required.</p>
				<div class="kx-grid">
					<?php foreach ($fields as $key => $field): ?>
						<?php
						$type = $field['type'] ?? 'text';
						$wide = in_array($type, ['textarea', 'file', 'select_other'], true);
						$current = $entity->{$key} ?? null;
						if (is_object($current) && method_exists($current, 'format')) {
							$current = $current->format('Y-m-d');
						}
						if (($current === null || $current === '') && isset($field['default'])) {
							$current = $field['default'];
						}
						$placeholder = (string)($field['placeholder'] ?? '');
						$emptyLabel = $placeholder !== '' ? $placeholder : 'Select';
						?>
						<label class="kx-field<?php echo $wide ? ' kx-span' : ''; ?>">
							<span><?php echo h($field['label']); ?><?php if (!empty($field['required'])): ?><span class="kx-req">*</span><?php endif; ?></span>
							<?php if ($type === 'textarea'): ?>
								<textarea name="<?php echo h($key); ?>" rows="4" placeholder="<?php echo h($placeholder); ?>"><?php echo h((string)$current); ?></textarea>
							<?php elseif ($type === 'select' || $type === 'select_other' || $type === 'person'): ?>
								<?php
								$options = $type === 'person'
									? array_combine($isoPeople, $isoPeople)
									: $field['options'];
								if (!is_array($options)) {
									$options = [];
								}
								if ($type === 'person' && $current !== null && $current !== '' && !isset($options[$current])) {
									$options = [(string)$current => (string)$current] + $options;
								}
								$selectedLabel = ($current !== null && $current !== '' && isset($options[$current])) ? (string)$options[$current] : '';
								?>
								<div class="kx-pick">
									<input type="hidden" name="<?php echo h($key); ?>" value="<?php echo h((string)$current); ?>">
									<button type="button" class="kx-pick-btn<?php echo $selectedLabel === '' ? ' is-placeholder' : ''; ?>" data-empty="<?php echo h($emptyLabel); ?>"><?php echo $selectedLabel !== '' ? h($selectedLabel) : h($emptyLabel); ?></button>
									<div class="kx-pick-menu" hidden>
										<input type="search" class="kx-pick-search" placeholder="Search" autocomplete="off">
										<div class="kx-pick-list">
											<?php if (empty($field['required'])): ?>
												<button type="button" data-value="">Select</button>
											<?php endif; ?>
											<?php foreach ($options as $ok => $ol): ?>
												<button type="button" data-value="<?php echo h((string)$ok); ?>"><?php echo h($ol); ?></button>
											<?php endforeach; ?>
											<?php if ($type === 'select_other'): ?>
												<button type="button" data-value="__other__"><?php echo h($field['other_label'] ?? 'Other — add new'); ?></button>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<?php if ($type === 'select_other'): ?>
									<input type="text" class="kx-other" name="<?php echo h($key); ?>_other" id="<?php echo h($key); ?>-other" placeholder="<?php echo h($field['other_placeholder'] ?? 'Type the new name'); ?>" style="display:<?php echo (string)$current === '__other__' ? 'block' : 'none'; ?>;">
								<?php endif; ?>
							<?php elseif ($type === 'file'): ?>
								<span class="kx-file">
									<input type="file" name="<?php echo h($key); ?>">
									<?php if (!empty($entity->{$key})): ?>
										<a class="kx-link" href="<?php echo SITEURL . h((string)$entity->{$key}); ?>" target="_blank">Open current file</a>
									<?php endif; ?>
								</span>
							<?php elseif ($type === 'date'): ?>
								<input type="date" name="<?php echo h($key); ?>" value="<?php echo h((string)$current); ?>">
							<?php else: ?>
								<input type="text" name="<?php echo h($key); ?>" value="<?php echo h((string)$current); ?>" placeholder="<?php echo h($placeholder); ?>">
							<?php endif; ?>
						</label>
					<?php endforeach; ?>
				</div>
				<div class="kx-actions">
					<?php if (!empty($canEdit)): ?><button class="kx-btn kx-btn-primary" type="submit">Save</button><?php endif; ?>
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . 'admin/iso/' . h($dashed($listAction)); ?>">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
		<?php if (!empty($revisions) && count($revisions)): ?>
			<div class="kx-card">
				<h1>Earlier revisions</h1>
				<div class="kx-table-wrap">
					<table class="kx-table">
						<thead><tr><th>Revision</th><th>Title</th><th>Status</th><th>When</th></tr></thead>
						<tbody>
							<?php foreach ($revisions as $rev): ?>
								<tr>
									<td><?php echo h($rev->revision); ?></td>
									<td><?php echo h($rev->title); ?></td>
									<td><?php echo h($rev->status); ?></td>
									<td><?php echo h((string)$rev->created); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<script>
document.querySelectorAll('.kx-pick').forEach(function (pick) {
	var btn = pick.querySelector('.kx-pick-btn');
	var menu = pick.querySelector('.kx-pick-menu');
	var hidden = pick.querySelector('input[type="hidden"]');
	var search = pick.querySelector('.kx-pick-search');
	function closeAll() {
		document.querySelectorAll('.kx-pick').forEach(function (other) {
			other.classList.remove('is-open');
			other.querySelector('.kx-pick-menu').setAttribute('hidden', '');
		});
	}
	btn.addEventListener('click', function () {
		var opening = menu.hasAttribute('hidden');
		closeAll();
		if (opening) {
			pick.classList.add('is-open');
			menu.removeAttribute('hidden');
			search.value = '';
			pick.querySelectorAll('.kx-pick-list button').forEach(function (opt) { opt.hidden = false; });
			search.focus();
		}
	});
	search.addEventListener('input', function () {
		var q = search.value.toLowerCase();
		pick.querySelectorAll('.kx-pick-list button').forEach(function (opt) {
			opt.hidden = opt.textContent.toLowerCase().indexOf(q) === -1;
		});
	});
	search.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeAll();
		}
	});
	pick.querySelectorAll('.kx-pick-list button').forEach(function (opt) {
		opt.addEventListener('click', function () {
			hidden.value = opt.getAttribute('data-value') || '';
			btn.textContent = hidden.value === '' ? btn.getAttribute('data-empty') : opt.textContent;
			btn.classList.toggle('is-placeholder', hidden.value === '');
			closeAll();
			var other = document.getElementById(hidden.name + '-other');
			if (other) {
				other.style.display = hidden.value === '__other__' ? 'block' : 'none';
			}
		});
	});
});
document.addEventListener('click', function (event) {
	if (!event.target.closest('.kx-pick')) {
		document.querySelectorAll('.kx-pick').forEach(function (pick) {
			pick.classList.remove('is-open');
			pick.querySelector('.kx-pick-menu').setAttribute('hidden', '');
		});
	}
});
</script>
