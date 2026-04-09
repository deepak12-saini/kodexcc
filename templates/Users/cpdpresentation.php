<style>
.overlay {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0, 0.9);
    overflow-x: hidden;
    transition: 0.5s;
}

.overlay-content {
    position: relative;
    top: 25%;
    width: 100%;
    text-align: center;
    margin-top: 30px;
}

.overlay a {
    padding: 8px;
    text-decoration: none;
    font-size: 36px;
    color: #818181;
    display: block;
    transition: 0.3s;
}

.overlay a:hover, .overlay a:focus {
    color: #f1f1f1;
}

.overlay .closebtn {
    position: absolute;
    top: 20px;
    right: 45px;
    font-size: 60px;
}

@media screen and (max-height: 450px) {
  .overlay a {font-size: 20px}
  .overlay .closebtn {
    font-size: 40px;
    top: 15px;
    right: 35px;
  }
}
</style>
<div id="myNav" class="overlay">
<a onclick="closeNav()" style="color:#fff; float:right; font-size:20px; cursor:pointer;">X</a>
 <iframe src="<?php echo SITEURL.'wp-content/uploads/New Refuel CPD presentation.pdf'?>" style="width:100%; height:590px;; border:none;"></iframe>										
</div>


<script>
function openNav() {
    document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
    document.getElementById("myNav").style.width = "0%";
}
</script>
<div class="page-content">
	<div class="page-header">
	<h1>
	CPD Presentation
	<small>
	<i class="ace-icon fa fa-angle-double-right"></i>
		CPD Presentation
	</small>
	<?php if(($user['NappUser']['is_natspec_presentation'] == 1)  ||  ($is_access == 2)){ ?>
		<span class="btn btn-mini btn-inverse" onclick="openNav()" style="float:right;"> Open in Full Screen</span>
	<?php }?>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			
			<form action="" method="post" >
				<div class="box">
					<div class="box-header">				  
					</div>					
					<div class="box-body">
						<?php if(($user['NappUser']['is_natspec_presentation'] == 1)  ||  ($is_access == 2)){ ?>
							<iframe src="<?php echo SITEURL.'wp-content/uploads/New Refuel CPD presentation.pdf'?>" style="width:100%; height:530px; border:none;"></iframe>
						<?php }else  if($is_access == 0){ ?>
							<h3> Sorry, you don't have access to this folder. You can request the admin to give you access. <a href="<?php echo SITEURL.'users/access/1'; ?>">Click here</a></h3>
						<?php }?>
					</div>
				</div>
			</form>
			
			<?php if(($user['NappUser']['is_natspec_presentation'] == 0) && ($is_access == 0)){ ?>
			<br><br>
			<div class="durolab_product_section col-xs-12">		
				<div class="span3 widget-container-span ui-sortable">
					<div class="widget-box light-border">
					
						<div class="widget-body">
							<div class="widget-main padding-6">
								
								<div class="alert alert-danger" style=" font-size:20px; padding:50px; ">To gain access to this document <a href="<?php echo SITEURL.'/users/cpdpresentation/otp'?>">Click Here</a> to receive a OTP(one time password) and gain access to the document.</div></a>
								
							</div>
						</div>
					</div>
				</div>

			</div>
			<?php }else if($is_access == 1){ ?>
			
			<div class="row">	
				<div class="col-xs-12">
				
					<form action="" class="form-horizontal" id="" method="post" accept-charset="utf-8">
						<?php if(!empty($usernewArr['NappUser']['email']) && !empty($usernewArr['NappUser']['mobile_number']) && ($usernewArr['NappUser']['is_active_otp'] > 0) ){ ?>
							<p><b>Form enhaced security of your account, we have sent One-Time Password (OTP) to your mobile number <?php echo $usernewArr['NappUser']['mobile_number'] ?> and email Id <?php echo $usernewArr['NappUser']['email'] ?></b></p>	
						<?php }else { ?>
							<p><b>Form enhaced security of your account, we have sent One-Time Password (OTP) to you email Id <?php echo $usernewArr['NappUser']['email'] ?></b></p>	
						<?php  } ?>
						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Please enter One Time Password (OTP): </label>
							<div class="col-sm-9">
								<input name="data[otp]" class="col-xs-10 col-sm-5 ErrorField" id="otp" placeholder="Fill OTP received on mobile / email" type="password">						
							</div>
						</div>				
						<div class="form-group">
							<div class="col-md-offset-3 col-md-9">
								<input class="btn btn-success" id="add_ser_prd_btn" value="Verify" type="submit">&nbsp;							
							</div>
						</div>
					</form>
					<p class="alert alert-warning"><b>Please check your mail. Enter one time pssword received on your registered email address.</b> </p>
				</div>		
			</div>
			<?php } ?>
		</div>
		
	</div>
</div>	
