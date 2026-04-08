
 <header class="main-header clearfix">
	<div class="main-header__top">
		<div class="container">
			<div class="main-header__top-inner clearfix">
				<div class="main-header__logo">
					<a href="<?php echo SITEURL ;?>">
						<img src="<?php echo SITEURL ;?>public/frontend/img/kdlogo.png" alt="KodexGlobalcc" class="dark-logo">
						<img src="<?php echo SITEURL ;?>public/frontend/img/kdlogo.png" alt="KodexGlobalcc" class="light-logo">
					</a>
				</div>
				<div class="main-header__top-right">
					<div class="main-header__top-right-content">
						<div class="main-header__top-address-box">
							<ul class="list-unstyled main-header__top-address">
								<!--li>
									<div class="icon">
										<span class="icon-phone-call"></span>
									</div>
									<div class="content">
										<p>Call anytime</p>
										<h5><a href="tel:<?php //echo PHONE; ?>">+ <?php //echo PHONE; ?></a></h5>
									</div>
								</li-->
								<li>
									<div class="icon">
										<span class="icon-message"></span>
									</div>
									<div class="content">
										<p>Send email</p>
										<h5><a href="mailto:<?php echo MAILTO; ?>"><?php echo MAILTO; ?></a></h5>
									</div>
								</li>
								
							</ul>
						</div>
						<div class="main-header__top-right-social">
							<a href="<?php echo TWITER; ?>" alt="twitter"><i class="fab fa-twitter"></i></a>
							<a href="<?php echo FACEBOOK; ?>" alt="facebook"><i class="fab fa-facebook"></i></a>
							<a href="<?php echo LINKDIN; ?>" alt="instagram" ><i class="fab fa-instagram"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<nav class="main-menu clearfix">
		<div class="main-menu__wrapper clearfix">
			<div class="container">
				<div class="main-menu__wrapper-inner clearfix">
					<div class="main-menu__left">
						<div class="main-menu__main-menu-box">
							<a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
							<ul class="main-menu__list">
								<li class="dropdown current megamenu"><a href="/">Home </a></li>
								<li><a href="/about" title="About Us Page">About </a></li>								
								<li><a href="/products" title="Product Page">Products</a></li>
								<li><a href="/service" title="Service Page">Service</a></li>
								<li><a href="/video" title="Video Page">Video</a></li>
								<li><a href="/contact" title="Contact Us Page">Contact Us</a></li>
							</ul>
						</div>
					</div>
					<div class="main-menu__right">
						<div class="main-menu__search-btn-box">
							<div class="main-menu__search-box">
								<a href="#" class="main-menu__search search-toggler icon-magnifying-glass"></a>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</nav>
</header>
 <!-- /.mobile-nav__wrapper -->
<div class="stricky-header stricked-menu main-menu">
            <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
        </div><!-- /.stricky-header -->
<div class="search-popup">
	<div class="search-popup__overlay search-toggler"></div>
	<!-- /.search-popup__overlay -->
	<div class="search-popup__content">
		<form action="#">
			<label for="search" class="sr-only">search here</label><!-- /.sr-only -->
			<input type="text" id="search" placeholder="Search Here..." />
			<button type="submit" aria-label="search submit" onclick="search()"  class="thm-btn">
				<i class="icon-magnifying-glass"></i>
			</button>
		</form>
	</div>
	<!-- /.search-popup__content -->
</div>
<script>
	function search(){			
		var searchPro = $("#header-input").val();
		if(searchPro != ''){
			$("#formsubmit").submit();
		}else{
			//$("#formsubmit").submit();
			return false;
		}	
		
	}
</script>	
<!-- /.search-popup -->
<div class="stricky-header stricked-menu main-menu">
	<div class="sticky-header__content"></div><!-- /.sticky-header__content -->
</div><!-- /.stricky-header -->