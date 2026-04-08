	
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
								<?php echo $this->Session->flash(); ?>
									<fieldset>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('Feedback.customer_name',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter  Customer Name','id'=>'customer_name'))?>
												
											</span>
											</label>
											<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('Feedback.company_name',array('div'=>false,'label'=>false,'type'=>'text','class' => array('form-control'),'placeholder'=>'Enter Company Name','id'=>'company_name'))?>
												
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('Feedback.contact',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter Phone Number','id'=>'phone'))?>
											
											</span>
										</label>

										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('Feedback.sample_given',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter Given Sample','id'=>'sample_given'))?>
												
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('Feedback.feedback',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter Feedback','id'=>'feedback'))?>
											
											</span>
										</label>
										<label class="block clearfix">
											<span class="block input-icon input-icon-right">
												
												<?php echo $this->Form->input('Feedback.addedby',array('div'=>false,'label'=>false, 'class' => array('form-control'),'placeholder'=>'Enter Added by','id'=>'addedby'))?>
											
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
	jQuery("#customer_name").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter customer name"
	}); jQuery("#addedby").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter added by"
	}); jQuery("#sample_given").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter sample given"
	}); jQuery("#feedback").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter feedback"
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

$.get("https://ipinfo.io/json", function (response) {
	
	var opeurl = '<?php echo SITEURL ?>fronts/analytics?ip='+response.ip+'&city='+response.city+'&state='+response.region+'&country='+response.country+'&loc='+response.loc+'&postal='+response.postal;

     $.ajax({url: opeurl, success: function(result){
		 console.log(result);
	  }});
  
}, "jsonp");
</script>