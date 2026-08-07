<?php 
$getpro = $this->requestAction('/app/getcate');
?>
<nav id="mob-menu" class="mobile-menu-nav" role="navigation" aria-label="Mobile Menu">	<ul class="mobile_menu-ul">
	<?php /* Products — uncomment to restore
	<li class="mobile_menu-li has-submenu ">
		<a class="mobile_menu-a" href="<?php echo SITEURL.'products'; ?>">Products</a>
		<ul class="mobile_menu_submenu-ul">		
			<li class="mobile_menu_submenu-li no-submenu " data-post="23">
			<a class="mobile_menu_submenu-a" href="<?php echo SITEURL.'products'; ?>" data-post="23">All Products</a>
				<ul class="subcat-ul">
					<?php
						foreach($getpro as $getpros)
						{
					?>
					<li class="subcat-li ">
					<a class="no-href" href="<?php echo SITEURL.'products/'.$getpros['Category']['slug']; ?>"><?php echo $getpros['Category']['category_name']; ?></a>
					<ul class="product-ul">
							<?php
								if(!empty($getpros['Product']))
								{
									foreach($getpros['Product'] as $pro)
									{
							?>
							<li class="product-li"><a class="product-a" href="<?php echo SITEURL.'products/'.$getpros['Category']['slug'].'/'.$pro['slug']; ?>"><?php echo $pro['title']; ?></a></li>
							<?php } }  ?>
						</ul>
					</li>
					<?php } ?>		
					
					
				</ul>
			</li>	
		</ul>
	</li>
	*/ ?>

	<?php /* Documents & Brochures — uncomment to restore
	<li class="mobile_menu-li has-submenu ">
		<a class="mobile_menu-a" href="">Documents &#038; Brochures</a>
		<ul class="mobile_menu_submenu-ul">
		<li class="mobile_menu_submenu-li no-submenu">
			<a class="mobile_menu_submenu-a" href="product-data-sheets">Product Data Sheets</a>
		</li>
				
		</ul>
	</li>
	*/ ?>

	<li class="mobile_menu-li no-submenu ">
		<a class="mobile_menu-a" href="<?php echo SITEURL; ?>about-us">About us</a>
	</li>
	<li class="mobile_menu-li has-submenu ">
		<a class="mobile_menu-a" href="javascript:void(0)">Capabilities</a>
		<ul class="mobile_menu_submenu-ul">
			<li class="mobile_menu_submenu-li no-submenu">
				<a class="mobile_menu_submenu-a" href="<?php echo SITEURL; ?>contact-us">Private Label Programmes</a>
			</li>
			<li class="mobile_menu_submenu-li no-submenu">
				<a class="mobile_menu_submenu-a" href="<?php echo SITEURL; ?>contact-us">Custom Formulation</a>
			</li>
			<li class="mobile_menu_submenu-li no-submenu">
				<a class="mobile_menu_submenu-a" href="<?php echo SITEURL; ?>contact-us">Confidential Supply</a>
			</li>
		</ul>
	</li>
	<li class="mobile_menu-li no-submenu ">
		<a class="mobile_menu-a" href="<?php echo SITEURL; ?>contact-us">Contact us</a>
	</li>
</ul>
</nav>

<div class="site-header-base kx-chrome">
	<header class="site-header kx-header" id="kx-header">
		<div class="kx-header__bar header-cols">
			<div class="col col-l">
				<a class="kx-logo" href="<?php echo SITEURL; ?>" aria-label="KodexCC home">
					<span class="kx-logo__crop">
						<img class="site-logo" src="<?php echo SITEURL ;?>wp-content/kodex-white-logo.png" alt="KodexCC">
					</span>
					<span class="kx-logo__text">
						<span class="kx-logo__name">KodexCC</span>
						<span class="kx-logo__tag">Private Label OEM</span>
					</span>
				</a>
			</div>
			<div class="col col-m">
			<nav class="header-menu kx-nav" aria-label="Primary">	
			<ul class="sitemenu-ul">
				<?php /* Products — uncomment to restore
				<li class="sitemenu-li has-submenu">
				<a class="sitemenu-a" href="<?php echo SITEURL.'products'; ?>">Products</a>
				<ul class="sitemenu_submenu-ul">
				
				<?php
					foreach($getpro as $getpros)
					{
						$slug = $getpros['Category']['slug'] ?? '';
						$cate = 'other';
						if ($slug === 'waterproofing-membranes') {
							$cate = 'waterproofing';
						} elseif ($slug === 'sealants-silicones') {
							$cate = 'sealants';
						} elseif ($slug === 'primers') {
							$cate = 'primers';
						} elseif ($slug === 'waterproofing-tapes') {
							$cate = 'adhesives';
						}
				?>
				<li class="sitemenu_submenu-li no-submenu pcat pcat_<?php echo h($cate); ?>">
					<a class="sitemenu_submenu-a" href="<?php echo SITEURL.'products/'.$getpros['Category']['slug']; ?>"><?php echo $getpros['Category']['category_name']; ?></a>								
						<div class="product-dropdown-menu">
						<div class="product-menu">
							<div class="ctn">
								<div class="menu-cols">
									<div class="menu-col menu-col-l">
										<h2 class="menu-cat-title"><a href="<?php echo SITEURL.'products/'.$getpros['Category']['slug']; ?>"><?php echo $getpros['Category']['category_name']; ?></a></h2>
										<div class="subcat-menu">
											<div class="col col-tabs">
											<ul class="subcat-ul">
												<li class="subcat-li active"><?php echo $getpros['Category']['category_name']; ?></li>
											</ul>
											</div>
											<div class="col col-links">
											<div class="subcat-products active">
											<ul class="product-ul">
												<?php
													if(!empty($getpros['Product']))
													{
														foreach($getpros['Product'] as $pro)
														{
												?>
												<li class="product-li"><a class="product-a" href="<?php echo SITEURL.'products/'.$getpros['Category']['slug'].'/'.$pro['slug']; ?>"><?php echo $pro['title']; ?></a></li>
												<?php } }  ?>
											</ul>
											</div>
											
											</div>
										</div>
									</div>
									<div class="menu-col menu-col-r">
									<img class="img-center" src="<?php echo SITEURL.'category/'.$getpros['Category']['image'];?>" alt="Sealants">										
									</div>
								</div>
							</div>
						</div>
						<section class="vc-row vc-row-o bg-grey sec-gap-xs dropdown-footer" >
							<div class="vc-col vc-col-o ctn" >
								<div class="vc-row vc-row-i cols" >
									<div class="vc-col vc-col-i col col-l" ><i class="ico fas fa-truck"></i><span class=""  note >Australia Wide Reseller Network</span></div>
									<div class="vc-col vc-col-i col col-m" ><i class="ico fas fa-person-carry"></i><span class=""  note >Technical Advisory Centre Support</span></div>
									<div class="vc-col vc-col-i col col-r" ><i class="ico fas fa-phone"></i><span class=""  note >Technical & Project Advice available</span></div>
								</div>
							</div>
						</section>
					</div>
				</li>
				<?php } ?>
				
				
				
				</ul>
				</li>
				*/ ?>
				<?php /* Documents & Brochures — uncomment to restore
				<li class="sitemenu-li has-submenu">
					<a class="sitemenu-a" href="<?php echo SITEURL ;?>product-data-sheets">Documents &#038; Brochures</a>
					<ul class="sitemenu_submenu-ul">
						<li class="sitemenu_submenu-li no-submenu">
							<a class="sitemenu_submenu-a" href="<?php echo SITEURL ;?>product-data-sheets">Product Data Sheets</a>
						</li>
						<li class="sitemenu_submenu-li no-submenu">
							<a class="sitemenu_submenu-a" href="<?php echo SITEURL ;?>product-msds">Product MSDS</a>
						</li>
						<li class="sitemenu_submenu-li no-submenu">
							<a class="sitemenu_submenu-a" href="<?php echo SITEURL ;?>documents-brochures">Brochures</a>
						</li>
					
					</ul>
				</li>
				*/ ?>
				<li class="sitemenu-li no-submenu">
					<a class="sitemenu-a" href="<?php echo SITEURL ;?>about-us">About</a>
				</li>
				<li class="sitemenu-li has-submenu">
					<a class="sitemenu-a" href="javascript:void(0)">Capabilities</a>
					<ul class="sitemenu_submenu-ul kx-dropdown">
						<li class="sitemenu_submenu-li no-submenu">
							<a class="sitemenu_submenu-a" href="<?php echo SITEURL ;?>contact-us">Private Label Programmes</a>
						</li>
						<li class="sitemenu_submenu-li no-submenu">
							<a class="sitemenu_submenu-a" href="<?php echo SITEURL ;?>contact-us">Custom Formulation</a>
						</li>
						<li class="sitemenu_submenu-li no-submenu">
							<a class="sitemenu_submenu-a" href="<?php echo SITEURL ;?>contact-us">Confidential Supply</a>
						</li>
					</ul>
				</li>
				<li class="sitemenu-li no-submenu">
					<a class="sitemenu-a" href="<?php echo SITEURL ;?>contact-us">Contact</a>
				</li>
				<li class="sitemenu-li no-submenu kx-nav-cta-li">
					<a class="kx-nav-cta" href="<?php echo SITEURL ;?>contact-us">Partner With Us</a>
				</li>
			</ul>			
		</nav>
			<a href="#mob-menu" class="mburger mburger--collapse kx-burger" aria-label="Open menu"><b></b><b></b><b></b></a>
		</div>			
	</div>		
</header>
</div>
