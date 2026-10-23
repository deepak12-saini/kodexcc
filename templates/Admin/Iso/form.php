<?php
$basePath = $basePath ?? 'admin/iso/';
$dashed = function (string $action): string {
	return strtolower((string)preg_replace('/([a-z])([A-Z])/', '$1-$2', $action));
};
?>
<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
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
						$wide = in_array($type, ['textarea', 'file', 'select_other', 'evidence'], true);
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
							<?php elseif ($type === 'evidence'): ?>
								<?php
								$savedFiles = json_decode((string)($entity->evidence_files ?? ''), true);
								$savedFiles = is_array($savedFiles) ? $savedFiles : [];
								$evidenceKind = function (string $path): string {
									$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
									if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
										return 'image';
									}
									if (in_array($ext, ['mp4', 'webm', 'mov'], true)) {
										return 'video';
									}

									return 'doc';
								};
								?>
								<div class="kx-evidence">
									<textarea name="<?php echo h($key); ?>" rows="4" placeholder="<?php echo h($placeholder); ?>"><?php echo h((string)$current); ?></textarea>
									<?php if (!empty($canEdit)): ?>
										<label class="kx-drop">
											<input type="file" name="<?php echo h($key); ?>_files[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,video/mp4,video/webm,video/quicktime">
											<span class="kx-drop-title">Add a photo, document, or video</span>
											<span class="kx-drop-hint">JPG, PNG, PDF, Word, Excel, or MP4. Up to 25 MB each. You can choose more than one.</span>
										</label>
										<div class="kx-evidence-list" data-pending></div>
									<?php endif; ?>
									<?php if ($savedFiles): ?>
										<div class="kx-evidence-list">
											<?php foreach ($savedFiles as $path): ?>
												<?php
												$path = (string)$path;
												$kind = $evidenceKind($path);
												$name = basename($path);
												?>
												<div class="kx-evidence-card">
													<?php if ($kind === 'image'): ?>
														<a href="<?php echo SITEURL . h($path); ?>" target="_blank"><img src="<?php echo SITEURL . h($path); ?>" alt=""></a>
													<?php elseif ($kind === 'video'): ?>
														<video controls preload="metadata" src="<?php echo SITEURL . h($path); ?>"></video>
													<?php else: ?>
														<a class="kx-evidence-doc" href="<?php echo SITEURL . h($path); ?>" target="_blank"><i class="fa fa-file-text-o"></i></a>
													<?php endif; ?>
													<div class="kx-evidence-meta">
														<a href="<?php echo SITEURL . h($path); ?>" target="_blank"><?php echo h($name); ?></a>
														<?php if (!empty($canEdit)): ?>
															<input type="hidden" name="<?php echo h($key); ?>_keep[]" value="<?php echo h($path); ?>">
															<button type="button" class="kx-evidence-remove">Remove</button>
														<?php endif; ?>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
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
					<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath . h($dashed($listAction)); ?>">Back</a>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
		<?php if (!empty($revisions) && count($revisions)): ?>
			<div class="kx-card">
				<h1>Earlier revisions</h1>
				<div class="kx-table-wrap">
					<table class="kx-table">
						<thead><tr><th>Revision</th><th>Title</th><th>Status</th><th>File</th><th>When</th></tr></thead>
						<tbody>
							<?php foreach ($revisions as $rev): ?>
								<tr>
									<td><?php echo h($rev->revision); ?></td>
									<td><?php echo h($rev->title ?? ''); ?></td>
									<td><?php echo !empty($rev->obsolete) ? 'Obsolete' : h((string)$rev->status); ?></td>
									<td>
										<?php if (!empty($rev->file_path)): ?>
											<a href="<?php echo SITEURL . h((string)$rev->file_path); ?>" target="_blank">Open file</a>
										<?php endif; ?>
									</td>
									<td><?php echo h((string)$rev->created); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
		<?php if (isset($sopReviewLog)): ?>
			<div class="kx-card">
				<h1>Reviews</h1>
				<?php if (!count($sopReviewLog)): ?>
					<p class="kx-lead">No reviews yet.</p>
				<?php else: ?>
					<div class="kx-table-wrap">
						<table class="kx-table">
							<thead><tr><th>Person</th><th>Result</th><th>Reason</th><th>When</th></tr></thead>
							<tbody>
								<?php foreach ($sopReviewLog as $review): ?>
									<tr>
										<td><?php echo h($review->employee_name); ?></td>
										<td><?php echo h(\App\Controller\Admin\IsoController::SOP_REVIEW_RESULTS[$review->result] ?? $review->result); ?></td>
										<td><?php echo h($review->reason); ?></td>
										<td><?php echo h((string)$review->created); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
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
document.querySelectorAll('.kx-evidence').forEach(function (box) {
	var input = box.querySelector('input[type="file"]');
	var pending = box.querySelector('[data-pending]');
	if (!input || !pending) {
		return;
	}
	input.addEventListener('change', function () {
		pending.innerHTML = '';
		Array.prototype.forEach.call(input.files || [], function (file) {
			var card = document.createElement('div');
			card.className = 'kx-evidence-card';
			if (file.type.indexOf('image/') === 0) {
				var img = document.createElement('img');
				img.alt = '';
				img.src = URL.createObjectURL(file);
				card.appendChild(img);
			} else if (file.type.indexOf('video/') === 0) {
				var video = document.createElement('video');
				video.controls = true;
				video.src = URL.createObjectURL(file);
				card.appendChild(video);
			} else {
				var doc = document.createElement('div');
				doc.className = 'kx-evidence-doc';
				doc.textContent = 'DOC';
				card.appendChild(doc);
			}
			var meta = document.createElement('div');
			meta.className = 'kx-evidence-meta';
			meta.textContent = file.name;
			card.appendChild(meta);
			pending.appendChild(card);
		});
	});
});
document.querySelectorAll('.kx-evidence-remove').forEach(function (button) {
	button.addEventListener('click', function () {
		var card = button.closest('.kx-evidence-card');
		if (card) {
			card.remove();
		}
	});
});
</script>
