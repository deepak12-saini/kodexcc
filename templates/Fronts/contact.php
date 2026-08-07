<?php
/**
 * Contact KodexCC — Confidential partnership enquiries
 */
$this->Html->css('kodex-oem-home', ['block' => true]);
$this->Html->script('kodex-oem-home', ['block' => true]);
?>
<?php if (empty($isLocalEnv)): ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<main class="kx-home kx-page" id="kx-page">

	<section class="kx-page-hero" aria-label="Contact KodexCC">
		<div class="kx-page-hero__media" aria-hidden="true">
			<img src="<?php echo SITEURL; ?>wp-content/uploads/about-us.jpg" alt="" class="kx-page-hero__img" fetchpriority="high">
			<div class="kx-page-hero__veil"></div>
		</div>
		<div class="kx-page-hero__inner">
			<p class="kx-hero__kicker">Contact</p>
			<h1 class="kx-page-hero__title">Start a confidential manufacturing partnership.</h1>
			<p class="kx-page-hero__lede">
				Tell us about your private label requirements. We respond with a discreet, technical conversation — never a public pitch.
			</p>
		</div>
	</section>

	<section class="kx-section kx-contact-page">
		<div class="kx-wrap kx-contact-page__grid">
			<aside class="kx-contact-card">
				<p class="kx-eyebrow">Partnership Enquiries</p>
				<h2 class="kx-h2 kx-h2--sm">Speak with our team</h2>
				<p class="kx-body">
					For private label programmes, custom formulation, and confidential supply discussions.
				</p>
				<ul class="kx-contact-card__list">
					<li>
						<span class="kx-contact-card__label">Email</span>
						<a href="mailto:sales@kodexcc.com">sales@kodexcc.com</a>
					</li>
					<li>
						<span class="kx-contact-card__label">Phone</span>
						<a href="tel:1800418495">1800 418 495</a>
					</li>
					<li>
						<span class="kx-contact-card__label">Hours</span>
						<span>Monday–Friday, 7am–5pm<br>Weekends &amp; public holidays closed</span>
					</li>
				</ul>
				<p class="kx-contact-card__note">
					We do not publish client identities. All partnership discussions are treated as confidential.
				</p>
			</aside>

			<div class="kx-contact-form-wrap" id="contact-form-sec">
				<p class="kx-eyebrow">Enquiry Form</p>
				<h2 class="kx-h2 kx-h2--sm">Send a confidential message</h2>
				<?php echo $this->Flash->render(); ?>
				<form action="" method="post" class="kx-form" novalidate="novalidate" id="contact">
					<div class="kx-form__grid">
						<div class="kx-form__field">
							<label for="kx-name">First Name*</label>
							<input type="text" id="kx-name" name="name" value="" size="40" required placeholder="First name" class="kx-input">
						</div>
						<div class="kx-form__field">
							<label for="kx-lname">Last Name*</label>
							<input type="text" id="kx-lname" name="lname" value="" size="40" required placeholder="Last name" class="kx-input">
						</div>
						<div class="kx-form__field">
							<label for="kx-email">Email*</label>
							<input type="email" id="kx-email" name="email" value="" size="40" required placeholder="Email address" class="kx-input">
						</div>
						<div class="kx-form__field">
							<label for="kx-phone">Phone*</label>
							<input type="text" id="kx-phone" name="phone" value="" size="40" required placeholder="Phone number" class="kx-input">
						</div>
						<div class="kx-form__field kx-form__field--full">
							<label for="kx-message">Message*</label>
							<textarea id="kx-message" name="message" cols="40" rows="6" required placeholder="Tell us about your manufacturing requirements" class="kx-input"></textarea>
						</div>
					</div>

					<?php if (empty($isLocalEnv)): ?>
					<div class="kx-form__captcha g-recaptcha" data-sitekey="6LdQkrYjAAAAAPyq63vn5KcqySpy8dqw44YCOXZE"></div>
					<?php else: ?>
					<p class="kx-form__local">reCAPTCHA bypassed on localhost</p>
					<?php endif; ?>

					<div class="kx-form__footer">
						<button type="submit" class="kx-btn kx-btn--primary">Submit Enquiry</button>
						<span class="kx-form__note">* Mandatory fields</span>
					</div>
				</form>
			</div>
		</div>
	</section>

</main>

<?php if (empty($isLocalEnv)): ?>
<button class="g-recaptcha"
	data-sitekey="6Ld-gbYjAAAAACPg08LcAM7Wrmi-erC7gApe-1K6"
	data-callback="onSubmit"
	data-action="submit"
	style="visibility:hidden;"></button>
<?php endif; ?>
<script>
jQuery(function ($) {
	$('#contact').validate({
		rules: {
			name: { required: true },
			lname: { required: true },
			email: { required: true },
			phone: { required: true },
			message: { required: true }
		},
		messages: {
			name: { required: "Please enter name" },
			lname: { required: "Please enter last name" },
			phone: { required: "Please enter phone" },
			email: { required: "Please enter email" },
			message: { required: "Please enter message" }
		},
		invalidHandler: function (form, validator) {
			if (!validator.numberOfInvalids()) {
				return;
			}
			validator.errorList[0].element.focus();
		},
		unhighlight: function (element) {
			$(element).parent().removeClass('error');
		},
		submitHandler: function () {
			return true;
		}
	});
});
</script>
