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
<link href="<?php echo SITEURL ;?>favicon.png" type="image/x-icon" rel="icon" /><link href="<?php echo SITEURL ;?>favicon.png" type="image/x-icon" rel="shortcut icon" />
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />	

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js "></script>			
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
<body class="no-skin">
	
	<?php // echo $this->element('admin_header'); ?>

	<div class="main-container" id="main-container">
	<?php //echo $this->element('admin_sidebar'); ?>
	<div class="main-content">
		<div class="main-content-inner">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
	<?php //echo $this->element('admin_footer'); ?>
	</div>
<!-- basic scripts -->
		
		<!--[if !IE]> -->
		<script type="text/javascript">
			$(document).ready(function() {
				$('#example').DataTable();
				$('#example1').DataTable();
			} );
			window.jQuery || document.write("<script src='<?php echo SITEURL ;?>theme/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

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

		<!--[if lte IE 8]>
		  <script src="../assets/js/excanvas.js"></script>
		<![endif]-->
		<script src="<?php echo SITEURL ;?>theme/js/jquery-ui.custom.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/jquery.ui.touch-punch.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/jquery.easypiechart.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/jquery.sparkline.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/flot/jquery.flot.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/flot/jquery.flot.pie.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/flot/jquery.flot.resize.js"></script>

		<!-- ace scripts -->
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.scroller.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.colorpicker.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.fileinput.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.typeahead.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.wysiwyg.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.spinner.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.treeview.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.wizard.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/elements.aside.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.ajax-content.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.touch-drag.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.sidebar.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.submenu-hover.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.widget-box.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.settings.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.settings-rtl.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.settings-skin.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?php echo SITEURL ;?>theme/js/ace/ace.searchbox-autocomplete.js"></script>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
