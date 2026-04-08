	<link rel="stylesheet" type="text/css" media="screen"
     href="<?php echo SITEURL; ?>datetimepicker/bootstrap-datetimepicker.css">
	
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
									TMC: New Lead
								</h4>

								<div class="space-6"></div>

								<form action="" method="post">
									<?php echo $this->Flash->render(); ?>
									<fieldset>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<input name="callername" class="form-control ErrorField" placeholder="caller name" id="callername" type="text">
												<i class="ace-icon fa fa-user"></i>
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">								
												<input name="calltime" class="datepicker form-control ErrorField" placeholder="Call Time" id="calltime" type="text">
												<i class="ace-icon fa fa-calendar"></i>
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">								
												<input name="phone" class="form-control ErrorField" placeholder="Phone / Mobile Number" id="phone" type="text">
												<i class="ace-icon fa fa-mobile"></i>
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">								
												<textarea name="message" class="form-control ErrorField" placeholder="Message" id="message" ></textarea>
												<i class="ace-icon fa fa-envelope"></i>
											</span>
										</label>
										
										<div class="space"></div>

										<div class="clearfix">
											<!--label class="inline">
												<input type="checkbox" class="ace" />
												<span class="lbl"> Remember Me</span>
											</label-->
												<?php echo $this->Form->submit('Send',array('div'=>false,'class'=>'width-35 pull-right btn btn-sm btn-primary'));?>
											<!--button type="button" class="width-35 pull-right btn btn-sm btn-primary">
												<i class="ace-icon fa fa-key"></i>
												<span class="bigger-110">Login</span>
											</button-->
										</div>
			
										<div class="space-4"></div>
									</fieldset>
								</form>

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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
	<script type="text/javascript"     src="<?php echo SITEURL; ?>datetimepicker/bootstrap-datetimepicker.min.js">
    </script>
	<script type="text/javascript">
	jQuery(function(){
	
	$('.datepicker').datetimepicker();
	
	jQuery("#callername").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter caller name"
	}); jQuery("#phone").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter phone number"
	});jQuery("#phone").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter phone number"
	});jQuery("#message").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter message"
	});
	});

	</script>