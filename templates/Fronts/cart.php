<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li class="active">Shopping Cart</li>
				</ol>
			</div>
			<form action="" method="post">
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Item</td>
							<td class="description"></td>
							<td class="price">Price</td>
							<td class="quantity">Quantity</td>
							<td class="total">Total</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
					<?php foreach($itemlist as $itemlists){
							if(!empty($itemlists['Product']['thumb_image']))
							  {
								$product_image=SITEURL.'product/'.$itemlists['Product']['thumb_image'];
							  }else{
								$product_image=SITEURL.'img/default.product.png';
							  }
							  
								$curr=$this->Session->read('currency');
								if(empty($curr))
								{
								$price_currency=$itemlists['Product']['price_dollar'];
								$cart_curr='$';
								}else{
								if($curr=='euro')
								{
								$price_currency=$itemlists['Product']['price_euro'];
								$cart_curr='€';
								}else{
								$price_currency=$itemlists['Product']['price_dollar'];
								$cart_curr='$';
								}
								}
					?>
						<tr>
							<td class="cart_product">
								<a href=""><img src="<?php echo $product_image ;?>" alt="" width="100px"></a>
							</td>
							<td class="cart_description">
								<h4><a href=""><?php echo $itemlists['Product']['title']?></a></h4>
								<p>Product ID: <?php echo $itemlists['Product']['product_code']?></p>
							</td>
							<td class="cart_price">
								<p><?php echo $cart_curr?><?php echo $itemlists['Product']['product_code']?></p>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<!-- <a class="cart_quantity_up" href=""> + </a> -->
									<input class="cart_quantity_input" type="text"  value="<?php echo $itemlists['ShopCart']['quantity']?>" autocomplete="off" size="2"  name="data[quantity][]">
									<!-- <a class="cart_quantity_down" href=""> - </a> -->
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price"><?php echo $cart_curr.$price_currency*$itemlists['ShopCart']['quantity']?></p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" href="<?php echo SITEURL.'fronts/DeleteCartItem/'.$itemlists['ShopCart']['id']; ?>"><i class="fa fa-times"></i></a>
							</td>
							<input  type="hidden" value="<?php echo $itemlists['ShopCart']['product_id']; ?>" name="data[item_id][]">
							<input  type="hidden" value="<?php echo $itemlists['ShopCart']['price']; ?>" name="data[price][]">
							<input  type="hidden" value="<?php echo $itemlists['ShopCart']['id']; ?>" name="data[id][]">
						</tr>
						<?php  } ?>
					</tbody>
				</table>
			</div>
			<div class="cartFooter w100">
                        <div class="box-footer">
                            <div class="pull-left"><a href="<?php echo SITEURL.'shop'; ?>" class="btn btn-default"> <i
                                    class="fa fa-arrow-left"></i> &nbsp; Continue shopping </a></div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-default"><i class="fa fa-undo"></i> &nbsp; Update Cart
                                </button>
                            </div>
                        </div>
                    </div>
			</form>
		</div>
	</section> <!--/#cart_items-->

	<section id="do_action">
		<div class="container">
			<div class="heading">
				<h3>What would you like to do next?</h3>
				<p>Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.</p>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="chose_area">
						<ul class="user_option">
							<li>
								<input type="checkbox">
								<label>Use Coupon Code</label>
							</li>
							<li>
								<input type="checkbox">
								<label>Use Gift Voucher</label>
							</li>
							<li>
								<input type="checkbox">
								<label>Estimate Shipping & Taxes</label>
							</li>
						</ul>
						<ul class="user_info">
							<li class="single_field">
								<label>Country:</label>
								<select>
									<option>United States</option>
									<option>Bangladesh</option>
									<option>UK</option>
									<option>India</option>
									<option>Pakistan</option>
									<option>Ucrane</option>
									<option>Canada</option>
									<option>Dubai</option>
								</select>
								
							</li>
							<li class="single_field">
								<label>Region / State:</label>
								<select>
									<option>Select</option>
									<option>Dhaka</option>
									<option>London</option>
									<option>Dillih</option>
									<option>Lahore</option>
									<option>Alaska</option>
									<option>Canada</option>
									<option>Dubai</option>
								</select>
							
							</li>
							<li class="single_field zip-field">
								<label>Zip Code:</label>
								<input type="text">
							</li>
						</ul>
						<a class="btn btn-default update" href="">Get Quotes</a>
						<a class="btn btn-default check_out" href="">Continue</a>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="total_area">
						<ul>
							<li>Cart Sub Total <span>$59</span></li>
							<li>Eco Tax <span>$2</span></li>
							<li>Shipping Cost <span>Free</span></li>
							<li>Total <span>$61</span></li>
						</ul>
							<a class="btn btn-default update" href="">Update</a>
							<a class="btn btn-default check_out" href="">Check Out</a>
					</div>
				</div>
			</div>
		</div>
	</section><!--/#do_action-->