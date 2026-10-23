<?php
$basePath = $basePath ?? 'admin/iso/';
$dashed = function (string $action): string {
	return strtolower((string)preg_replace('/([a-z])([A-Z])/', '$1-$2', $action));
};
$date = function ($value): string {
	if (is_object($value) && method_exists($value, 'format')) {
		return $value->format('Y-m-d');
	}

	return (string)$value;
};
$statuses = \App\Controller\Admin\IsoController::SOP_STATUSES;
$results = \App\Controller\Admin\IsoController::SOP_REVIEW_RESULTS;
?>
<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet">
		<div class="kx-card">
			<h1><?php echo h($entity->sop_no); ?></h1>
			<p class="kx-lead"><?php echo h($entity->title); ?></p>
			<div class="kx-grid">
				<label class="kx-field"><span>Department</span><input type="text" value="<?php echo h((string)$entity->department); ?>" readonly></label>
				<label class="kx-field"><span>Revision</span><input type="text" value="<?php echo h((string)$entity->revision); ?>" readonly></label>
				<label class="kx-field"><span>Status</span><input type="text" value="<?php echo h($statuses[$entity->status] ?? (string)$entity->status); ?>" readonly></label>
				<label class="kx-field"><span>Next review date</span><input type="text" value="<?php echo h($date($entity->next_review_on)); ?>" readonly></label>
				<label class="kx-field"><span>Prepared by</span><input type="text" value="<?php echo h((string)$entity->prepared_by); ?>" readonly></label>
				<label class="kx-field"><span>Prepared on</span><input type="text" value="<?php echo h($date($entity->prepared_on)); ?>" readonly></label>
				<label class="kx-field"><span>Reviewed by</span><input type="text" value="<?php echo h((string)$entity->reviewed_by); ?>" readonly></label>
				<label class="kx-field"><span>Reviewed on</span><input type="text" value="<?php echo h($date($entity->reviewed_on)); ?>" readonly></label>
				<label class="kx-field"><span>Approved by</span><input type="text" value="<?php echo h((string)$entity->approved_by); ?>" readonly></label>
				<label class="kx-field"><span>Approved on</span><input type="text" value="<?php echo h($date($entity->approved_on)); ?>" readonly></label>
				<label class="kx-field kx-span"><span>File</span>
					<?php if (!empty($entity->file_path)): ?>
						<a class="kx-link" href="<?php echo SITEURL . h((string)$entity->file_path); ?>" target="_blank">Open current file</a>
					<?php else: ?>
						<input type="text" value="No file uploaded yet" readonly>
					<?php endif; ?>
				</label>
				<?php if ((string)$entity->notes !== ''): ?>
					<label class="kx-field kx-span"><span>Notes</span><textarea rows="4" readonly><?php echo h((string)$entity->notes); ?></textarea></label>
				<?php endif; ?>
			</div>
			<div class="kx-actions">
				<a class="kx-btn kx-btn-ghost" href="<?php echo SITEURL . $basePath . h($dashed($listAction)); ?>">Back</a>
			</div>
		</div>
		<?php if (!empty($revisions) && count($revisions)): ?>
			<div class="kx-card">
				<h1>Earlier revisions</h1>
				<p class="kx-lead">These files are obsolete. The current file is the one above.</p>
				<div class="kx-table-wrap">
					<table class="kx-table">
						<thead><tr><th>Revision</th><th>Title</th><th>Status</th><th>File</th><th>When</th></tr></thead>
						<tbody>
							<?php foreach ($revisions as $rev): ?>
								<tr>
									<td><?php echo h($rev->revision); ?></td>
									<td><?php echo h((string)$rev->title); ?></td>
									<td>Obsolete</td>
									<td><?php if (!empty($rev->file_path)): ?><a href="<?php echo SITEURL . h((string)$rev->file_path); ?>" target="_blank">Open file</a><?php endif; ?></td>
									<td><?php echo h((string)$rev->created); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
		<div class="kx-card">
			<h1>Reviews</h1>
			<?php if (empty($sopReviewLog) || !count($sopReviewLog)): ?>
				<p class="kx-lead">No reviews yet.</p>
			<?php else: ?>
				<div class="kx-table-wrap">
					<table class="kx-table">
						<thead><tr><th>Person</th><th>Result</th><th>Reason</th><th>When</th></tr></thead>
						<tbody>
							<?php foreach ($sopReviewLog as $review): ?>
								<tr>
									<td><?php echo h($review->employee_name); ?></td>
									<td><?php echo h($results[$review->result] ?? $review->result); ?></td>
									<td><?php echo h($review->reason); ?></td>
									<td><?php echo h((string)$review->created); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
			<?php if (!empty($ownReview)): ?>
				<p class="kx-lead">You reviewed this SOP on <?php echo h((string)$ownReview->created); ?>.</p>
			<?php elseif (!empty($canReview)): ?>
				<?php echo $this->Form->create(null, ['url' => ['action' => 'sopReview', $entity->id], 'class' => 'kx-form']); ?>
					<div class="kx-grid">
						<label class="kx-field">
							<span>Result<span class="kx-req">*</span></span>
							<select name="result">
								<option value="">Select</option>
								<?php foreach ($results as $key => $label): ?>
									<option value="<?php echo h($key); ?>"><?php echo h($label); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
						<label class="kx-field kx-span">
							<span>Reason<span class="kx-req">*</span></span>
							<textarea name="reason" rows="4" placeholder="Why you reviewed it, or what needs to change"></textarea>
						</label>
					</div>
					<div class="kx-actions">
						<button class="kx-btn kx-btn-primary" type="submit">Submit review</button>
					</div>
				<?php echo $this->Form->end(); ?>
			<?php else: ?>
				<p class="kx-lead">Your login is not linked to an employee, so a review cannot be saved.</p>
			<?php endif; ?>
		</div>
	</div>
</div>
