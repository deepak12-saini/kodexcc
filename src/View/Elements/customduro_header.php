	<script>
		function search(){			
			var searchPro = $("#searchPro").val();
			if(searchPro != ''){
				$("#formsubmit").submit();
			}else{
				//$("#formsubmit").submit();
				return false;
			}	
		}	
	</script>
	<style>
	.login{ border: 1px solid #ED1C24;padding: 6px 8px;border-radius: 14px;color: #ED1C24;margin-right: 10px !important;}

.mega-dropdown-menu {
  padding: 20px 0px;
  width: 100%;
  box-shadow: none;
  -webkit-box-shadow: none;
}

.mega-dropdown-menu:before {
  content: "";
  border-bottom: 15px solid #fff;
  border-right: 17px solid transparent;
  border-left: 17px solid transparent;
  position: absolute;
  top: -15px;
  left: 285px;
  z-index: 10;
}

.mega-dropdown-menu:after {
  content: "";
  border-bottom: 17px solid #ccc;
  border-right: 19px solid transparent;
  border-left: 19px solid transparent;
  position: absolute;
  top: -17px;
  left: 283px;
  z-index: 8;
}

.mega-dropdown-menu > li > ul {
  padding: 0;
  margin: 0;
}

.mega-dropdown-menu > li > ul > li {
  list-style: none;
}

.mega-dropdown-menu > li > ul > li > a {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 1.428571429;
  color: #999;
  white-space: normal;
}

.mega-dropdown-menu > li ul > li > a:hover,
.mega-dropdown-menu > li ul > li > a:focus {
  text-decoration: none;
  color: #444;
  background-color: #f5f5f5;
}

.mega-dropdown-menu .dropdown-header {
  color: #428bca;
  font-size: 18px;
  font-weight: bold;
}

.mega-dropdown-menu form {
  margin: 3px 20px;
}

.mega-dropdown-menu .form-group {
  margin-bottom: 3px;
}
@media (min-width: 768px){
.navbar-right .dropdown-menu { 
       width: 436px !important;
    margin-right: -86px;
}
}
.mega-dropdown-menu > li > ul > li > a {   
    color: #1e3038 !important
   <!--  font-weight: bold; -->
}
.mega-dropdown-menu > li ul > li > a:hover, .mega-dropdown-menu > li ul > li > a:focus {
    text-decoration: none;
	color: #2889c9;
}
.mega-dropdown-menu .dropdown-header {
    color: #2889c9;
    font-size: 14px;
    font-weight: bold;
}
.navbar-right {
    float: right !important;
    margin-right: 8px;
}
.durotech_product {
    padding-top: 0%;
}
a {
    color: #ED1C24 !important;	
}
h1{	color: #1e3038; }
h2{	color: #1e3038; }
h3{	color: #1e3038; }
h4{	color: #1e3038; }
h5{	color: #1e3038; }
p {

    color: #1e3038 !important;
}
</style>
<script>
jQuery(document).on('click', '.mega-dropdown', function(e) {
  e.stopPropagation()
})
</script>
	<?php
		$searchtitle = $this->Session->read('searchtitle');
		$getproducttype = $this->requestAction('/app/getproducttype');
		$getproductbyuse = $this->requestAction('/app/getproductbyuse');
	?>
	<div class="header_fixed col-lg-12 col-md-12 col-sm-12">
		<div class="header_top col-lg-12 col-md-12 col-sm-12">
			  
			  <div class="header_top_address col-lg-6 colmd-6 col-sm-6">
				   <ul>
					   <li><Strong>Address:</strong> <?php echo ADDRESS;  ?></li>
					   <li><Strong>Phone:</strong> <?php echo PHONE;  ?></li>
				   
				   </ul>
			  </div>
			  <div class="header_top_social_search col-lg-2 col-md-2 col-sm-2" >
					<form action="<?php echo SITEURL.'products/search' ?>" method="post" id="formsubmit">
						<input type="text" name="data[search]" value="<?php echo $searchtitle; ?>" id="searchPro" placeholder="Search Product" required>
						<a href="javascript:void(0)" onclick="search()"><i  onclick="search()" class="fa fa-search" aria-hidden="true"></i></a>
					</form>			  
			  </div>
			  <div class="header_top_social col-lg-2 col-md-2 col-sm-2">
			   <ul>
					<li><a  target="_blank" href="<?php echo FACEBOOK ?>"><i class="fa fa-facebook" aria-hidden="true"></a></i></li>
					<li><a  target="_blank" href="<?php echo TWITER ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
					<li><a  target="_blank" href="<?php echo GOOGLE_PLUS ?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
					<li><a  target="_blank" href="https://www.instagram.com/kodexglobalcc/"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>					   
			   </ul>			  
			</div>
			<div class="header_top_social_login col-lg-2 col-md-2 col-sm-2" >
			<?php
			$is_customer = $this->Session->read('is_customer'); 
			if(empty($is_customer)){		
			?>
				<a href="<?php echo SITEURL.'login'; ?>" class="login">Login </a>					
			
			<a href="<?php echo SITEURL.'register'; ?>" class="login">Register</a>					
			<?php }else{ ?>
				<a href="<?php echo SITEURL.'users/dashboard'; ?>" class="login">Account</a>	
			<?php } ?>	
			</div>
		</div>
		<div class="container example5">
			<nav class="navbar navbar-default">
			<div class="container-fluid">
			  <div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar5">
				  <span class="sr-only">Toggle navigation</span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo SITEURL ;?>"><img src="<?php echo SITEURL ;?>customdurotech/images/durotech_logo.png" alt="Dispute Bills">
				</a>
			  </div>
			  <div id="navbar5" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
				  <!--li class="active"><a href="<?php echo SITEURL ?>">Home</a></li-->
				  <li><a href="<?php echo SITEURL ?>">Home</a></li>
				  <li><a href="<?php echo SITEURL ?>about">About Us</a></li>
				  <li><a href="<?php echo SITEURL ?>products">Products</a></li>
				  <!--li class="dropdown mega-dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#null">Products</a>
				  
				  
					<ul class="dropdown-menu mega-dropdown-menu row">
						
						<li class="col-sm-6">
						  <ul>
						  
							
							<li class="dropdown-header">Product</li>
							<li><a href="<?php echo SITEURL ?>products">Products</a></li>
							<li class="divider"></li>
							
							<li class="dropdown-header">Type</li>
							<?php foreach($getproducttype as $getproducttypes){ ?>
							<li><a href="<?php echo SITEURL.'products/detail/'.$getproducttypes['Product']['slug'] ;?>"><?php echo $getproducttypes['Product']['title'];?></a></li>
							<?php } ?>
						  </ul>
						</li>
						<li class="col-sm-6">
						  <ul>
							<li class="dropdown-header">By Use</li>
							<?php foreach($getproductbyuse as $getproductbyuses){ ?>
							<li><a href="<?php echo SITEURL.'products/detail/'.$getproductbyuses['Product']['slug'] ;?>"><?php echo ucfirst(strtolower($getproductbyuses['Product']['title']));?></a></li>
							<?php } ?>
							
						  </ul>
						</li>
					
					  </ul>
				  
				  </li-->
				  <!--li><a href="<?php echo SITEURL ?>technical-literature">Technical literature</a></li>
				  <li><a href="<?php echo SITEURL ?>specifiers">Specifiers</a></li>
				  <li><a href="https://duroshop.com">DuroShop</a></li-->
				  <!--li><a href="<?php echo SITEURL ?>durolab">DuroLab</a></li-->
				
				  <!--li><a href="http://durotechpolymers.com">Durotech Polymers</a></li-->
				  <li><a href="<?php echo SITEURL ?>contact">Contact Us</a></li>
				</ul>
			  </div>
			  <!--/.nav-collapse -->
			</div>
			<!--/.container-fluid -->
			</nav>
		</div>
	</div>
	