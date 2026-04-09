<?php
	
	if($type == 'datasheet'){
		$datasheet = explode("##",$data['Product']['datasheet']);
		
		$url = $datasheet[$num];
		$name = 'Datasheet';
	
	}
	if($type == 'msds'){
		$msds = explode("##",$data['Product']['msds']);
		$url = $msds[$num];
		$name = 'MSDS';
	}
	if($type == 'voc'){
		$voc = explode("##",$data['Product']['voc']);
		$url = $voc[$num];
		$name = 'VOC';
	}
	if($type == 'label'){
		$label = explode("##",$data['Product']['label']);
		$url = $label[$num];
		$name = 'Label';
	}
	//$url  = str_replace(' ','%20',$url);
	//$url  = str_replace('http:','https:',$url);
?>

<div class="page-content">
	<div class="page-header">
	<h1>
	 Product Documents >> <small> <?php echo $data['Product']['title'].' '.$name; ?>	</small> <a href="<?php echo SITEURL.'staffs/datasheet'?>" class="btn btn-xs btn-inverse" style="float:right;">Back</a>
	</h1>
	</div><!-- /.page-header -->
	
	
	
	<?php //if($is_access == 0){ ?>
	<div class="row">
		<div class="col-xs-12">
			
			<form action="" method="post" >
				<div class="box">
					<div class="box-header">				  
					</div>					
					<div class="box-body">
						<?php if(!empty($url)){ ?>
							<iframe src="<?php echo $url ?>" style="width:100%; height:530px; border:none;" allowTransparency="true" scrolling="no" frameborder="0"  allow-top-navigation="no"></iframe>
						<?php }else{ ?>
							<h3> Sorry Document Not Found.</h3>
						<?php }?>
					</div>
				</div>
			</form>
		</div>		
	</div>
	<?php /* }else{ ?>
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
	<?php } */ ?>
</div>	

<script type="text/javascript">
	jQuery(function(){ 
		$("#otp").validate({
			 expression: "if (VAL) return true; else return false;",
			message: "Please enter otp"
		});
	});
</script>	
	