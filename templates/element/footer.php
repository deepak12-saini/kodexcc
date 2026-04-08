<div class="footer col-lg-12 col-md-12 col-sm-12">
     <div class="container">
	     <div class="footer_section_1 col-md-3">
		     <h5>About</h5>
			 <p>Durotech industries is a leading manufacturer and supplier of waterproofing products. Durotech waterproofing products have been widely used in the construction industry for the sealing of wet areas, decks, roofs and other substrates providing protection against moisture and weather damage for over 40 years.</p>
		 
	     </div>
		 <div class="footer_section_1 col-md-3">
		     <h5>Our Company</h5>
			 <ul>
			     <li><a href="http://www.durotechindustries.com.au/wp-content/uploads/Brochure/Durotech_Credit%20Application_Term_and_condition.pdf">Terms & Conditions of Use</a></li>
				 <li><a href="<?php echo SITEURL ?>#">Privacy Policy</a></li>
				 <li><a href="<?php echo SITEURL ?>#">Specifications</a></li>
				 <li><a href="<?php echo SITEURL ?>about">About</a></li>				 
			 </ul>
		 
	     </div>
		 
		  <div class="footer_section_1 col-md-3">
		     <h5>Quick Links</h5>
			 <ul>
			     <li><a href="http://www.durotechindustries.com.au/wp-content/uploads/Brochure/Durotech_Credit_Application_Form.pdf" target="_blank">Credit Application Form</a></li>
				 <li><a href="http://www.durotechindustries.com.au/wp-content/uploads/Brochure/Product_warranty_request_form.pdf"  target="_blank">Product warranty request form</a></li>				
				 <li><a href="www.durotechindustries.com.au/faq/" target="_blank">FAQ</a></li>
			 </ul>
	     </div>
		 
		 <div class="footer_section_1 col-md-3">
		     <h5>Newsletter</h5>
			 <div class="input-group">
				<form action="" method="post" onsubmit="return submit()">
					<input name="email" id="subscribe_email" class="form-control" required placeholder="email" type="email">
					<div style="color:red" id="red_email"></div>
					<a href="#null" class="newsletter_send" onclick="return submit()" >SEND</a> 
					
				</form>	
             </div>
			 <div class="footer_social_media">
			    <a href="<?php echo FACEBOOK ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
				<a href="<?php echo TWITER ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
				<a href="<?php echo GOOGLE_PLUS ?>" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
				<a href="<?php echo LINKDIN ?>" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
			 </div>
			 
	     </div>
		<div class="footer_bottom col-lg-12 col-md-12 col-sm-12">
			 <p>www.durotechindustries.com.au © 2015-2016 .All Rights Reserved</p>
		</div>		 
	 </div>
	</div>	
	<script>
		function submit(){
			var email = $("#subscribe_email").val();
			if(email == ''){
				$("#red_email").text('Email-id is required');
				setTimeout(function(){ $("#red_email").text(''); },5000);
				return false;
			}else{
				$.ajax({url: "<?php echo SITEURL.'fronts/subscribe?email='?>"+email, success: function(result){
					$("#red_email").text(result);
					setTimeout(function(){ $("#red_email").text(''); },5000);
				}});
				return true;
			}
			
			
		}
		
	</script>