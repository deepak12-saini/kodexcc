<div class="stricky-header stricked-menu main-menu">
	<div class="sticky-header__content"></div><!-- /.sticky-header__content -->
</div><!-- /.stricky-header -->

<!--Page Header Start-->
<section class="page-header">
	<div class="page-header-bg" style="background-image: url(assets/images/backgrounds/page-header-bg.jpg)">
	</div>
	<div class="container">
		<div class="page-header__inner">
			<ul class="thm-breadcrumb list-unstyled">
				<li><a href="/">Home</a></li>
				<li><span>/</span></li>
				<li>Video</li>
			</ul>
			<h2>Video</h2>
		</div>
	</div>
</section>
<!--Page Header End-->

<section class="gallery-page">
<div class="container">
	<div class="row">
		<div class="section-title text-left">			
			<h2 class="section-title__title" style="text-align:center;">Our Video</h2>
			<div class="section-title__line"></div>
		</div>
		<div class="col-xl-4 col-lg-6 col-md-6">
			<!--Gallery Page Single-->
			<div class="gallery-page__single">
				<div class="gallery-page__img">
					<img src="<?php echo SITEURL.'img/video.jpg'?>" alt="">
					<div class="gallery-page__icon">
						<a href="https://www.youtube.com/watch?v=-GASoVo-mck&feature=youtu.be" class="video-popup">
							<div class="leading__video-icon">
								<span class="fa fa-play"></span>
								<i class="ripple"></i>
							</div>
						</a>
					</div>
					<h2 style="font-size:15px;">Roof Coat use on Healing Hospital Rooftop -  Kodex Global CC</h2>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-lg-6 col-md-6">
			<!--Gallery Page Single-->
			<div class="gallery-page__single">
				<div class="gallery-page__img">
					<img src="<?php echo SITEURL.'img/video.jpg'?>" alt="">
					<div class="gallery-page__icon">
						<a href="https://www.youtube.com/watch?v=isvyMBLTocE&feature=youtu.be" class="video-popup">
							<div class="leading__video-icon">
								<span class="fa fa-play"></span>
								<i class="ripple"></i>
							</div>
						</a>
					</div>
					<h2 style="font-size:15px;">Kodelastic Bituroof Applying Rooftop -  Kodex Global CC</h2>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-lg-6 col-md-6">
			<!--Gallery Page Single-->
			<div class="gallery-page__single">
				<div class="gallery-page__img">
					<img src="<?php echo SITEURL.'img/video.jpg'?>" alt="">
					<div class="gallery-page__icon">
						<a href="https://www.youtube.com/watch?v=PeCbpa4eqXY" class="video-popup">
							<div class="leading__video-icon">
								<span class="fa fa-play"></span>
								<i class="ripple"></i>
							</div>
						</a>
					</div>
					<h2 style="font-size:15px;">KodeCrete SBL and Kodeflex 2k Roof Waterproofing Membrane Products -  Kodex Global CC</h2>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-lg-6 col-md-6">
			<!--Gallery Page Single-->
			<div class="gallery-page__single">
				<div class="gallery-page__img">
					<img src="<?php echo SITEURL.'img/video.jpg'?>" alt="">
					<div class="gallery-page__icon">
						<a href="https://www.youtube.com/watch?v=A2rC0a_Qveg&feature=youtu.be" class="video-popup">
							<div class="leading__video-icon">
								<span class="fa fa-play"></span>
								<i class="ripple"></i>
							</div>
						</a>
					</div>
					<h2 style="font-size:15px;">Chandigarh Basement Waterproofing | Kodex Global</h2>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-lg-6 col-md-6">
			<!--Gallery Page Single-->
			<div class="gallery-page__single">
				<div class="gallery-page__img">
					<img src="<?php echo SITEURL.'img/video.jpg'?>" alt="">
					<div class="gallery-page__icon">
						<a href="https://www.youtube.com/watch?v=I8m5XqKAchw&feature=youtu.be" class="video-popup">
							<div class="leading__video-icon">
								<span class="fa fa-play"></span>
								<i class="ripple"></i>
							</div>
						</a>
					</div>
					<h2 style="font-size:15px;">Bituroof, Geotextile and Kodeflex 2k | Prefect Combination for Basement Waterproofing</h2>
				</div>
			</div>
		</div>	
	</div>
</div>
</section>

 <!--Newsletter Start-->
 <section class="newsletter">
	 <div class="container">
		 <div class="newsletter__inner wow fadeInUp" data-wow-delay="100ms">
			 <div class="newsletter-shape-1" style="background-image: url(assets/images/shapes/newsletter-shape-1.png);"></div>
			 <div class="newsletter__left">
				<h3 class="newsletter__title">Join Our Newsletter</h3>
                <p class="newsletter__text">Connect With Us</p>
			 </div>
			 <div class="newsletter__right">
				<form class="newsletter__form">
					<div class="newsletter__input-box">
						<input type="email" placeholder="Enter your email" name="email"  name="nw_mail" id="nw_mail">
						<a href="#null" class="thm-btn newsletter__btn" onclick="return sendmail()">Subscribe</a>
					</div>
				</form>
			 </div>
		 </div>
	 </div>
 </section>
 <!--Newsletter End-->

<script>
function sendmail(){
	//var formData = new FormData($(this)[0]);
	var mail = $('#nw_mail').val();			
	if(mail != ''){
	
		$.ajax({
			url: "https://kodexglobalcc.com/fronts/subscribe?email="+mail,  
			success: function(result){
				$("#nw_mail").val('');					
				if(result != ''){
					swal("Email Subscribed!", "Your email updated to our database.", "success");						
				} else {
					swal("Already Exists!", "Your email already found in our database.", "warning");
				}
			
			}
		});	
	}else{
		swal("Warning", "Your email is not empty.", "warning");
	}	
}
</script>