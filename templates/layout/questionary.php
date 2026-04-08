	
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
			<div class="login-container">
				<div class="center">
					<h1>
						<a href="<?php echo SITEURL; ?>"><img src="<?php echo SITEURL; ?>customdurotech/images/durotech_logo.png"/></a>
					</h1>
					
				</div>

				<div class="space-6"></div>

				<div class="position-relative">
					<div id="login-box" class="login-box visible widget-box no-border">
						<div class="widget-body">
							<div class="widget-main">
								<h4 class="header blue lighter bigger">
									<i class="ace-icon fa fa-coffee green"></i>
									Please Enter Your Information
								</h4>

								<div class="space-6"></div>

								<?php echo $this->Form->create('Front',array('action'=> '','name'=>'loginForm','id'=>'loginForm'));?>
								<?php echo $this->Flash->render(); ?>
									<fieldset>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('name',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter Name','id'=>'name'))?>
												
											</span>
										</label>

										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('phone',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter Phone Number','id'=>'phone'))?>
											
											</span>
										</label>

										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('email',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter email','id'=>'emails'))?>
												
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('occupation',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter occupation','id'=>'occupations'))?>
											
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('existing',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter existing product using','id'=>'existing'))?>
											
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('location',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter location','id'=>'location'))?>
											
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<input type="radio" checked name="data[Front][interest]" value="Strong Interest"> Strong Interest<br>
												<input type="radio" name="data[Front][interest]" value="Some Interest"> Some Interest<br>
												<input type="radio" name="data[Front][interest]" value="Very Litle Interest"> Very Litle Interest<br>
												
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('addedby',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter added by','id'=>'addedby'))?>
											
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												
											</span>
										</label>
										
										<div class="space"></div>

										<div class="clearfix">
											<!--label class="inline">
												<input type="checkbox" class="ace" />
												<span class="lbl"> Remember Me</span>
											</label-->
												<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'width-35 pull-right btn btn-sm btn-primary'));?>
											<!--button type="button" class="width-35 pull-right btn btn-sm btn-primary">
												<i class="ace-icon fa fa-key"></i>
												<span class="bigger-110">Login</span>
											</button-->
										</div>

										<div class="space-4"></div>
									</fieldset>
								<?php echo $this->Form->end();?>

								<!--div class="social-or-login center">
									<span class="bigger-110">Or Login Using</span>
								</div>

								<div class="space-6"></div>

								<div class="social-login center">
									<a class="btn btn-primary">
										<i class="ace-icon fa fa-facebook"></i>
									</a>

									<a class="btn btn-info">
										<i class="ace-icon fa fa-twitter"></i>
									</a>

									<a class="btn btn-danger">
										<i class="ace-icon fa fa-google-plus"></i>
									</a>
								</div-->
							</div><!-- /.widget-main -->

							
						</div><!-- /.widget-body -->
					</div><!-- /.login-box -->


				</div><!-- /.position-relative -->

			
			</div>
		</div><!-- /.col -->
	</div><!-- /.row -->
	<script type="text/javascript">
	jQuery(function(){


	jQuery("#addedby").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter added by"
	}); jQuery("#name").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter name"
	}); jQuery("#is_staff_id").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select register type"
	});jQuery("#occupation").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter occupation"
	});
	jQuery("#location").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter location"
	}); 
	jQuery("#password").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter password"
	});	jQuery("#Cpassword").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter password"
	}); jQuery("#uemail").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter email"
	});jQuery("#uemail").validate({
		expression: "if (VAL.match(\/^([a-zA-Z0-9_\\.\\-])+\\@(([a-zA-Z0-9\\-])+\\.)+([a-zA-Z0-9]{2,4})+$\/) && VAL) return true; else return false;",
		message: "Please enter valid email"
	}); jQuery("#email").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter email"
	});jQuery("#email").validate({
		expression: "if (VAL.match(\/^([a-zA-Z0-9_\\.\\-])+\\@(([a-zA-Z0-9\\-])+\\.)+([a-zA-Z0-9]{2,4})+$\/) && VAL) return true; else return false;",
		message: "Please enter valid email"
	}); 			


	});

	</script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132219331-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-132219331-2');
</script>