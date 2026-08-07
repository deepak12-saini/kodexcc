<?php
/**
 * KodexCC Homepage — Private Label OEM Manufacturing
 */
$this->Html->css('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Syne:wght@600;700;800&display=swap', ['block' => true]);
$this->Html->css('kodex-oem-home', ['block' => true]);
$this->Html->script('kodex-oem-home', ['block' => true]);
?>
<main class="kx-home" id="kx-home">

	<!-- 1. Hero -->
	<section class="kx-hero" aria-label="Hero">
		<div class="kx-hero__media" aria-hidden="true">
			<img
				src="<?php echo SITEURL; ?>wp-content/uploads/Liquid-Polyurethane.jpg"
				alt=""
				class="kx-hero__img"
				fetchpriority="high"
			>
			<div class="kx-hero__veil"></div>
			<div class="kx-hero__grain"></div>
		</div>
		<div class="kx-hero__inner">
			<p class="kx-hero__kicker kx-reveal">Technology-Driven OEM · Confidential</p>
			<h1 class="kx-hero__title kx-reveal kx-reveal--delay-1">
				Private Label<br>Technology-Driven<br>OEM Manufacturing
			</h1>
			<p class="kx-hero__lede kx-reveal kx-reveal--delay-2">
				Confidential manufacturing of advanced polymers, sealants, silicones, and coatings for global brands.
			</p>
			<div class="kx-hero__cta kx-reveal kx-reveal--delay-3">
				<a class="kx-btn kx-btn--primary" href="<?php echo SITEURL; ?>contact-us">Start a Partnership</a>
				<a class="kx-btn kx-btn--ghost" href="#divisions">Explore Divisions</a>
			</div>
			<p class="kx-hero__tagline kx-reveal kx-reveal--delay-4">
				Confidential Manufacturing. Advanced Materials. Trusted Partnerships.
			</p>
		</div>
		<a class="kx-hero__scroll" href="#about" aria-label="Scroll to about">
			<span></span>
		</a>
	</section>

	<!-- Trust strip -->
	<section class="kx-trust" aria-label="Partnership principles">
		<div class="kx-wrap kx-trust__row">
			<div class="kx-trust__item"><span>NDA-First Partnerships</span></div>
			<div class="kx-trust__item"><span>Private Label Focus</span></div>
			<div class="kx-trust__item"><span>No Client Logos Published</span></div>
			<div class="kx-trust__item"><span>Advanced Materials R&amp;D</span></div>
		</div>
	</section>

	<!-- 2. About -->
	<section class="kx-section kx-about" id="about">
		<div class="kx-wrap kx-about__grid">
			<div class="kx-about__copy kx-reveal">
				<p class="kx-eyebrow">About KodexCC</p>
				<h2 class="kx-h2">Specialist private label manufacturing, built on technology and trust.</h2>
				<p class="kx-body">
					KodexCC develops and manufactures advanced polymers, sealants, silicones, and coatings for global brands.
					We combine innovation, research, and strict confidentiality to deliver high-performance private label manufacturing solutions.
				</p>
				<p class="kx-body">
					We operate behind the scenes — formulating, producing, and protecting your intellectual property under NDA-backed partnerships.
					Your brand stays yours. Our role is precision manufacturing at scale.
				</p>
				<a class="kx-text-link" href="<?php echo SITEURL; ?>about-us">Learn more about KodexCC</a>
			</div>
			<figure class="kx-about__visual kx-reveal kx-reveal--delay-1">
				<img
					src="<?php echo SITEURL; ?>wp-content/uploads/Silicon.jpg"
					alt="KodexCC advanced materials manufacturing"
					loading="lazy"
				>
				<figcaption class="kx-about__caption">Technology-led production · Confidential OEM</figcaption>
			</figure>
		</div>
	</section>

	<!-- Process -->
	<section class="kx-section kx-process" id="process">
		<div class="kx-wrap">
			<header class="kx-section__head kx-reveal">
				<p class="kx-eyebrow">How We Partner</p>
				<h2 class="kx-h2">A discreet path from brief to brand-ready supply.</h2>
			</header>
			<ol class="kx-process__list">
				<li class="kx-process__step kx-reveal">
					<span class="kx-process__num">01</span>
					<h3>Discover</h3>
					<p>Confidential briefing on performance, markets, and brand requirements.</p>
				</li>
				<li class="kx-process__step kx-reveal kx-reveal--delay-1">
					<span class="kx-process__num">02</span>
					<h3>Formulate</h3>
					<p>R&amp;D and materials science tuned to your private label specification.</p>
				</li>
				<li class="kx-process__step kx-reveal kx-reveal--delay-2">
					<span class="kx-process__num">03</span>
					<h3>Manufacture</h3>
					<p>Controlled production with quality systems built for consistency.</p>
				</li>
				<li class="kx-process__step kx-reveal kx-reveal--delay-3">
					<span class="kx-process__num">04</span>
					<h3>Deliver</h3>
					<p>Private labelling, documentation, and long-term supply partnership.</p>
				</li>
			</ol>
		</div>
	</section>

	<!-- 3. Four Business Divisions -->
	<section class="kx-section kx-divisions" id="divisions">
		<div class="kx-wrap">
			<header class="kx-section__head kx-reveal">
				<p class="kx-eyebrow">Business Divisions</p>
				<h2 class="kx-h2">Four specialised material platforms.</h2>
				<p class="kx-body kx-body--narrow">
					Purpose-built chemistries and processes for private label programmes across polymers, sealants, silicones, and coatings.
				</p>
			</header>
			<div class="kx-divisions__grid">
				<article class="kx-division kx-reveal">
					<div class="kx-division__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Liquid-Polyurethane.jpg)"></div>
					<div class="kx-division__content">
						<span class="kx-division__num">01</span>
						<h3 class="kx-division__title">Polymers</h3>
						<p class="kx-division__desc">Engineered polymer systems formulated for performance, processability, and brand-specific requirements.</p>
					</div>
				</article>
				<article class="kx-division kx-reveal kx-reveal--delay-1">
					<div class="kx-division__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/Banner-Sealants.jpg)"></div>
					<div class="kx-division__content">
						<span class="kx-division__num">02</span>
						<h3 class="kx-division__title">Sealants</h3>
						<p class="kx-division__desc">High-performance sealant technologies developed for demanding industrial and construction applications.</p>
					</div>
				</article>
				<article class="kx-division kx-reveal kx-reveal--delay-2">
					<div class="kx-division__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Silicon.jpg)"></div>
					<div class="kx-division__content">
						<span class="kx-division__num">03</span>
						<h3 class="kx-division__title">Silicones</h3>
						<p class="kx-division__desc">Advanced silicone formulations with tailored rheology, adhesion, and durability profiles.</p>
					</div>
				</article>
				<article class="kx-division kx-reveal kx-reveal--delay-3">
					<div class="kx-division__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Cement-Based.jpg)"></div>
					<div class="kx-division__content">
						<span class="kx-division__num">04</span>
						<h3 class="kx-division__title">Coatings</h3>
						<p class="kx-division__desc">Protective and specialty coatings engineered for longevity, compliance, and end-use conditions.</p>
					</div>
				</article>
			</div>
		</div>
	</section>

	<!-- 4. Why Choose Kodex -->
	<section class="kx-section kx-why" id="why">
		<div class="kx-wrap">
			<header class="kx-section__head kx-reveal">
				<p class="kx-eyebrow">Why KodexCC</p>
				<h2 class="kx-h2">Built for brands that demand discretion and excellence.</h2>
			</header>
			<div class="kx-why__grid">
				<article class="kx-why__item kx-reveal">
					<div class="kx-why__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/Banner-Sealants.jpg)"></div>
					<div class="kx-why__content">
						<span class="kx-why__icon" aria-hidden="true">
							<svg viewBox="0 0 48 48" fill="none"><path d="M24 6l14 6v10c0 10-6.5 16.5-14 20-7.5-3.5-14-10-14-20V12l14-6z" stroke="currentColor" stroke-width="1.75"/><path d="M17 24l5 5 9-10" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</span>
						<h3 class="kx-why__title">Private Label Manufacturing</h3>
						<p class="kx-why__text">End-to-end OEM production under your brand — from formulation support to finished goods.</p>
					</div>
				</article>
				<article class="kx-why__item kx-reveal kx-reveal--delay-1">
					<div class="kx-why__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Primer.jpg)"></div>
					<div class="kx-why__content">
						<span class="kx-why__icon" aria-hidden="true">
							<svg viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="16" stroke="currentColor" stroke-width="1.75"/><path d="M24 14v10l7 4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>
						</span>
						<h3 class="kx-why__title">Research &amp; Development</h3>
						<p class="kx-why__text">Dedicated R&amp;D that turns performance targets into manufacturable, scalable chemistries.</p>
					</div>
				</article>
				<article class="kx-why__item kx-reveal kx-reveal--delay-2">
					<div class="kx-why__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/about-us.jpg)"></div>
					<div class="kx-why__content">
						<span class="kx-why__icon" aria-hidden="true">
							<svg viewBox="0 0 48 48" fill="none"><rect x="8" y="14" width="32" height="22" rx="2" stroke="currentColor" stroke-width="1.75"/><path d="M8 22h32M16 14v-4h16v4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/></svg>
						</span>
						<h3 class="kx-why__title">Advanced Manufacturing</h3>
						<p class="kx-why__text">Process-controlled production environments designed for consistency batch after batch.</p>
					</div>
				</article>
				<article class="kx-why__item kx-reveal kx-reveal--delay-3">
					<div class="kx-why__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Silicon.jpg)"></div>
					<div class="kx-why__content">
						<span class="kx-why__icon" aria-hidden="true">
							<svg viewBox="0 0 48 48" fill="none"><path d="M12 34l8-20 8 20M15 28h10" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/><circle cx="34" cy="18" r="6" stroke="currentColor" stroke-width="1.75"/></svg>
						</span>
						<h3 class="kx-why__title">Quality Assurance</h3>
						<p class="kx-why__text">Rigorous testing and quality systems that protect your brand reputation in every market.</p>
					</div>
				</article>
				<article class="kx-why__item kx-why__item--wide kx-reveal kx-reveal--delay-4">
					<div class="kx-why__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Kodex-Banner.jpg)"></div>
					<div class="kx-why__content kx-why__content--wide">
						<span class="kx-why__icon" aria-hidden="true">
							<svg viewBox="0 0 48 48" fill="none"><rect x="10" y="10" width="28" height="28" rx="4" stroke="currentColor" stroke-width="1.75"/><path d="M18 24h12M24 18v12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/><path d="M14 8v4M34 8v4M8 14h4M8 34h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity=".5"/></svg>
						</span>
						<div>
							<h3 class="kx-why__title">Complete Confidentiality</h3>
							<p class="kx-why__text">
								We manufacture for other brands under strict confidentiality. Formulations, volumes, and partnerships remain private — always.
								We do not publish client identities or portfolio brands.
							</p>
						</div>
					</div>
				</article>
			</div>
		</div>
	</section>

	<!-- 5. Manufacturing Capabilities -->
	<section class="kx-section kx-capabilities" id="capabilities">
		<div class="kx-capabilities__bg" aria-hidden="true" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Kodex-Banner.jpg)"></div>
		<div class="kx-wrap kx-capabilities__inner">
			<header class="kx-section__head kx-section__head--light kx-reveal">
				<p class="kx-eyebrow kx-eyebrow--light">Manufacturing Capabilities</p>
				<h2 class="kx-h2">Precision production for private label programmes.</h2>
				<p class="kx-body kx-body--light kx-body--narrow">
					From laboratory development to commercial-scale output, KodexCC delivers controlled manufacturing with documentation, traceability, and partnership-grade service.
				</p>
			</header>
			<ul class="kx-cap-list kx-reveal kx-reveal--delay-1">
				<li>Custom formulation &amp; scale-up</li>
				<li>Batch consistency &amp; process control</li>
				<li>Private labelling &amp; packaging</li>
				<li>Technical documentation support</li>
				<li>Flexible production volumes</li>
				<li>Long-term supply partnerships</li>
			</ul>
		</div>
	</section>

	<!-- 6. Industries We Serve -->
	<section class="kx-section kx-industries" id="industries">
		<div class="kx-wrap">
			<header class="kx-section__head kx-reveal">
				<p class="kx-eyebrow">Industries We Serve</p>
				<h2 class="kx-h2">Materials expertise across critical markets.</h2>
				<p class="kx-body kx-body--narrow">
					We partner with brand owners and distributors who require confidential OEM manufacturing — not retail storefronts.
				</p>
			</header>
			<div class="kx-industries__row">
				<article class="kx-industry kx-reveal">
					<div class="kx-industry__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Cement-Based.jpg)"></div>
					<span>Construction Materials</span>
				</article>
				<article class="kx-industry kx-reveal kx-reveal--delay-1">
					<div class="kx-industry__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/about-us.jpg)"></div>
					<span>Industrial Manufacturing</span>
				</article>
				<article class="kx-industry kx-reveal kx-reveal--delay-2">
					<div class="kx-industry__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Liquid-Polyurethane.jpg)"></div>
					<span>Specialty Chemicals</span>
				</article>
				<article class="kx-industry kx-reveal kx-reveal--delay-3">
					<div class="kx-industry__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/Banner-Waterproofing.jpg)"></div>
					<span>Infrastructure &amp; Protection</span>
				</article>
				<article class="kx-industry kx-reveal kx-reveal--delay-4">
					<div class="kx-industry__bg" style="background-image:url(<?php echo SITEURL; ?>wp-content/uploads/Kodex-Banner.jpg)"></div>
					<span>Global Brand Programmes</span>
				</article>
			</div>
		</div>
	</section>

	<!-- 7. Contact / Enquiry -->
	<section class="kx-section kx-contact" id="enquiry">
		<div class="kx-wrap kx-contact__panel kx-reveal">
			<div class="kx-contact__copy">
				<p class="kx-eyebrow">Partner With Us</p>
				<h2 class="kx-h2">Ready to discuss a confidential manufacturing programme?</h2>
				<p class="kx-body">
					Share your requirements in confidence. Our team will respond with a discreet, technical conversation — never a public pitch.
				</p>
			</div>
			<div class="kx-contact__actions">
				<a class="kx-btn kx-btn--primary" href="<?php echo SITEURL; ?>contact-us">Make an Enquiry</a>
				<a class="kx-contact__link" href="mailto:sales@kodexcc.com">sales@kodexcc.com</a>
				<a class="kx-contact__link" href="tel:1800418495">1800 418 495</a>
			</div>
		</div>
	</section>

</main>
