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
		<title>
			<?php echo $title_for_layout; ?>
		</title>
		
		<link href="<?php echo SITEURL ;?>favicon.png" type="image/x-icon" rel="icon" />
		<meta name="google-site-verification" content="APCO-gVPMSiSKXaAU7YnymUEU4K1kjEuRXmwQtnEK9c" />
		<meta name="msvalidate.01" content="18C63E8840CE8756F641A3CA6F76199D" />
		
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-5LKKRHG');</script>
		<!-- End Google Tag Manager -->
		<link href="<?php echo SITEURL ;?>front_theme/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo SITEURL ;?>front_theme/css/font-awesome.min.css" rel="stylesheet">
		<link href="<?php echo SITEURL ;?>front_theme/css/prettyPhoto.css" rel="stylesheet">
		<link href="<?php echo SITEURL ;?>front_theme/css/price-range.css" rel="stylesheet">
		<link href="<?php echo SITEURL ;?>front_theme/css/animate.css" rel="stylesheet">
		<link href="<?php echo SITEURL ;?>front_theme/css/main.css" rel="stylesheet">
		<link href="<?php echo SITEURL ;?>front_theme/css/responsive.css" rel="stylesheet">
		<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
		<![endif]-->       
		
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo SITEURL ;?>front_theme/images/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo SITEURL ;?>front_theme/images/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo SITEURL ;?>front_theme/images/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="<?php echo SITEURL ;?>front_theme/images/ico/apple-touch-icon-57-precomposed.png">
		
		<script src="<?php echo SITEURL ;?>theme/js/jquery.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/jquery.validate.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/jquery-ui.js"></script>

<style>	
.ValidationErrors{
    color: #d00;
    display: inline-block;
    font-size: 12px;
    font-style: italic;
    padding-left: 10px;
}
</style>
</head>
<body>
	
		<?php echo $this->element('header'); ?>
		<?php echo $this->fetch('content'); ?>
		<?php echo $this->element('footer'); ?>
	</div>
<!-- basic scripts -->
	<script src="<?php echo SITEURL ;?>front_theme/js/bootstrap.min.js"></script>
	<script src="<?php echo SITEURL ;?>front_theme/js/jquery.scrollUp.min.js"></script>
	<script src="<?php echo SITEURL ;?>front_theme/js/price-range.js"></script>
    <script src="<?php echo SITEURL ;?>front_theme/js/jquery.prettyPhoto.js"></script>
    <script src="<?php echo SITEURL ;?>front_theme/js/main.js"></script>
	<?php //echo $this->element('sql_dump'); ?>
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


</body>
</html>
