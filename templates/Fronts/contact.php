<?php if (empty($isLocalEnv)): ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>
<main class="page-body page-body-16">
<section class="vc-row vc-row-o bg-img page-banner" style="background-image:url(<?php echo SITEURL ;?>wp-content/uploads/2021/06/contact-us-main-banner.jpg);">
	<div class="vc-col vc-col-o banner-content ctn ctn-lg" >	
		<h1 class="hd " >We formulate advanced technologies to produce high quality products and solutions so contact us</h1>
	</div>
</section>
<section class="vc-row vc-row-o txt-center txt-block" >
	
	<div class="vc-col vc-col-o sec-gap bg-grey" >
		<div class="vc-row vc-row-i ctn" >
			<div class="vc-col vc-col-i " >	
			<h2 class="hd txt-purple p" >Visit a Technical Advisory Centre</h2>
			<div  class="txt-content p" >
				<p>Visit the team at an Kodex Technical Advisory Centre &#8211; a hub for the demonstration of innovation and collaboration that showcases the versatility, performance, and ease-of-use of our flagship products.</p>
			</div>
			</div>
		</div>
	</div>
</section>

<section class="vc-row vc-row-o bg-grey office-info-map" >
	<div class="vc-col vc-col-o ctn offices" >
		<div class="vc-row vc-row-i office-info sec-gap active" data-office="sydeny">
			<!--div class="vc-col vc-col-i col col-l col-map" >
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3306.9105272789425!2d150.83978067554153!3d-34.02050752693224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12ec14bbd88861%3A0x62b85cb8c8bfc3ac!2s14%20Essex%20St%2C%20Minto%20NSW%202566%2C%20Australia!5e0!3m2!1sen!2sin!4v1672289735490!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div-->
			<div class="vc-col vc-col-i col col-r col-info txt-block" >	
				<!--h2 class="hd p txt-purple" >New South Wales</h2-->
				<div  class="txt-content contact-info" >
					<!--p><strong class="txt-green">Address:</strong><br />
					14 Essex Street Minto NSW 2566 Australia</p-->
					 
					<p><strong class="txt-green">Email:</strong><br />
					<a href="mailto:info@kodexcc.com">sales@kodexcc.com</a></p>
					<p><strong class="txt-green">Phone:</strong><br />
					<a href="tel:1800418495">1800 418 495</a></p>
				</div>
				<div  class="txt-content opening-hours" >
					<p><strong class="txt-green">Opening Hours:</strong><br />
					Monday-Friday: 7am-5pm<br />
					Saturday-Sunday: Closed<br />
					Public Holidays: Closed</p>
				</div>
			</div>
			<div class="vc-col vc-col-i col col-r col-info txt-block" >	
				<!--h2 class="hd p txt-purple" >Approved Distributor:</h2>
				<div  class="txt-content contact-info" >				 
										
					<p><strong class="txt-green">Name: </strong> Tradies Only</p> 
					<p><strong class="txt-green">Address:</strong>Unit 12, 15-17 Gartmore Avenue Bankstown 2200 </p> 
					<p><strong class="txt-green">Phone:</strong><a href="tel:0412291021"> 0412 291 021 </a></p>
				</div>
				
			</div-->
		</div>			
	</div>
</section>
<section id="contact-form-sec" class="vc-row vc-row-o sec-gap" >
	<div class="vc-col vc-col-o ctn txt-block" >	
		<h2 class="hd txt-green hd-sm p" >Email Enquiries</h2>
			<div role="form" class="wpcf7" id="wpcf7-f50-p20-o1" lang="en-AU" dir="ltr">
			<div class="screen-reader-response"><p role="status" aria-live="polite" aria-atomic="true"></p> <ul></ul></div>
			<?php echo $this->Flash->render(); ?>
			<form action="" method="post" class="wpcf7-form init" novalidate="novalidate" data-status="init" id="contact">				
			<div class="contact-form">
				<div class="form-body">
					<div class="col col-l">
						<div class="field-group fname"><span class="wpcf7-form-control-wrap your-fname"><input type="text" name="name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="First Name*" /></span></div>
						<div class="field-group lname"><span class="wpcf7-form-control-wrap your-lname"><input type="text" name="lname" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Last Name*" /></span></div>
						<div class="field-group email"><span class="wpcf7-form-control-wrap your-email"><input type="email" name="email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="Email Address*" /></span></div>
						<div class="field-group phone"><span class="wpcf7-form-control-wrap your-phone"><input type="text" name="phone" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Phone Number*" /></span></div>						
					</div>
					<div class="col col-r">
						<div class="field-group message"><span class="wpcf7-form-control-wrap your-message"><textarea name="message" cols="40" rows="6" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" placeholder="Message"></textarea></span></div>
					</div>
				</div>
				
				<?php if (empty($isLocalEnv)): ?>
				<div class="g-recaptcha" data-sitekey="6LdQkrYjAAAAAPyq63vn5KcqySpy8dqw44YCOXZE"></div>
				<?php else: ?>
				<div style="margin:8px 0;color:#555;">reCAPTCHA bypassed on localhost</div>
				<?php endif; ?>
				<footer class="form-footer">
					<div class="col col-l">						
						<div class="field-group submit">
							<button type="submit" class="wpcf7-form-control wpcf7-submit btn btn2 hover6 hover2">Submit</button>							
						</div>
					</div>
					<div class="col col-r"><span class="form-note">* Mandatory Field</span></div>
				</footer>
			</div>
			<div class="wpcf7-response-output" aria-hidden="true"></div>
			</form>
		</div>
	</div>
</section>
</main><!-- .page-body -->
<?php if (empty($isLocalEnv)): ?>
<button class="g-recaptcha" 
				data-sitekey="6Ld-gbYjAAAAACPg08LcAM7Wrmi-erC7gApe-1K6" 
				data-callback='onSubmit' 
				data-action='submit' style="visibility:hidden;"></button>
<?php endif; ?>
<script>
	
	$(document).ready(function()
	{ 	
		$('#contact').validate({
		rules: {
			name: {
				required: true,  			
			},
			lname: {
				required: true,  			
			},
			email: {
				required: true,		
			},
			phone: {
				required: true,		
			},
			message: {
				required: true,		
			}
		},
		messages: {
			name: {
				required: "Please enter name",		
			},
			lname: {
				required: "Please enter last name",		
			},
			msg_subject: {
				required: "Please enter subject name",			
			},
			phone: {
				required: "Please enter phone",			
			},
			email: {
				required: "Please enter email",			
			},
			message: {
				required: "Please enter message",			
			}
		},
		invalidHandler: function(form, validator) {
		  if (!validator.numberOfInvalids())
		  {
			return;
		  }
		  else
		  {
			var error_element=validator.errorList[0].element;
			error_element.focus();
		  }
		},
		highlight: function (element) {
		 
		},
		unhighlight: function (element) {
		  $(element).parent().removeClass('error')
		},
		submitHandler: function(form) 
		{
			return true;
		}
		}); 
	}); 
	
	</script>