<section id="form"><!--form-->
		<div class="container">
			<div class="row">
				<?php echo $this->Session->flash();?>
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Forgot Password</h2>
						<?php echo $this->Form->create('Customer',array('class'=>'')); ?>
							<?php echo $this->Form->input('Customer.email',array('type'=>'email','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'email','placeholder'=>'Email Address'))?>
						
							<!-- <span>
								<input type="checkbox" class="checkbox"> 
								Keep me signed in
							</span> -->
							<button type="submit" name="submit" class="btn btn-default">Submit</button>  <a href="<?php echo SITEURL ;?>login">Login</a>
						<?php echo $this->Form->end(); ?>
					</div><!--/login form-->
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
				
				
			});
			</script>