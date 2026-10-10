<?php
/**
 * @var \App\View\AppView $this
 * @var string $logoUrl
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= h($this->fetch('title')) ?></title>
</head>
<body style="margin:0;padding:0;background:#eef2f6;">
	<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef2f6;padding:28px 12px;">
		<tr>
			<td align="center">
				<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;">
					<tr>
						<td style="background:#07111f;padding:18px 28px;">
							<img src="<?= h($logoUrl ?? 'https://kodexcc.com/img/kodex-logo.png') ?>" alt="Kodex" height="48" style="display:block;height:48px;width:auto;max-width:220px;border:0;">
						</td>
					</tr>
					<tr>
						<td style="padding:28px 28px 8px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:1.55;color:#111827;">
							<?= $this->fetch('content') ?>
						</td>
					</tr>
					<tr>
						<td style="padding:8px 28px 28px;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:1.5;color:#6b7280;">
							KodexCC hardware portal
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
