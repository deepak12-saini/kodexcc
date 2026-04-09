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
									Set your new password 
								</h4>

								<div class="space-6"></div>

								<form action="" method="post">
								<?php echo $this->Session->flash(); ?>
									<fieldset>
										
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												<?php echo $this->Form->input('NappUser.password',array('div'=>false,'label'=>false, 'class' =>array('form-control') ,'placeholder'=>'Password','type' =>'password','id'=>'password'))?>
												<i class="ace-icon fa fa-lock"></i>
											</span>
										</label>

										<div class="space"></div>

										<div class="clearfix">											
											<?php echo $this->Form->submit('Set Password',array('div'=>false,'class'=>'width-35 pull-right btn btn-sm btn-primary'));?>											
										</div>

										<div class="space-4"></div>
									</fieldset>
								<?php echo $this->Form->end();?>

								
							</div><!-- /.widget-main -->

							<div class="toolbar clearfix">
								<div>
									<a href="<?php echo SITEURL.'login'?>" data-target="#forgot-box" class="forgot-password-link">
										<i class="ace-icon fa fa-arrow-left"></i>
										Login
									</a>
								</div>

								<div>
									<a href="<?php echo SITEURL.'register'?>" class="user-signup-link">
										I want to register
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</div>
							</div>
						</div><!-- /.widget-body -->
					</div><!-- /.login-box -->

				</div><!-- /.position-relative -->

			
			</div>
		</div><!-- /.col -->
	</div><!-- /.row -->
	<script type="text/javascript">
	jQuery(function(){


	jQuery("#Cname").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter name"
	}); 
	jQuery("#Clname").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter last name"
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