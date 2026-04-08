<section id="form"><!--form-->
		<div class="container">
			<div class="row">
				<?php echo $this->Flash->render();?>
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Login to your account</h2>
						<?php echo $this->Form->create('Front',array('class'=>'')); ?>
							<?php echo $this->Form->input('Customer.email',array('type'=>'email','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'email','placeholder'=>'Email Address'))?>
							<?php echo $this->Form->input('Customer.password',array('type'=>'password','div'=>false,'label'=>false,'id'=>'password','placeholder'=>'Password'))?>
							<!-- <span>
								<input type="checkbox" class="checkbox"> 
								Keep me signed in
							</span> -->
							<button type="submit" name="login" class="btn btn-default">Login</button>  <a href="<?php echo SITEURL ;?>forgot-password">Forgot Password?</a>
						<?php echo $this->Form->end(); ?>
					</div><!--/login form-->
				</div>
				<div class="col-sm-1">
					<h2 class="or">OR</h2>
				</div>
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
						<h2>New User Signup!</h2>
						<?php echo $this->Form->create('Front',array('class'=>'')); ?>
							
							<?php echo $this->Form->input('Customer.fname',array('type'=>'text','label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'fname','placeholder'=>'First Name'))?>
							<?php echo $this->Form->input('Customer.lname',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'lname','placeholder'=>'Last Name'))?>
							<?php echo $this->Form->input('Customer.email',array('type'=>'email','label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'signup_email','placeholder'=>'Email Address'))?>
							<?php echo $this->Form->input('Customer.password',array('type'=>'password','div'=>false,'label'=>false,'id'=>'signup_password','placeholder'=>'Password'))?>
							<?php echo $this->Form->input('Customer.confirm_password',array('type'=>'password','div'=>false,'label'=>false,'id'=>'confirm_password','placeholder'=>'Confirm Password'))?>
							<button type="submit" name="signup" class="btn btn-default">Signup</button>
						<?php echo $this->Form->end(); ?>
					</div><!--/sign up form-->
				</div>
			</div>
		</div>
	</section><!--/form-->
	
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