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

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>Kodexglobalcc.com: 404 Page</title>
	<link href="https://kodexglobalcc.com/favicon.png" type="image/x-icon" rel="icon" />
	<link href="https://kodexglobalcc.com/favicon.png" type="image/x-icon" rel="shortcut icon" />	
	<!-- ================== Font =================== -->
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/font/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/font/mdi-font/css/material-design-iconic-font.min.css">
	<!-- ================== Vendor CSS =================== -->
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/vendor/bootstrap4/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/vendor/owl-carousel/animate.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/vendor/owl-carousel/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/vendor/owl-carousel/owl.theme.default.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/vendor/revolution/settings.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/vendor/revolution/navigation.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/vendor/revolution/layers.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/vendor/lightbox2/src/css/lightbox.css">

	<!-- Main CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/css/font.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/css/style.css">

	<!-- Icon CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>public/frontend/css/font-icons.css">

	<!--alerts CSS -->
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
	</style>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?php //echo $this->Html->link($cakeDescription, 'http://cakephp.org'); ?></h1>
		</div>
		<div id="content">

			<?php echo $this->Flash->render(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
