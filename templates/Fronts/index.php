<style>
@keyframes slidy {
0% { left: 0%; }
20% { left: 0%; }
25% { left: -100%; }
45% { left: -100%; }
50% { left: -200%; }
70% { left: -200%; }
75% { left: -300%; }
90% { left: -300%; }
95% { left: -400%; }
100% { left: -400%; }
}

body { margin: 0; } 
div#slider { overflow: hidden; }
div#slider figure img { width: 20%; float: left; }
div#slider figure { 
  position: relative;
  width: 500%;
  margin: 0;
  left: 0;
  text-align: left;
  font-size: 0;
  animation: 20s slidy infinite; 
}
.project h3 {
	text-align: center;
	font-size: 30px;
	color: #000;
}


.app_new_product img {
	float: left;
	width: 115px;
}
.app_new_product h3 {
	font-size: 22px;
	font-weight: bold;
	color: #2889c9;
}
	.app_new_product {
	padding: 50px 0px;
}
.app_new_product_content p {

font-size: 18px;
letter-spacing: 0.05em;
text-align: justify;
}
.mobile_section {
	text-align: center;
}
.mobile_section img {
	width: 260px;
}
.app_new_product_img {
	float: left;
	margin-right: 23px;
}
.app_new_product_img {
	float: left;
	margin-right: 23px;
	height: auto;
	padding-bottom: 100px;
}
.mobile_app_section h2 {
	text-align: center;
	font-size: 47px;
	border-bottom: none;
}
.mobile_app_section_top_content p {
	text-align: center;
	font-size: 17px;
	letter-spacing: 0.05em;
	color: #828282;
	padding-bottom: 50px;
}
.work_section_img {
	text-align: center;
	padding: 7% 0px;
}
	
	</style>
<div class="banner col-lg-12 col-md-12 col-sm-12">
	 <!--img src="<?php echo SITEURL ;?>customdurotech/images/banner_1.jpg"-->
	<div id="sliders">
	<figure>
		<img src="<?php echo SITEURL ;?>customdurotech/images/slider/banner-5.jpg" alt>
	</figure>
	</div>
</div>

	<div class="content col-lg-12 col-md-12 col-sm-12">
		 
		 <p style="text-align:center;">Kodex is a leading Australian manufacturer and supplier of Innovative chemical construction products to the Commercial and Residential Industries. We provide reliable solutions which offer unmatched protective advantages that can be applied to both new construction and remedial buildings. Kodex is now proud to announce its Indian expansion. Our expertise lies in delivering market leading products which provide uncompromising quality and durability.</p>
		 
	</div>
	
	
	
		<!--div class="duro_service col-lg-12 col-md-12 col-sm-12">
		
		      <div class="container_service">
			       <div class="card slide-up col-lg-4 col-md-4 col-sm-4" id="duroprodcut">
					  <div class='image'>
					      <img src="<?php echo SITEURL ;?>customdurotech/images/duro_products_clr.png">
						  <h3>Duro Products</h3>
						  <a href="<?php echo SITEURL.'products'?>">View More</a>
					  </div>
					  <div class='caption'>
						<img src="<?php echo SITEURL ;?>customdurotech/images/duro_products.png">
						  <h3>Duro Products</h3>
						  <a href="<?php echo SITEURL.'products'?>">View More</a>
					  </div>
					</div>
					
				    <div class="card slide-up col-lg-4 col-md-4 col-sm-4">
					  <div class='image'>
					      <img src="<?php echo SITEURL ;?>customdurotech/images/technical_literature_clr.png">
						  <h3>Technical Literature</h3>
						  <a href="<?php echo SITEURL.'technical-literature' ?>">View More</a>
					  </div>
					  <div class='caption'>
						<img src="<?php echo SITEURL ;?>customdurotech/images/technical_literature.png">
						  <h3>Technical Literature</h3>
						  <a href="<?php echo SITEURL.'technical-literature' ?>">View More</a>
					  </div>
					</div>
					
				     <div class="card slide-up col-lg-4 col-md-4 col-sm-4">
					  <div class='image'>
					      <img src="<?php echo SITEURL ;?>customdurotech/images/specifiers_clr.png">
						  <h3>Durotech Specifiers</h3>
						  <a href="<?php echo SITEURL.'specifiers' ?>">View More</a>
					  </div>
					  <div class='caption'>
						<img src="<?php echo SITEURL ;?>customdurotech/images/specifiers.png">
						  <h3>Durotech Specifiers</h3>
						  <a href="<?php echo SITEURL.'specifiers' ?>">View More</a>
					  </div>
					</div>
					
					<div class="card slide-up col-lg-4 col-md-4 col-sm-4">
					  <div class='image'>
					      <img src="<?php echo SITEURL ;?>customdurotech/images/Durolab_clr.png">
						  <h3>DuroLab</h3>
						  <a href="<?php echo SITEURL ?>durolab">View More</a>
					  </div>
					  <div class='caption'>
						 <img src="<?php echo SITEURL ;?>customdurotech/images/Durolab.png">
						  <h3>DuroLab</h3>
						  <a href="<?php echo SITEURL ?>durolab">View More</a>
					  </div>
					</div>
					
					<div class="card slide-up col-lg-4 col-md-4 col-sm-4">
					  <div class='image'>
					      <img src="<?php echo SITEURL ;?>customdurotech/images/durotoll_clr.png">
						  <h3>DuroToll</h3>
						  <a href="<?php echo SITEURL ?>durooem">View More</a>
					  </div>
					  <div class='caption'>
						<img src="<?php echo SITEURL ;?>customdurotech/images/durotoll.png">
						  <h3>DuroToll</h3>
						  <a href="<?php echo SITEURL ?>durooem">View More</a>
					  </div>
					</div>
					
					<div class="card slide-up col-lg-4 col-md-4 col-sm-4">
					  <div class='image'>
					      <img src="<?php echo SITEURL ;?>customdurotech/images/duroshop_clr.png">
						  <h3>Duro Store</h3>
						  <a href="https://duroshop.com" target="_blank">View More</a>
					  </div>
					  <div class='caption'>
						<img src="<?php echo SITEURL ;?>customdurotech/images/duroshop.png">
						  <h3>Duro Store</h3>
						  <a href="https://duroshop.com"  target="_blank">View More</a>
					  </div>
					</div>
			  
			  
			  </div>
		
		</div-->
		
		<div class="work_with col-lg-12 col-md-12 col-sm-12">
		    <div class="row">
		      <h3>What We Offer</h3>
			  <p>Kodex  is a leading manufacturer and supplier of waterproofing products. </p>
			  <div class="work_section col-lg-12 col-md-12 col-sm-12">
			         <div class="container">
			         <div class="work_section_content col-lg-6 col-md-6 col-sm-6">
					      <!-- <img src="<?php echo SITEURL ;?>customdurotech/images/globe_new123.gif"> -->
						   <div class="work_section_content_1">
							  <i class="fa fa-search" aria-hidden="true"></i>
							  <h4>RESEARCH & DEVELOPMENT</h4>
							  <p>Research and development (R&D) for waterproofing involves the exploration, experimentation, and innovation of new materials, technologies, and techniques to enhance the water resistance of various products, surfaces, and structures</p>
						  </div>
						  <div class="work_section_content_1">
							  <i class="fa fa-industry" aria-hidden="true"></i>
							  <h4>GLOBAL MANUFACTERING</h4>
							  <p>With multiple manufacturing plants in both Australia and Inda Kodex global is able to cater to any part of the world. From small high performance products to large cost-effective solutions.</p>
						  </div>
					 </div>
					 <div class="work_section_content col-lg-6 col-md-6 col-sm-6">
					     
						  <div class="work_section_content_1">
							  <i class="fa fa-headphones" aria-hidden="true"></i>
							  <h4>SERVICE & SUPPORT </h4>
							  <p>We pride ourselves not only on the quality of our products but also the comprehensive support available to our customers and clients guaranteeing full-service satisfaction.</p>
						  </div>
						  <div class="work_section_content_1">
							  <i class="fa fa-shopping-cart" aria-hidden="true"></i>
							  <h4>GLOBAL PRESENCE</h4>
							  <p>Having Global offices In areas such as India and Australia Kodex global is better able to serve and support the growing local and international customers.</p>
						  </div>
					 </div>
					 </div>
			  </div>
			  </div>
		</div>
		<div class="counter col-lg-12 col-md-12 col-sm-12">
		     <div id="projectFacts" class="sectionClass">
				<div class="fullWidth eight columns">
					<div class="projectFactsWrap ">
						<div class="item wow fadeInUpBig animated animated" data-number="12" style="visibility: visible;">
							<i class="fa fa-building" aria-hidden="true"></i>
							<p id="number1" class="number">10,000</p>
							<span></span>
							<p>Project Completed</p>
						</div>
						<div class="item wow fadeInUpBig animated animated" data-number="55" style="visibility: visible;">
							<i class="fa fa-industry"></i>
							<p id="number2" class="number">205</p>
							<span></span>
							<p>Products Made</p>
						</div>
						<div class="item wow fadeInUpBig animated animated" data-number="359" style="visibility: visible;">
							<i class="fa fa-user"></i>
							<p id="number3" class="number">60</p>
							<span></span>
							<p>Global Staff</p>
						</div>
						<div class="item wow fadeInUpBig animated animated" data-number="246" style="visibility: visible;">
							<i class="fa fa-briefcase"></i>
							<p id="number4" class="number">40</p>
							<span></span>
							<p>Years of Business</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="client_logo col-lg-12 col-md-12 col-sm-12">		
			<div class="container">
				<h3>We Work With</h3>
				<p>Renowned national and multinational companies whom we work with.</p>
				<section class="customer-logos slider">
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_1.png"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_2.png"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_3.png"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_4.png"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_5.png"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_6.png"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_7.png"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_8.png"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>customdurotech/images/client_icon_9.png"></div>
				</section>
			</div>
		</div>
		
		<!--div class="client_logo col-lg-12 col-md-12 col-sm-12">		
			<div class="container">
				<div class="Accreditations col-lg-6 col-md-6 col-sm-6">
				<h3>Accreditations</h3>
			   <section class="customer-logos slider">
				 
				  <div class="slide"><img src="<?php echo SITEURL ;?>img/AIW_logo_new.jpg"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>img/australia_builder_new.jpg"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>img/HIA Master Builders_logo_new.jpg"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>img/netspec_logo_new.jpg"></div>
				  <div class="slide"><img src="<?php echo SITEURL ;?>img/refuel_new.jpg"></div>
				   <div class="slide"><img src="<?php echo SITEURL ;?>img/australia_builder_new.jpg"></div>
				   <div class="slide"><img src="<?php echo SITEURL ;?>img/HIA Master Builders_logo_new.jpg"></div>
			   </section>
			   </div>
			   
				<div class="Accreditations col-lg-6 col-md-6 col-sm-6">
				<h3>Strategic Alliances</h3>
			   <section class="customer-logos slider">
					<div class="slide"><img src="<?php echo SITEURL ;?>img/dow_new.jpg"></div>
					<div class="slide"><img src="<?php echo SITEURL ;?>img/graco_new.jpg"></div>
					<div class="slide"><img src="<?php echo SITEURL ;?>img/HB Fuller_new.jpg"></div>
					<div class="slide"><img src="<?php echo SITEURL ;?>img/dow_new.jpg"></div>
					<div class="slide"><img src="<?php echo SITEURL ;?>img/graco_new.jpg"></div>
					<div class="slide"><img src="<?php echo SITEURL ;?>img/HB Fuller_new.jpg"></div>
					<div class="slide"><img src="<?php echo SITEURL ;?>img/graco_new.jpg"></div>
			   </section>
			   </div>		   
			</div>
		</div-->
		<!--div class="mobile_app_section col-lg-12 col-md-12 col-s-12">
	      <div class="container">
		  <div class="mobile_app_section_top_content">
		  <h2>Our Hi-Tech Mobile App </h2>
		  <p>With an Australian wide community that is connected to Durotech for over 40 years, we wish to make the user experience simplified and convenient. Download our latest mobile App to know about latest products and waterproofing solutions from Durotech Industries. </p>
          </div>
	      <div class="mobile_app_section_1 col-lg-4 col-md-4 col-sm-4">
		       <div class="app_new_product">
				   <div class="app_new_product_img"><img src="<?php echo SITEURL ;?>customdurotech/images/product_icon.png"></div>
				   <div class="app_new_product_content"><h3>New Products</h3>
				      <p>Get latest new about new product additions and project updates</p>
				   </div>
			   </div>
			   
			   <div class="app_new_product">
				   <div class="app_new_product_img"><img src="<?php echo SITEURL ;?>customdurotech/images/app_QR_icon.png"></div>
				   <div class="app_new_product_content"><h3>Scan QR Code Feature</h3>
				      <p>Use our exclusive product QR code scanner to get information on any product</p>
				   </div>
			   </div>
          </div>
		  <div class="mobile_section col-lg-4 col-md-4 col-sm-4">
		       <img src="<?php echo SITEURL ;?>customdurotech/images/mobile_app_img.png">
		  </div>
		  <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="app_new_product">
				   <div class="app_new_product_img"><img src="<?php echo SITEURL ;?>customdurotech/images/check_icon.png"></div>
				   <div class="app_new_product_content"><h3>Technical Datasheets</h3>
				      <p>Get complete access to product datasheets and MSDS docs</p>
				   </div>
			   </div>
			   
			   <div class="app_new_product">
				   <div class="app_new_product_img"><img src="<?php echo SITEURL ;?>customdurotech/images/vcard_icon.png"></div>
				   <div class="app_new_product_content"><h3>Exclusive vcard feature</h3>
				      <p>Creare your personal profile on the APP and generate a personal QR code linked to your contact information</p>
				   </div>
			   </div>

          </div>
    </div>
    </div-->
		
		<?php
		/*
			$data = file_get_contents('http://lightwidget.com/widgets/6b59d0e0b09e59d1bbfadd63c7a3ed43.html');
			$datas = htmlspecialchars($data);
			preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $datas, $match);
			$images  = array_chunk($match[0],5);
		?>
		<?php 	if(!empty($images)){ ?>
		<div class="project col-lg-12 col-md-12 col-sm-12">
		<h3>Durotech Projects </h3>
	    <div class="row">
		
			<?php 
			 
				foreach($images as $img){					
				$linknew = str_replace('"','',$img[0]);
				$linknew = str_replace('&quot','',$linknew); 				
			?>
			
			 <div class="card">
				  <div class="content">
					<div class="front">
					  <a href="<?php echo $linknew ;?>" target="_blank"><img src="<?php echo $img[1] ;?>" style="width:100%"></a>
					</div>
					<div class="back">
					  <a href="<?php echo $linknew ;?>"  target="_blank"><img src="<?php echo $img[1] ;?>" style="width:100%"></a>
					</div>
				  </div>
			 </div>
			 <?php } ?>
		</div>
		</div>
		<?php }else{ */ ?>
		<div class="project col-lg-12 col-md-12 col-sm-12">
	    <div class="row">
			 <div class="card">
				  <div class="content">
					<div class="front">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_1.jpg"></a>
					</div>
					<div class="back">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_1.jpg"></a>
					</div>
				  </div>
			 </div>
			 <div class="card">
				  <div class="content">
					<div class="front">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_2.jpg"></a>
					</div>
					<div class="back">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_2.jpg"></a>
					</div>
				  </div>
			 </div>
			 <div class="card">
				  <div class="content">
					<div class="front">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_3.jpg"></a>
					</div>
					<div class="back">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_3.jpg"></a>
					</div>
				  </div>
			 </div>
			 <div class="card">
				  <div class="content">
					<div class="front">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_4.jpg"></a>
					</div>
					<div class="back">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_4.jpg"></a>
					</div>
				  </div>
			 </div>
			 <div class="card">
				  <div class="content">
					<div class="front">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_5.jpg"></a>
					</div>
					<div class="back">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_5.jpg"></a>
					</div>
				  </div>
			 </div>
			 <div class="card">
				  <div class="content">
					<div class="front">
					   <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_6.jpg"></a>
					</div>
					<div class="back">
					  <a href="<?php echo SITEURL ;?>technical-literature/project"><img src="<?php echo SITEURL ;?>customdurotech/images/project_6.jpg"></a>
					</div>
				  </div>
			 </div>
			 </div>

			</div>
			<?php //} ?>
		<script>
		$(document).ready(function(){
			$('.customer-logos').slick({
				slidesToShow: 6,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 1500,
				arrows: false,
				dots: false,
				pauseOnHover: false,
				responsive: [{
					breakpoint: 768,
					settings: {
						slidesToShow: 4
					}
				}, {
					breakpoint: 520,
					settings: {
						slidesToShow: 3
					}
				}]
			});
			$("#duroprodcut").click(function() {
				window.location.href = '<?php echo SITEURL.'products'?>';
			});
			
		});
		</script>