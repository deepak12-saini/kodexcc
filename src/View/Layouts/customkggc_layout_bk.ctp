<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

?>
<!DOCTYPE html>
<html>
<head>

	<?php echo $this->Html->charset(); ?>
	
	<title><?php  if(isset($meta_title)){ echo $meta_title; } else{ echo  META_TITLE; }?></title>
	<meta name="google-site-verification" content="APCO-gVPMSiSKXaAU7YnymUEU4K1kjEuRXmwQtnEK9c" />
	<meta name="msvalidate.01" content="18C63E8840CE8756F641A3CA6F76199D" />
	<meta name="p:domain_verify" content="cf442fce7055d63ebed36b98457ca2d6"/>
	<meta name="google-site-verification" content="QCvoWzEuPFTmfexEobbdCgq4HgjOGVFwGTfhmN45NuM" />
	<meta name="trustpilot-one-time-domain-verification-id" content="a025cf52-f07f-48ac-9464-a2e7dda87f0a"/>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5LKKRHG');</script>
	<!-- End Google Tag Manager -->

	<meta name="description" content="<?php  if(isset($meta_description)){ echo $meta_description; } else{ echo  META_DESCRIPTION; }?>" />
	<meta name="keywords" content="<?php  if(isset($meta_keyword)){ echo $meta_keyword; } else{ echo  META_KEYWORD; }?>">
	
    <meta name="viewport" content="width=device-width,initial-scale=1" />
	<?php
	$actualurl =  "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	?>
	<link href="<?php echo $actualurl; ?>" rel="canonical">
	<link href="<?php echo SITEURL ;?>favicon.png" type="image/x-icon" rel="icon" /><link href="<?php echo SITEURL ;?>favicon.png" type="image/x-icon" rel="shortcut icon" />	
	   <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"  rel="stylesheet">
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/animate/animate.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/animate/custom-animate.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/fontawesome/css/all.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/jarallax/jarallax.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/nouislider/nouislider.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/nouislider/nouislider.pips.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/odometer/odometer.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/swiper/swiper.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/ambed-icons/style.css">
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/tiny-slider/tiny-slider.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/reey-font/stylesheet.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/owl-carousel/owl.carousel.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/owl-carousel/owl.theme.default.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/bxslider/jquery.bxslider.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/bootstrap-select/css/bootstrap-select.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/vegas/vegas.min.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/jquery-ui/jquery-ui.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/vendors/timepicker/timePicker.css" />
	<!-- template styles -->
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/css/ambed.css" />
	<link rel="stylesheet" href="<?php echo SITEURL ;?>kd/assets/css/ambed-responsive.css" />

	<link href="<?php echo SITEURL ;?>public/backend/vendors/bower_components/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
	<script src="<?php echo SITEURL ;?>public/frontend/vendor/jquery-3.2.1.min.js" type="text/javascript"></script>
	<script src="<?php echo SITEURL ;?>theme/js/jquery.validate.js"></script>

	<style>
	.our-process2__item {
	  padding: 110px 40px;
	}
	h4.pro-title {
	  font-size: 21px;
	}
	
	.ValidationErrors{
		color: #d00;
		display: inline-block;
		font-size: 12px;
		font-style: italic;
		padding-left: 10px;
	}
	.floating-wpp {		
		z-index: 999999 !important;
	}
	</style>
	<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Kodex Global Construction Private Limited",
  "image": "https://kodexglobalcc.com/public/frontend/img/logo.png",
  "@id": "",
  "url": "https://kodexglobalcc.com/",
  "telephone": "+91-172 5096688",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "E-121, Industrial Area, Sector 73",
    "addressLocality": "Mohali",
    "postalCode": "140308",
    "addressCountry": "IN"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 30.717869,
    "longitude": 76.70235439999999
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday"
    ],
    "opens": "09:00",
    "closes": "17:00"
  },
  "sameAs": [
    "https://www.facebook.com/Kodexglobalcc/",
    "https://twitter.com/kodexglobalcc",
    "https://in.pinterest.com/kodexglobalcc/"
  ] 
}
</script>


</head>
<body>
	<div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>
	
    <div class="preloader">
        <div class="preloader__image"></div>
    </div>
	<div class="page-wrapper">
		<?php echo $this->element('customkgcc_header'); ?>
		<?php echo $this->fetch('content'); ?>
		<?php echo $this->element('customkgcc_footer'); ?>	
		<?php //echo $this->element('sql_dump'); ?>
	</div>

    <!-- =================== PLUGIN JS ==================== -->
	
	<script src="<?php echo SITEURL ;?>kd/assets/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/jarallax/jarallax.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/jquery-appear/jquery.appear.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/jquery-circle-progress/jquery.circle-progress.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/jquery-validate/jquery.validate.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/nouislider/nouislider.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/odometer/odometer.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/swiper/swiper.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/tiny-slider/tiny-slider.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/wnumb/wNumb.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/wow/wow.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/isotope/isotope.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/countdown/countdown.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/owl-carousel/owl.carousel.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/bxslider/jquery.bxslider.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/vegas/vegas.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/jquery-ui/jquery-ui.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/timepicker/timePicker.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/circleType/jquery.circleType.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/circleType/jquery.lettering.min.js"></script>
    <!-- template js -->
    <script src="<?php echo SITEURL ;?>kd/assets/js/ambed.js"></script>
    <!-- toolbar js -->
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/toolbar/js/js.cookie.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/toolbar/js/jQuery.style.switcher.min.js"></script>
    <script src="<?php echo SITEURL ;?>kd/assets/vendors/toolbar/js/toolbar.js"></script>	
    <!-- Sweet-Alert  -->
	<script src="<?php echo SITEURL ;?>public/backend/vendors/bower_components/sweetalert/dist/sweetalert.min.js"></script>
 
    <script>
		 $(document).ready(function () {
          /*   lightbox.option({
                'resizeDuration': 200,
                'wrapAround': false,
                'alwaysShowNavOnTouchDevices': true,
            }); */
        });
		function sendmail()
		{
			//var formData = new FormData($(this)[0]);
			var mail = $('#nw_mail').val();			
			if(mail != ''){
			
				$.ajax({
					url: "<?php echo SITEURL ;?>fronts/subscribe?email="+mail,  
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

		$('#owl-re-pro-2').owlCarousel({
			loop: true,
			responsiveClass: true,
			autoplay: true,
			autoplayTimeout: 8000,
			autoplayHoverPause: false,
			smartSpeed: 1000,
			nav: true,
			navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
			responsive: {
				0: {
					items: 1,
					dots: false,
					nav: false
				},
				768: {
					items: 2,
					dots: false,
					nav: false
				},
				992: {
					items: 3,
					dots: false
				},
				1024: {
					items: 3,
					dots: false
				},
				1200: {
					items: 4,
					dots: false,
					loop: true
				}
			}
		});
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-215643172-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-215643172-1');
</script>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5LKKRHG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/577f6eb641d269625348d44c/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>
