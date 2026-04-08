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
	<?php
		echo $this->Html->meta('icon');
	?>
		<meta name="google-site-verification" content="R50rW2j4zN6nENTDOVPfRsczHvMGGOTDBu8Tkag5ltg" />
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo SITEURL ;?>theme/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo SITEURL ;?>theme/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo SITEURL ;?>theme/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo SITEURL ;?>theme/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo SITEURL ;?>theme/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo SITEURL ;?>theme/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo SITEURL ;?>theme/js/ace-extra.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/jquery.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/jquery.validate.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/jquery-ui.js"></script>
		
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?php echo SITEURL ;?>theme/js/html5shiv.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/respond.js"></script>
		<![endif]-->
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
<body class="login-layout light-login">
	
		
	
		<div class="main-container" id="main-container">
			<div class="main-content">
			
				<?php echo $this->fetch('content'); ?>
				
			</div>

		
		</div>
<!-- basic scripts -->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo SITEURL ;?>theme/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo SITEURL ;?>theme/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo SITEURL ;?>theme/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->
<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
		</script>
	<?php //echo $this->element('sql_dump'); ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132219331-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-132219331-2');
</script>
</body>
</html>
