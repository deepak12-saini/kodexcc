	<section>
		<div class="container">
			<div class="row">
			<?php echo $this->Session->flash();?>
				<div class="col-sm-12">
					<div class="blog-post-area">
						<h3>My Account</h3>
						<div class="post-meta">
								<ul>
									<li><i class="fa fa-user"></i> Edit Profile</li>
									<!-- <li><i class="fa fa-clock-o"></i> Change Password</li>
									<li><i class="fa fa-calendar"></i> Order History</li> -->
								</ul>
						</div>
						<div class="replay-box">
						<div class="row">
							<div class="col-sm-4">
								<h2>Edit Personal Information</h2>
								<?php echo $this->Form->create('Customer',array('class'=>'')); ?>
									<div class="blank-arrow">
										<label>First Name</label>
									</div>
									<span>*</span>
									<?php echo $this->Form->input('Customer.fname',array('type'=>'text','label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'fname','placeholder'=>'First Name','value'=>$Customer['Customer']['fname']))?>
									<div class="blank-arrow">
										<label>Last Name</label>
									</div>
									<span>*</span>
									<?php echo $this->Form->input('Customer.lname',array('type'=>'text','label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'lname','placeholder'=>'Last Name','value'=>$Customer['Customer']['lname']))?>
									<div class="blank-arrow">
										<label>Email Address</label>
									</div>
									<span>*</span>
									<?php echo $this->Form->input('Customer.email',array('type'=>'email','label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'signup_email','placeholder'=>'Email Address','disabled'=>true,'value'=>$Customer['Customer']['email']))?>
									<div class="blank-arrow">
										<label>Phone Number</label>
									</div>
									<?php echo $this->Form->input('Customer.phoneno',array('type'=>'text','label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'phoneno','placeholder'=>'Phone Number','value'=>$Customer['Customer']['phoneno']))?>
									<div class="blank-arrow">
										<label>Postal Code</label>
									</div>
									<?php echo $this->Form->input('Customer.postal_code',array('type'=>'text','label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'postal_code','placeholder'=>'Postal Code','value'=>$Customer['Customer']['postal_code']))?>
									
									<div class="blank-arrow">
										<label>City</label>
									</div>
									<?php echo $this->Form->input('Customer.city',array('type'=>'text','label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'city','placeholder'=>'City','value'=>$Customer['Customer']['city']))?>
									<div class="text-area">
									<div class="blank-arrow">
										<label>Address</label>
									</div>
									<textarea name="data[Customer][address]" id="address" rows="4"><?php echo $Customer['Customer']['address']?></textarea>
									<button type="submit" class="btn btn-primary">Submit</button>
									</div>
									
								<?php echo $this->Form->end(); ?>
							</div>
							<!-- <div class="col-sm-8">
								<div class="text-area">
									<div class="blank-arrow">
										<label>Your Name</label>
									</div>
									<span>*</span>
									<textarea name="message" rows="11"></textarea>
									<a class="btn btn-primary" href="">post comment</a>
								</div>
							</div> -->
						</div>
					</div><!--/Repaly Box-->
					</div>
				</div>
			</div>
		</div>
	</section>	
	
	<script type="text/javascript">
			jQuery(function(){ //short for $(document).ready(function(){
	

				$("#email").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter email"
                });$("#email").validate({
					expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
					message: ""
					});$("#password").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter password"
                });
				
				$("#fname").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter first name"
                });$("#lname").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter last name"
                });
				$("#signup_email").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter email"
                });$("#signup_email").validate({
					expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
					message: ""
					});$("#signup_password").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter password"
                });$("#confirm_password").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter confirm password"
                }); $("#confirm_password").validate({
					expression:"if ((VAL == jQuery('#signup_password').val()) && VAL) return true; else return false;",
					message:"Confirm password field doesn't match the password field"});
			});
			</script>