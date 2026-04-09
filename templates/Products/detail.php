	<style>
	.sec-gap-btm {
		padding-bottom: 0rem;
	}
	p, .p {		
		text-align: justify;
	}
	.btn{ padding: 10px;border: 1px solid #1175b8;border-radius: 18px;color: #1175b8;}
	.btn:hover{ padding: 10px;border: 1px solid #1175b8;border-radius: 18px;color: #fff; background:#1175b8; }
	.btn:active{ padding: 10px;border: 1px solid #ddd;border-radius: 18px;color: #fff; background:#1175b8; }
	.green-boxed.reverse 
	{		
		background: linear-gradient(to left, #244e6a 0%, #1175b8 50%,#90b7d0 100%);
	}
	
	/*
	.wp-post-image:hover, .wp-post-image:focus, .wp-post-image:active 
	{
		text-decoration: none;
		animation: zoom-in-zoom-out 1s ease infinite;
		width: 100%;		
	}
	
	@keyframes zoom-in-zoom-out {
	  0% {
			transform: scale(1, 1);
	  }
	  50% {
			transform: scale(1.5, 1.5);
	  }
	  100% {
			transform: scale(1, 1);
	  }
	} */
	
	</style>
	<?php
		$desc = '';
		$banner = 'Banner-Waterproofing.jpg';
		$slug = $products['Category']['slug'];
		if($slug == 'waterproofing-membranes')
		{			
			$banner = 'Banner-Waterproofing.jpg';
		}else if($slug == 'sealants-silicones')
		{			
			$banner = 'Banner-Sealants.jpg';
		}else if($slug == 'primers')
		{			
			$banner = 'Banner-Primer.jpg';
		}else if($slug == 'waterproofing-tapes')
		{			
			$banner = 'Banner-Tapes.jpg';
		}

		$datasheetHref = (string)($products['Product']['datasheet'] ?? '');
		if ($datasheetHref !== '' && !preg_match('#^https?://#i', $datasheetHref)) {
			$datasheetHref = SITEURL . 'productimg/' . ltrim($datasheetHref, '/');
		}
	
	?>
	<main class="page-body page-body-2">
	<section class="vc-row vc-row-o bg-img page-banner" style="background-image:url('<?php echo SITEURL.'wp-content/'.$banner ;?>');">
		<div class="vc-col vc-col-o banner-content ctn ctn-lg">	
			<h1 class="hd "><?php //echo ucfirst($products['Product']['title']); ?></h1>
		</div>
	</section>
	<section class="breadcrumb-sec sec-gap-top">
		<div class="ctn">
			<span class="breadcrumb pcat-adhesives">				
				<a href="<?php echo SITEURL ;?>" class="main-cat-link hover-txt-green">Home</a> / 
				<a href="<?php echo SITEURL.'products'; ;?>" class="main-cat-link hover-txt-green">Products</a> / 
				<a href="<?php echo  SITEURL.'products/'.$products['Category']['slug']; ?>" class="main-cat-link hover-txt-green"><?php echo ucfirst(($products['Category']['category_name'])); ?></a> / 
				<strong class="product-name"><?php echo ucfirst($products['Product']['title']); ?></strong>
			</span>
		</div>
	</section>
	
	<section class="product-intro sec-gap-btm product type-product post-9350 status-publish first instock product_cat-adhesive-sealants product_cat-adhesives product_cat-joint-sealants product_cat-sealants product_tag-adhesive-sealant product_tag-joint-sealant has-post-thumbnail taxable shipping-taxable purchasable product-type-simple">
		<div class="ctn">
			<div class="cols">
				<div class="col col-l col-txt">
					<h1 class="hd txt-green"><?php echo ucfirst(($products['Product']['title'])); ?></h1>
					<p></p>
					<div class="rgc-single-product-summary">

					<ul class="rgc-variation-list">
						<li>
						<strong>Size:</strong> <?php echo $products['Product']['sizes']; ?></li>
					</ul>
					<div class="product_meta">
						<div class="product-meta-item product-category-list">
							<strong>Categories:</strong> 
							<a href="<?php echo  SITEURL.'products/'.$products['Category']['slug']; ?>" rel="tag"><?php echo ucfirst(($products['Category']['category_name'])); ?></a>
						</div>						
					</div>
					</div>
				</div>
				<div class="col col-r col-img">
					<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" style="opacity: 0.9; transition: opacity .25s ease-in-out;">
						<figure class="woocommerce-product-gallery__wrapper">
							<div>
								<a href="<?php echo  SITEURL.'productimg/'.$products['Product']['image']; ?>" target="_blank">
									<img src="<?php echo  SITEURL.'productimg/'.$products['Product']['image']; ?>" class="wp-post-image" alt="<?php echo ucfirst(($products['Product']['title'])); ?>"/>
								</a>
							</div>	
						</figure>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	
	<section class="product-tabs-sec">
		<div class="woocommerce-tabs wc-tabs-wrapper">
			<div class="green-boxed reverse">
				<div class="ctn">
					<ul class="tabs wc-tabs" role="tablist">
						<li class="description_tab" id="tab-title-description" role="tab" aria-controls="tab-description">
							<a href="#tab-description">Product Outline</a>
						</li>
						<li class="data_sheets_tab" id="tab-title-data_sheets" role="tab" aria-controls="tab-data_sheets">
							<a href="#tab-data_sheets">Data Sheets</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="sec-gap woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">
				<div class="ctn">
				<div class="product-tab-description txt-block two-col"><div class="donotbreak">
					<h3><strong>Description</strong></h3>
					<?php echo $products['Product']['description']; ?>
					</div>
					<?php if(!empty($products['Product']['feature'])){ ?>
						<div class="donotbreak">
							<h3><strong>Features</strong></h3>
							<?php echo $products['Product']['feature']; ?>
						</div>
					<?php } ?>
					<?php if(!empty($products['Product']['userarea'])){ ?>
						<div class="donotbreak">
							<h3><strong>User Area</strong></h3>
							<?php echo $products['Product']['userarea']; ?>
						</div>
					<?php } ?>
				</div>
				</div>
			</div>
			<div class="sec-gap woocommerce-Tabs-panel woocommerce-Tabs-panel--data_sheets panel entry-content wc-tab" id="tab-data_sheets" role="tabpanel" aria-labelledby="tab-title-data_sheets">
				<div class="ctn">
					<div class="product-tab-data-sheets">
						<h3>Download Data Sheets</h3>
						<div id="document-gallery-1" class="document-gallery" >
							<div class='document-icon-row'>
								<div class="document-icon" data-id="7185">
									<a href="<?php echo h($datasheetHref); ?>" target="_blank" title="Download <?php echo ucfirst(($products['Product']['title'])); ?>">
										<!--img src="" title="<?php echo ucfirst(($products['Product']['title'])); ?>" alt="<?php echo ucfirst(($products['Product']['title'])); ?>" data-ext="pdf"/-->
										<span class="title btn"><?php echo ucfirst(($products['Product']['title'])); ?> &#8211; Product Data Sheet</span>
									</a>
								</div>

							</div>
						</div>
					</div>			
				</div>
			</div>
					
	</div>
	</section>

	</main><!-- .page-body -->