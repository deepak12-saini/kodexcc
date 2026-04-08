<section id="advertisement">
		<div class="container">
			<img src="<?php echo SITEURL ;?>front_theme/images/shop/advertisement.jpg" alt="" />
		</div>
	</section>
	
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
						<h2>Category</h2>
						<div class="panel-group category-products" id="accordian"><!--category-productsr-->
							<!-- <div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordian" href="#sportswear">
											<span class="badge pull-right"><i class="fa fa-plus"></i></span>
											Sportswear
										</a>
									</h4>
								</div>
								<div id="sportswear" class="panel-collapse collapse">
									<div class="panel-body">
										<ul>
											<li><a href="">Nike </a></li>
											<li><a href="">Under Armour </a></li>
											<li><a href="">Adidas </a></li>
											<li><a href="">Puma</a></li>
											<li><a href="">ASICS </a></li>
										</ul>
									</div>
								</div>
							</div> -->
							<?php if(!empty($categories)){
									foreach($categories as $category){
							?>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title"><a href="<?php echo SITEURL ;?>shop/<?php echo $category['Category']['slug']?>"><?php echo $category['Category']['category_name']?></a></h4>
									</div>
								</div>
							<?php }}?>
							
							
						</div><!--/category-productsr-->
					
						<div class="brands_products" style="display:none;"><!--brands_products-->
							<h2>Brands</h2>
							<div class="brands-name">
								<ul class="nav nav-pills nav-stacked">
									<li><a href=""> <span class="pull-right">(50)</span>Acne</a></li>
									<li><a href=""> <span class="pull-right">(56)</span>Grüne Erde</a></li>
									<li><a href=""> <span class="pull-right">(27)</span>Albiro</a></li>
									<li><a href=""> <span class="pull-right">(32)</span>Ronhill</a></li>
									<li><a href=""> <span class="pull-right">(5)</span>Oddmolly</a></li>
									<li><a href=""> <span class="pull-right">(9)</span>Boudestijn</a></li>
									<li><a href=""> <span class="pull-right">(4)</span>Rösch creative culture</a></li>
								</ul>
							</div>
						</div><!--/brands_products-->
						
						<div class="price-range" style="display:none;"><!--price-range-->
							<h2>Price Range</h2>
							<div class="well">
								 <input type="text" class="span2" value="" data-slider-min="0" data-slider-max="600" data-slider-step="5" data-slider-value="[250,450]" id="sl2" ><br />
								 <b>$ 0</b> <b class="pull-right">$ 600</b>
							</div>
						</div><!--/price-range-->
						
						<div class="shipping text-center"><!--shipping-->
							<img src="<?php echo SITEURL ;?>front_theme/images/home/shipping.jpg" alt="" />
						</div><!--/shipping-->
						
					</div>
				</div>
				
				<div class="col-sm-9 padding-right">
					<div class="features_items"><!--features_items-->
						<!-- <h2 class="title text-center">Features Items</h2> -->
						<?php if(!empty($products)){ 
							  foreach($products as $product){
							  
							  if(!empty($product['Product']['thumb_image']))
							  {
								$product_image=SITEURL.'product/'.$product['Product']['thumb_image'];
							  }else{
								$product_image=SITEURL.'img/default.product.png';
							  }
							  
							  $title_length=strlen($product['Product']['title']);
								if($title_length>80)
								{
									$title=substr($product['Product']['title'],0,60).'...';
								}else{
									$title=$product['Product']['title'];
								}
						?>
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="<?php echo $product_image ;?>" alt="" />
										<h2>
										<?php $curr=$this->Session->read('currency');
											  if(empty($curr))
											  {
												$price_currency='$'.$product['Product']['price_dollar'];
											  }else{
													if($curr=='euro')
													{
														$price_currency='€'.$product['Product']['price_euro'];
													}else{
														$price_currency='$'.$product['Product']['price_dollar'];
													}
											  }
											  
											  echo $price_currency;
										?>
										</h2>
										<p><?php echo $title ;?></p>
										
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
									<!-- <div class="product-overlay">
										<div class="overlay-content">
											<h2><?php echo $price_currency ;?></h2>
											<p><?php echo $title ;?></p>
											<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
											<a href="<?php echo SITEURL ;?>product-detail/<?php echo $product['Product']['product_code']; ?>" class="btn btn-default add-to-cart"><i class="fa fa-search"></i>Detail</a>
										</div>
									</div> -->
								</div>
								<div class="choose" style="display:none;">
									<ul class="nav nav-pills nav-justified">
										<li><a href=""><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
										<li><a href=""><i class="fa fa-plus-square"></i>Add to compare</a></li>
									</ul>
								</div>
							</div>
						</div>
						<?php }} ?>
						
						<?php if(!empty($products)){
						$record_count=$this->Paginator->param('count');
						if($record_count>9){
						?>
						<ul class="pagination">
						<li >
						<?php echo $this->Paginator->prev('< ' . __('Previous'), array(), null, array('class' => 'prev disabled'));?>
						</li>
						<li><?php echo $this->Paginator->numbers(array('class' => 'paginate_button'));?></li>
						<li><?php echo $this->Paginator->next(__('Next') . ' >', array(), null, array('class' => 'next disabled'));?></li>
						</ul>
						<?php }  }?>
					</div><!--features_items-->
				</div>
			</div>
		</div>
	</section>