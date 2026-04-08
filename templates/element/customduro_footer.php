<footer>
    <div class="footer_section col-lg-12 col-md-12 col-sm-12">
	    <div class="row">
	     <div class="footer_about col-lg-3 col-md-3 col-sm-3">
		      <img src="<?php echo SITEURL ;?>customdurotech/images/durotech_logo.png">
			  <h6>Kodex is a leading and Innovative construction chemical products manufacturer for the Commercial and Residential Industries. With its second global manufacturing & distribution plant located in North India with its State of Art mother plant in Australia operating for 41 years.</h6>	 
		 </div>
		 
		 <div class="footer_company col-lg-3 col-md-3 col-sm-3">
		      <h4 style="color:#fff;">Our Company</h4>
			  <ul>
			      <li><a href="<?php echo SITEURL ;?>">Case studies</a></li>
				  <li><a href="<?php echo SITEURL ;?>">Terms & Conditions of Use</li>
				  <!--li><a href="<?php echo SITEURL ;?>">Privacy Policy</li-->
				  <!--li><a href="<?php echo SITEURL ;?>specifiers">Specifications</li-->
				  <li><a href="<?php echo SITEURL ;?>about">Our Mission</li>
				  <li><a href="<?php echo SITEURL ;?>video">Video Gallery</li>
				  <!--li><a href="<?php echo SITEURL ;?>durotech-institute-of-waterproofing">Durotech Institute of Waterproofing</li-->
				  <!--li><a href="<?php echo SITEURL ;?>">Jobs</li-->
		      </ul>
		 </div>
		 
		 <div class="footer_links col-lg-3 col-md-3 col-sm-3">
		     <h4  style="color:#fff;">Quick Links</h4>
			 <ul>
			      <!--li><a href="<?php echo SITEURL ;?>wp-content/uploads/Durotech_Credit_Application_Form.pdf">Credit Application Form</li>
				  <li><a href="<?php echo SITEURL ;?>wp-content/uploads/Product_warranty_request_form.pdf">Product warranty request form</li>
				  <li><a href="<?php echo SITEURL ;?>products/index/duromastic-waterbased-membranes">Water Based Membranes</li>
				  <li><a href="<?php echo SITEURL ;?>products/index/duroproof-solvent-based-membranes">Solvent based membranes</li>
				  <li><a href="<?php echo SITEURL ;?>products/index/duroprime-primers">Duroprime Primers</li>
				  <li><a href="<?php echo SITEURL ;?>flake">DuroFlake</li-->
				  <li><a href="<?php echo SITEURL ;?>faqs">FAQ</li>
				  <li><a href="<?php echo SITEURL ;?>contact">contact</li>
		      </ul>
		 
		 
		 </div>
		 
		 <div class="footer_info col-lg-3 col-md-3 col-sm-3">
		     <h4  style="color:#fff;">Contact Information</h4>
			 <ul>
			      <li><i class="fa fa-map-marker" aria-hidden="true"></i><a href="<?php echo SITEURL.'contact' ;?>"><?php echo ADDRESS;  ?></a></li>
				  <li><i class="fa fa-phone" aria-hidden="true"></i><a href="<?php echo SITEURL.'contact' ;?>"><?php echo PHONE;  ?></a></li>
				  <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="<?php echo SITEURL.'contact' ;?>"><?php echo MAILTO;  ?></a></li>
		      </ul>
			  
		    <form id="formemailnews">
		    <div class="form-group">
				<input type="email" class="form-control" id="emailnews" placeholder="Subscribe">
				 <h6 id="successmesg"></h6>
				
			</div>
			<div class="button-4">
				<div class="eff-4"></div>
				<a onclick="subscribe()"> SEND </a>
			</div>
			</form>
			<div class="row">
					 
			</div>
		 </div>
         </div>
    </div>
	<div class="footer_bottom col-lg-12 col-md-12 col-sm-12">
	    <a href="<?php echo SITEURL ?>">kgcc.com.au</a> © 2017-2019. All Rights Reserved
	</div>
</footer>
<script>
	function subscribe(){	
		var emailnews = $("#emailnews").val();		
		if(emailnews != ''){
			
			$.ajax({url: "<?php echo SITEURL.'fronts/subscribe?email='?>"+emailnews, success: function(result){
				$("#emailnews").val('');
				$("#successmesg").html(result);
				setTimeout(function(){ $("#successmesg").html('');}, 5000);
			}});
			
		}else{
			$("#formemailnews").submit();
		}	
	}

	jQuery(function(){ //short for $(document).ready(function(){
	$("#emailnews").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter email"
	});jQuery("#emailnews").validate({
		expression: "if (VAL.match(\/^([a-zA-Z0-9_\\.\\-])+\\@(([a-zA-Z0-9\\-])+\\.)+([a-zA-Z0-9]{2,4})+$\/) && VAL) return true; else return false;",
		message: "Please enter valid email"
	});
	});

	</script>		