<?php
header("Content-Security-Policy: frame-ancestors 'none'");
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
<?php ob_start("ob_gzhandler"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>		
	<title><?php  if(isset($meta_title)){ echo $meta_title; } else{ echo  META_TITLE; }?></title>
	<meta name="description" content="<?php  if(isset($meta_description)){ echo $meta_description; } else{ echo  META_DESCRIPTION; }?>" />
	<meta name="keywords" content="<?php  if(isset($meta_keyword)){ echo $meta_keyword; } else{ echo  META_KEYWORD; }?>">
	<link href="<?php echo SITEURL ;?>favicon.png" type="image/x-icon" rel="icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />	
	<meta property="og:title" content="<?php  if(isset($meta_description)){ echo $meta_description; } else{ echo  META_DESCRIPTION; }?>" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="<?php echo SITEURL ;?>wp-content/uploads/sheet.jpg" />
	<meta property="og:url" content="<?php echo SITEURL ;?>" />
	<link rel="canonical" href="<?php echo SITEURL ;?>" />
	<meta name="yandex-verification" content="97d468d5c089c1ed" />
	<link rel='stylesheet' href='<?php echo SITEURL ;?>wp-content/plugins/instagram-feed/css/sbi-styles.minbf9e.css?ver=2.9.3.1' type='text/css' media='all' />	
	<link rel='stylesheet' id='fontawesome-css'  href='<?php echo SITEURL ;?>wp-content/themes/enviro/tool/fa/css/all.minb683.css?ver=5.12.0' type='text/css' media='all' />	
	<link rel='stylesheet' href='<?php echo SITEURL ;?>wp-content/themes/enviro/tool/mmenujs/mmenu.css' type='text/css' media='all' />
	<link rel='stylesheet' href='<?php echo SITEURL ;?>wp-content/themes/enviro/style696e.css?ver=1636523509' type='text/css' media='all' />
	<link rel='stylesheet' href='<?php echo SITEURL ;?>wp-content/themes/enviro/css/globalb9c0.css?ver=1642475174' type='text/css' media='all' />
	<script type='text/javascript' src='<?php echo SITEURL ;?>wp-content/jquery.min9d52.js?ver=3.5.1'></script>	
	<script src="<?php echo SITEURL; ?>validator/jquery.validate.js"></script>
	<script src="<?php echo SITEURL; ?>validator/validator-init.js"></script>
	<style>
	.mm-navbar {
	  --mm-color-background: #1175b8;
	}
	.site-header .site-logo {
	  height: 100%;
	  width:120px;
	}
	
	@media (max-width: 850px)
	{
		.site-header .site-logo {
		  height: 100%;
		  width:63px;
		}
		
		.page-banner.home-banner 
		{		
			background-size: 100%;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			background-repeat: no-repeat;
			min-height: 200px;
		}
		
		.page-banner {
			min-height: 100%;
		}
		.bg-img {
			background-position: top;
			background-repeat: no-repeat;
			background-size: cover;
		}
	}

	@media (max-width: 450px)
	{
		.site-header .site-logo {
			height: 100%;
			width:63px;
			margin-left: 21px;
		}
			
		.page-banner.home-banner 
		{		
			background-size: 100%;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			background-repeat: no-repeat;
			min-height: 0px;
		}
		
		.page-banner {
			min-height: 100%;
		}
		.bg-img {
			background-position: top;
			background-repeat: no-repeat;
			background-size: cover;
		}
	}
	/* Products landing banner: anchor background so left-side logo/badge are not cropped (cover + center was clipping) */
	.page-banner.page-banner-products.bg-img {
		background-position: left center;
	}
	@media (max-width: 850px) {
		.page-banner.page-banner-products.bg-img {
			background-position: left top;
		}
	}

	.error{ color: #e43d3c; }
	</style>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-Q1HW1HGFDE"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-Q1HW1HGFDE');
	</script>
	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/63e7318ac2f1ac1e2032a9c3/1govhj240';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
	</script>
	<!--End of Tawk.to Script-->
	</head>
	<body class="home page-template-default page page-id-2 theme-enviro woocommerce-no-js wpb-js-composer js-comp-ver-6.6.0 vc_responsive">
		<div id="webpage" class="webpage fixtop" data-spy="affix" data-offset-top="100">	
			<?php echo $this->element('kodexcc_header'); ?>
			<?php echo $this->fetch('content'); ?>
			<?php echo $this->element('kodexcc_footer'); ?>	
		</div>	
		<script type='text/javascript' src='<?php echo SITEURL ;?>wp-content/themes/enviro/tool/mmenujs/mmenu6996.js?ver=4.7.1' ></script>
		<script type='text/javascript'>
		const THEME_URL = "<?php echo SITEURL ;?>";
		const SITE_URL = "<?php echo SITEURL ;?>";
		const SLUG = "home";
		const DEV_MODE = false;
		const USER_NAME = "";
		const EDIT_URL = "";
		</script>
		<script type='text/javascript' src='<?php echo SITEURL ;?>wp-content/themes/enviro/js/kodexglobal.js' ></script>
		<script type='text/javascript' src='<?php echo SITEURL ;?>wp-content/themes/enviro/js/kodexhomee.js'  ></script>
		<script>jQuery(function ($) { $('body').css('opacity', 1); });</script>	
	</body>
</html>
