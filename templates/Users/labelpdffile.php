<div class="page-content">
	<div class="page-header">
	<h1>
	<?php echo $LabFile['LabFile']['label'];?> Price PDF

	<a href="<?php echo SITEURL.'users/labfile'?>" class="btn btn-xs btn-inverse" style="float:right;">Back</a>
	</h1>
	</div><!-- /.page-header -->
	
	<?php if($is_access == 0){ ?>
	<div class="row">
		<div class="col-xs-12">
			
			<form action="" method="post" >
				<div class="box">
					<div class="box-header">				  
					</div>					
					<div class="box-body">
						<?php if(!empty($LabFile['LabFile']['filename'])){ ?>
							<iframe src="<?php echo SITEURL.$LabFile['LabFile']['dir'].'/'.$LabFile['LabFile']['filename'] ?>" style="width:100%; height:530px; border:none;" allowTransparency="true" scrolling="no" frameborder="0"  allow-top-navigation="no"></iframe>
						<?php }else{ ?>
							<h3> Sorry, you don't have access to this folder. You can request the admin to give you access. <a href="<?php echo SITEURL.'users/access/1'; ?>">Click here</a></h3>
						<?php }?>
					</div>
				</div>
			</form>
		</div>		
	</div>
	<?php }else{ ?>
	<div class="row">
		<div class="col-xs-12">
		
			<form action="" class="form-horizontal" id="" method="post" accept-charset="utf-8">						
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> One Time Password (OTP): </label>
					<div class="col-sm-9">
						<input name="data[otp]" class="col-xs-10 col-sm-5 ErrorField" id="otp" placeholder="One Time Password (OTP)" type="password">						
					</div>
				</div>				
				<div class="form-group">
					<div class="col-md-offset-3 col-md-9">
						<input class="btn btn-success" id="add_ser_prd_btn" value="Submit" type="submit">&nbsp;							
					</div>
				</div>
			</form>
			<p class="alert alert-warning"><b>Please check your mail. Enter one time pssword received on your registered email address.</b> </p>
		</div>		
	</div>
	<?php } ?>
</div>	

<script type="text/javascript">
	jQuery(function(){ 
		$("#otp").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter otp"
		});
	});
</script>	
	