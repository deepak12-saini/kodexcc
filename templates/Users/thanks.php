<div class="page-content">
	<div class="page-header">
	<h1>
	Natspec Presentation Management
	<small>
	<i class="ace-icon fa fa-angle-double-right"></i>
		Natspec Presentation
	</small>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			  <form action="" method="post" >
				  <div class="box">
						<div class="box-header">
						
						  <?php if(!empty($message)){ ?>
								<div style="color:green; font-weight:bold; font-size:20px;"><?php if(isset($message)){ echo $message; } ?></div>
							<?php  } ?>
						</div>
						
						<div class="box-body">
						 <div class="thankyou_container" style="width: 1170px;margin: 0 auto;text-align: center;">
							   <div class="thanku_page">
							   <h3 style="color: #2889c9;font-family: lato,arial;font-size: 45px;margin-bottom: 15px;">Thank You</h3>
							   <p style="font-size: 19px;font-family: lato,sans-serif;line-height: 30px;margin-top: 0px;">You have now successfully completed the Durotech Questionnaire!
								<br>You can proceed further to fill your details and obtain your certificate.</p>
							   <a  target="_blank" href="<?php echo SITEURL.'certificate.php?userid='.$custname ?>" style="background: #2889c9;color: #fff;text-decoration: none;padding: 9px 31px;font-size: 18px;font-family: lato,sans-serif;font-weight: bold;border-radius:3px;">Download certificate</a>
							 
							  </div>
						  
						  </div>
						</div>
					</form>
		</div>
		
	</div>
</div>	