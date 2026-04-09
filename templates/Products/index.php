	<style>
	.sec-gap-btm {
		padding-bottom: 0rem;
	}
	.green-boxed.reverse 
	{		
		background: linear-gradient(to left, #244e6a 0%, #1175b8 50%,#90b7d0 100%);
	}
	.blog-one__title{ text-align:center; }
	.rgc-product-archive .product-item:hover 
	{
	  border-color: #1175b8;
	}
	.btn.btn6 {
	  color: #ffffff;
	  background-color: #1175b8;
	}
	/*
	.proimg:hover, .proimg:focus, .proimg:active 
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
		// Safe defaults for newly imported categories.
		$cate = 'category_250.jpg';
		$banner = 'Banner-Waterproofing.jpg';
		$categoryIcon = '';
		if (!empty($category_image)) {
			$categoryIcon = SITEURL . 'category/' . ltrim((string)$category_image, '/');
		}
		$desc = '';
		if($slug == 'waterproofing-membranes')
		{
			$cate = 'waterproofing.png';
			$desc = 'kodex waterproofing membranes form an impermeable barrier that is durable, flexible, and certain to withstand the toughest of conditions. our specialization in bathroom waterproofing, shower waterproofing,bathroom floor waterproofing.';
			$banner = 'Banner-Waterproofing.jpg';
		}else if($slug == 'sealants-silicones')
		{
			$cate = 'sealants.png';
			$desc = 'Our surface sealants work by forming a thin film over a substrate creating a hard-wearing, UV stable and dustproof layer while our penetrating sealants work by penetrating the pores of a cementitious or natural stone substrate to fill the voids with a silicone-based compound.';
			$banner = 'Banner-Sealants.jpg';
		}else if($slug == 'primers')
		{
			$cate = 'primers.png';
			$desc = 'Primers are an important part of all applications as they improve the bonding characteristics of coatings to substrates.';
			$banner = 'Banner-Primer.jpg';
		}else if($slug == 'waterproofing-tapes')
		{
			$cate = 'adhesives.png';
			$banner = 'Banner-Tapes.jpg';
		}else if($slug == 'polymers')
		{
			$cate = 'adhesives.png';
			$banner = 'Banner-Tapes.jpg';
		}
	
	?>
	<main class="page-body page-body-2">
	<section class="vc-row vc-row-o bg-img page-banner" style="background-image:url('<?php echo SITEURL.'wp-content/'.$banner ;?>');">
		<div class="vc-col vc-col-o banner-content ctn ctn-lg">	
			<h1 class="hd"></h1>
		</div>
	</section>
	<section class="breadcrumb-sec sec-gap-top">
		<div class="ctn">
		
			<span class="breadcrumb pcat-adhesives">				
				<a href="<?php echo SITEURL ;?>" class="main-cat-link hover-txt-green">Home</a> / 
				<a href="<?php echo SITEURL.'products'; ;?>" class="main-cat-link hover-txt-green">Products</a> / 
				<strong class="product-name"><?php echo ucfirst($category_name); ?></strong>
			</span>
		</div>
	</section>
	
	<section class="vc-row vc-row-o sec-gap">
		<div class="vc-col vc-col-o ctn txt-center txt-block">			
			<img class="img above-cat-hd-icon" src="<?php echo h($categoryIcon !== '' ? $categoryIcon : (SITEURL.'wp-content/'.$cate)); ?>" alt="<?php echo ucfirst($category_name); ?>">
			<h2 class="hd txt-accessories hd-lg p"><?php echo ucfirst($category_name); ?></h2>
			<div class="txt-content txt-xl p">
				<p><?= $desc; ?></p>
			</div>
		</div>
	</section>
	<section class="vc-row vc-row-o bg-white sec-gap">
		<div class="vc-col vc-col-o ctn">	
			<div class="rgc-product-archive " >
				<?php
				if(!empty($products))
				{  
					foreach($products as $product)
					{						
				?>
				<div class="product-item">
					<img class="product-cat-icon" title="Waterproofing" src="<?php echo SITEURL.'productimg/'.$product['Product']['image']?>">
					<a class="product-thumb-link bg-img proimg" href="<?php echo SITEURL.'products/'.$slug.'/'.$product['Product']['slug'];?>" style="background-image:url('<?php echo SITEURL.'productimg/'.$product['Product']['image']?>')"></a>
					<h3 class="product-title"><a class="product-title-link" href="<?php echo SITEURL.'products/'.$slug.'/'.$product['Product']['slug'];?>"><?php echo $product['Product']['title'] ?></a></h3>					
					<a class="btn btn6 product-link" href="<?php echo SITEURL.'products/'.$slug.'/'.$product['Product']['slug'];?>">View Product</a>
				</div>	
				<?php } }else{ ?>	
				<div class="product-item" style="text-align:center; margin-top:50px;">					
					<h1>Coming Soon</h1>					 
				</div>
				<?php } ?>		
			</div>
		</div>
	</section>
	</main>