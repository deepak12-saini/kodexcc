<link rel="stylesheet" href="<?php echo SITEURL; ?>css/admin-forms.css?v=4">
<div class="page-content">
	<div class="kx-sheet kx-overview"><div class="kx-card">
		<h1>Financials</h1>
		<p class="kx-lead">Purchase bills, sales invoices and bank payments post journal entries. Run these reports beside the existing books for one full month and let the CA confirm the trial balance and GST before switching over.</p>
		<div class="kx-modules">
			<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/trial-balance"><span><strong>Trial balance</strong><small>Debits and credits</small></span></a>
			<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/profit-loss"><span><strong>Profit and loss</strong><small>Income and expenses</small></span></a>
			<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/balance-sheet"><span><strong>Balance sheet</strong><small>Assets, liabilities and capital</small></span></a>
			<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/gst-summary"><span><strong>GST summary</strong><small>Outward and inward tax</small></span></a>
			<a class="kx-module" href="<?php echo SITEURL; ?>admin/erp/opening-form"><span><strong>Opening balances</strong><small>What the CA brings in on day one</small></span></a>
		</div>
		<h2 class="kx-section">Period lock</h2>
		<?php if (!empty($canEdit)): ?>
			<?php echo $this->Form->create(null, ['url' => ['action' => 'lockPeriod'], 'class' => 'kx-form']); ?>
				<label class="kx-field"><span>Period (YYYY-MM)</span><input type="text" name="period" placeholder="2026-10"></label>
				<div class="kx-actions"><button class="kx-btn kx-btn-primary" type="submit">Lock period</button></div>
			<?php echo $this->Form->end(); ?>
		<?php endif; ?>
		<ul>
			<?php foreach ($periods as $period): ?><li><?php echo h($period->period); ?> locked</li><?php endforeach; ?>
		</ul>
	</div></div>
</div>
