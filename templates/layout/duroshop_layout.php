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
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>duroshop/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>duroshop/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>duroshop/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!--link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Raleway" />
	<link href='https://fonts.googleapis.com/css?family=Raleway:800' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'-->


	<script src="<?php echo SITEURL ;?>theme/js/jquery.js"></script>
	<script src="<?php echo SITEURL ;?>theme/js/jquery.validate.js"></script>
	<script src="<?php echo SITEURL ;?>theme/js/jquery-ui.js"></script>	
	<script src="<?php echo SITEURL ;?>duroshop/js/autocomplete.js"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<?php
		$catproduct = array();
		$getcarttotal = $this->requestAction('/app/getcarttotal');	
		$pro = $this->requestAction('/app/getproduct');	
		foreach($pro as $pros){
			$catproduct[] = $pros['Product']['title'];
		}
		$catproduct = json_encode($catproduct);	
		$searchtitle = $this->Session->read('searchtitle');
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		// Defining the local dataset
		var cars = [<?php echo $catproduct; ?>];
		
		// Constructing the suggestion engine
		var cars = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.whitespace,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			local: cars
		});
		
		// Initializing the typeahead
		$('.product_search').typeahead({
			hint: true,
			highlight: true, /* Enable substring highlighting */
			minLength: 1 /* Specify minimum characters required for showing result */
		},
		{
			name: 'cars',
			source: cars
		});
	});  
	
	function search(){
		$("#searchId").submit();
	}	
	</script>

</head>
<body>

	<div class="duroshop_home col-lg-12 col-md-12 col-sm-12">
		 <div class="header col-lg-12 col-md-12 col-sm-12">
			  <div class="container">
					<div class="header_logo col-lg-6 col-md-6 col-sm-6">
						<img src="<?php echo SITEURL ;?>duroshop/images/logo.png">
					</div>
					<div class="header_right col-lg-6 col-md-6 col-sm-6">				
						<ul class="">
								<li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:<?php echo MAILTO ?>"><?php echo MAILTO ?></a></li>
								<li><i class="fa fa-phone" aria-hidden="true"></i><a href="tel:<?php echo PHONE ?>"><?php echo PHONE ?></a> </li>
						</ul>
						
						<div class="Search_header col-lg-6 col-md-6 col-sm-6">
							 <form action="<?php echo SITEURL.'products/search'?>" method="post" id="searchId">
								   <input class="product_search" value="<?php echo $searchtitle; ?>" type="text" placeholder="Search for product" name="product"><i onclick="search()" class="fa fa-search" aria-hidden="true" style="cursor:pointer"></i>						
							 </form>
					    </div>
							 <div class="header_user_shop col-lg-6 col-md-6 col-sm-6">
								   <a href="#" ><i class="fa fa-shopping-cart" aria-hidden="true"><span class="badge" id="cartval"><?php echo $getcarttotal; ?></span></a></i>
								   <i class="fa fa-user-o" aria-hidden="true"></i>
							 </div>
						
					</div>
			 </div>
		</div>
	
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

</body>
</html>
