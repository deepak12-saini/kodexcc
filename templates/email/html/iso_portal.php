<?php
/**
 * @var string $employeeName
 * @var string $message
 * @var string $portalUrl
 * @var string $username
 * @var string $resetUrl
 */
$blocks = preg_split("/\r\n|\n|\r/", (string)$message) ?: [];
?>
<p style="margin:0 0 16px;font-size:18px;">Hello <?= h($employeeName) ?>,</p>
<?php foreach ($blocks as $block): ?>
	<?php if (trim($block) === '') continue; ?>
	<p style="margin:0 0 14px;"><?= str_replace(
		h($portalUrl),
		'<a href="' . h($portalUrl) . '" style="color:#0f766e;">' . h($portalUrl) . '</a>',
		h($block)
	) ?></p>
<?php endforeach; ?>
<table role="presentation" cellpadding="0" cellspacing="0" style="margin:8px 0 18px;width:100%;background:#f8fafc;border:1px solid #e5e7eb;border-radius:10px;">
	<tr>
		<td style="padding:14px 16px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#111827;">
			<p style="margin:0 0 8px;font-weight:bold;">Your ISO portal login</p>
			<p style="margin:0 0 4px;">Username: <strong><?= h($username ?? '') ?></strong></p>
			<p style="margin:0;">Password: choose your own with the button below. This link works for 24 hours.</p>
		</td>
	</tr>
</table>
<p style="margin:22px 0 8px;">
	<a href="<?= h($portalUrl) ?>" style="display:inline-block;background:#0f766e;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 22px;border-radius:8px;">Open the ISO portal</a>
</p>
<p style="margin:8px 0 8px;">
	<a href="<?= h($resetUrl ?? '') ?>" style="display:inline-block;background:#ffffff;color:#0f766e;text-decoration:none;font-weight:bold;padding:11px 22px;border-radius:8px;border:2px solid #0f766e;">Set your password</a>
</p>
