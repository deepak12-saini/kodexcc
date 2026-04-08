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
	

		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-5LKKRHG');</script>
		<!-- End Google Tag Manager -->

	<meta name="description" content="<?php  if(isset($meta_description)){ echo $meta_description; } else{ echo  META_DESCRIPTION; }?>" />
	<meta name="keywords" content="<?php  if(isset($meta_keyword)){ echo $meta_keyword; } else{ echo  META_KEYWORD; }?>">
	<meta name="google-site-verification" content="FXVutlwYWnDRsI7KWBzt3RcaQXumzQswN86ExJrL4XM" />
	<?php
		//echo $this->Html->meta('icon');
	?>
	<link href="<?php echo SITEURL ;?>favicon.png" type="image/x-icon" rel="icon" /><link href="<?php echo SITEURL ;?>favicon.png" type="image/x-icon" rel="shortcut icon" />	
	
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>customdurotech/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>customdurotech/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>customdurotech/css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway" />
	<link href='https://fonts.googleapis.com/css?family=Raleway:800' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
	
	<script src="https://osvaldas.info/examples/main.js"></script>
	<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="<?php echo SITEURL ;?>theme/js/jquery.validate.js"></script>
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
	<div class="durotech col-lg-12 col-md-12 col-sm-12">       
		<?php echo $this->element('customduro_header'); ?>
		<?php echo $this->fetch('content'); ?>
		<?php echo $this->element('customduro_footer'); ?>	
		<?php //echo $this->element('sql_dump'); ?>
	</div>
	<!-- Global site tag (gtag.js) - Google Analytics -->
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
