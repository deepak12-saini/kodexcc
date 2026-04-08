<style>
.sec-gap-btm {
	padding-bottom: 0rem;
}
.bg-green
{		
	background: linear-gradient(to left, #244e6a 0%, #1175b8 50%,#90b7d0 100%);
}
.document-gallery .document-icon-row > .document-icon {
  width: calc((100% - 160px) / 4);
  margin: 0 20px 9rem;
}

<!-- .bg-img {
  background-size: contain;
  margin-top: -8px;
} -->
<!-- @keyframes zoom-in-zoom-out {
  0% {
		transform: scale(1, 1);
  }
  50% {
		transform: scale(1.5, 1.5);
  }
  100% {
		transform: scale(1, 1);
  }
} -->
</style>
<main class="page-body page-body-2">
	
	<section class="vc-row vc-row-o bg-img page-banner"  style="background-image:url(<?php echo SITEURL ;?>wp-content/uploads/product-msds.jpg);">
		<div class="vc-col vc-col-o banner-content ctn ctn-md">	
			<h1 class="hd"></h1>
		</div>
	</section>
	<section class="vc-row vc-row-o sec-gap" >
		<div class="vc-col vc-col-o ctn txt-center txt-block" >	<h2 class="hd txt-green p">Product MSDS</h2>
			<div  class="txt-content txt-xl p" >
				<p>kodex has a specified MSDS for each and every one of our solutions. To download a MSDS please select a category below or search for the product.</p>
			</div>
		</div>
	</section>
	<section class="vc-row vc-row-o sec-gap-btm bg-grey" >
		<div class="vc-col vc-col-o " >
			<div class="wpb_text_column wpb_content_element " >
				<div class="wpb_wrapper">
					<div id="data-sheet-search">
						<div class="search-datasheet-console bg-green">							
							<div class="col col-r col-cat">
								<select id="search-categorys" onchange="shoehidecate(this.value)">
									<option value="0">All Products</option>
									<?php foreach($category as $cats){ ?>
										<option value="<?php echo $cats['Category']['id']; ?>"><?php echo $cats['Category']['category_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="ctn">
							<?php foreach($category as $cats){ ?>							
								<div id="document-gallery-<?php echo $cats['Category']['id']; ?>" class="document-gallery" >
								<div class='document-icon-row'>
									<?php 
									if(!empty($cats['Product'])){ 
										foreach($cats['Product'] as $pdt)
										{
										if(!empty($pdt['msds']))
										{										
										$exp = explode("###",$pdt['msds']);
										
										if(isset($exp) && count($exp) == 1)
										{										
									
										$ptimg_1 = str_replace(".pdf",".jpg",$pdt['msds']);
										$ptimg_1 = str_replace(" ","-",$ptimg_1);
									?>
									<div class="document-icon" data-id="8823">
										<a href="<?php echo $pdt['msds']; ?>" target="_blank" class="proimg">
											<img class="" src="<?php echo $ptimg_1; ?>" title="" data-ext="pdf" />
											<span class="title"><?php echo $pdt['title']; ?></span>
										</a>
									</div>
									<?php 
									}else{
									
									$ptimg_1 = str_replace(".pdf",".jpg",$exp[0]);
									$ptimg_1 = str_replace(" ","-",$ptimg_1);
									?>	
									<div class="document-icon" data-id="8823">
										<a href="<?php echo $exp[0]; ?>" target="_blank" class="proimg">
											<img class="" src="<?php echo $ptimg_1; ?>" title="" data-ext="pdf" />
											<span class="title"><?php echo $pdt['title']; ?> - Part A</span>
										</a>
									</div>
									<?php if(isset($exp[1])){?>
									<div class="document-icon" data-id="8823">
										<a href="<?php echo $exp[1]; ?>" target="_blank" class="proimg">
											<img class="" src="<?php echo $ptimg_1; ?>" title="" data-ext="pdf" />
											<span class="title"><?php echo $pdt['title']; ?> - Part B</span>
										</a>
									</div>									
									<?php } } } } ?>	
								</div>
								</div>	
							<?php } } ?>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		function shoehidecate(id)
		{
			if(id == 0)
			{
				$(".document-gallery").show();
			}else{
				$(".document-gallery").hide();
				$("#document-gallery-"+id).show();
			}
		}
	</script>
	<section class="vc-row vc-row-o " >
		<div class="vc-col vc-col-o " >
			<div class="templatera_shortcode">
				<section class="vc-row vc-row-o sec-gap txt-center bg-img txt-white overlay overlay-green"  style="background-image:url(<?php echo SITEURL ;?>wp-content/uploads/2021/06/enviro27.jpg);">
					<div class="vc-col vc-col-o ctn ctn-lg z-20 txt-block" >	
						<h2 class="hd p" >For More Information</h2>
						<div  class="txt-content p" >
							<p>Our experienced team are here to ensure your project is a success, so if you can’t find what you need here or are searching for further clarification on any of our solutions, please feel free to get in contact with us. Alternatively, you can read through our FAQ’s for some quick answers.</p>
						</div>
						
					</div>
				</section>
			</div>
		</div>
	</section>
	</main><!-- .page-body -->