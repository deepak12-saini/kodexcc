<style>
.form-group {
    margin-bottom: 23px;
}
table tr td { font-size: 20px; font-weight:bold;}
</style>
<div class="page-content">
	<div class="page-header">
	<h1>
	Architect CPD Presentation Management
	<small>
	<i class="ace-icon fa fa-angle-double-right"></i>
	Architect CPD Questionnaire
	</small>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
		
		 <form action="" method="post" >
          <div class="box">
				<div class="box-header">
					<h3 class="box-title" style="width:100%;">
						<!-- <input type="submit" name="submit" id="formSubmit" value="Save" class="btn btn-primary" style="float:right;"> -->
					</h3>			  			  
				</div>
				<?php if(($user['NappUser']['is_cpd_presentation'] == 1)  ||  ($is_access == 2)){ ?>
				<?php	$nums = count($question);	 ?>
				<div class="box-body">
				  <table  class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>Question (<span id="number">1/<?php echo $nums ?></span>)</th>							  
					</tr>
					</thead>
					<tbody>
					<?php								
						$i = 1;						
						foreach($question as $fetch){
							
					?>
						<input id="questype_<?php echo $i; ?>" type="hidden" value="<?php echo $fetch['Question']['type']; ?>" >		
						<tr id="question_<?php echo $i; ?>" <?php if($i > 1){ ?> style="display:none;"  <?php  } ?> class="question"  >
							<td>
							<?php echo $i.'. &nbsp;&nbsp;<b style="color:#3C8DBC">'.$fetch['Question']['title'].'</b>'; ?> <br><br>
								<?php 
									$options = array();		
									$j=0;									
									foreach($fetch['QuestionOption'] as $option){	
								?>
								<input id="ans_<?php echo $i.'_'.$j; ?>" type="hidden" value="<?php echo $option['is_correct']; ?>" >			
								<input id="title_<?php echo $i.'_'.$j; ?>" type="hidden" value="<?php echo $option['title']; ?>" >			
								<input id="imagetitle_<?php echo $i.'_'.$j; ?>" type="hidden" value="<?php echo $option['imagetitle']; ?>" >			
									
								<?php
																		
									$select  = '';								
									/*if(in_array($option['id'],$options)){
										$select  = 'checked';
									}*/									
									if($fetch['Question']['type'] == 1){										
								?>								
								<p>
									<div class="checkbox">
										<label class="block">
											<input class="ace input-lg questions_<?php echo $i; ?>" <?php echo $select; ?> value="<?php echo $option['id'] ?>" name="option[<?php echo $fetch['Question']['id'] ?>][<?php echo $option['id'] ?>]" type="checkbox">
											<span class="lbl bigger-120"> <?php echo $option['title'] ?></span>
										</label>
									</div>
								
									<!-- &nbsp; <input class="questions_<?php echo $i; ?>" <?php echo $select; ?> type="checkbox" value="<?php echo $option['id'] ?>" name="option[<?php echo $fetch['Question']['id'] ?>][<?php echo $option['id'] ?>]"> &nbsp;&nbsp;&nbsp; <?php echo $option['title'] ?> -->
								</p>	
								<?php  }else if($fetch['Question']['type'] == 2){ ?>
									<p>
										<?php echo $option['title'] ?>
										<div class="radio">
											<label>
												<input <?php if(in_array($option['id'].'_true',$options)){ echo 'checked'; } ?> value="<?php echo $option['id'].'_true' ?>" checked name="option[<?php echo $fetch['Question']['id'] ?>][<?php echo $option['id'] ?>]"  class="ace input-lg questions_<?php echo $i; ?> radio_<?php echo $j; ?>" type="radio">
												<span class="lbl bigger-120"><b>True</b></span>
											</label>
										</div>
										
										<!-- <input class="questions_<?php echo $i; ?> radio_<?php echo $j; ?>"  type="radio" <?php if(in_array($option['id'].'_true',$options)){ echo 'checked'; } ?> value="<?php echo $option['id'].'_true' ?>" name="option[<?php echo $fetch['Question']['id'] ?>][<?php echo $option['id'] ?>]"> &nbsp; <b>True</b> -->
										
										<div class="radio">
											<label>
												<input <?php if(in_array($option['id'].'_false',$options)){ echo 'checked';  } ?>  value="<?php echo $option['id'].'_false' ?>" name="option[<?php echo $fetch['Question']['id'] ?>][<?php echo $option['id'] ?>]" class="ace input-lg questions_<?php echo $i; ?> radio_<?php echo $j; ?>" type="radio">
												<span class="lbl bigger-120"><b>False</b></span>
											</label>
										</div>
										
										<!-- &nbsp;<input class="questions_<?php echo $i; ?> radio_<?php echo $j; ?>"  type="radio" <?php if(in_array($option['id'].'_false',$options)){ echo 'checked';  } ?>  value="<?php echo $option['id'].'_false' ?>" name="option[<?php echo $fetch['Question']['id'] ?>][<?php echo $option['id'] ?>]">  &nbsp; <b>False</b>
										 &nbsp;&nbsp;&nbsp;   -->
										
									</p>
								<?php  }else if($fetch['Question']['type'] == 3){ ?>								
									<p>
									<div class="checkbox">
										<label class="block">
											<input class="ace input-lg questions_<?php echo $i; ?>" <?php echo $select; ?> value="<?php echo $option['id'] ?>" name="option[<?php echo $fetch['Question']['id'] ?>][<?php echo $option['id'] ?>]" type="checkbox">
											<span class="lbl bigger-120"> <img src="<?php echo $option['title'] ?>" width="300"/> <?php echo $option['imagetitle'] ?></span>
										</label>
									</div>
									
									<!-- &nbsp;<input class="questions_<?php echo $i; ?>" <?php echo $select; ?> type="checkbox" value="<?php echo $option['id'] ?>" name="option[<?php echo $fetch['Question']['id'] ?>][<?php echo $option['id'] ?>]"> &nbsp;&nbsp;&nbsp;  

										<img src="<?php echo $option['title'] ?>" width="300"/> -->
									</p>
								<?php } $j++; } ?>
								
							</td>					
							
						</tr>
					<?php  $i++; } ?>

						<tr id="question_<?php echo $nums+1; ?>"  class="question" style="display:none;">
							<td>
								<h2 style="text-align:center;text-decoration:underline;">Questionairre Completion Form </h2>
							
								<br/>							
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label" style="text-align:right;">First Name: </label>
									<div class="col-sm-6">
									 <input type="text" class="form-control" placeholder="First Name" value="" id="name" name="name" style="width:300px;">	
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label" style="text-align:right;">Last Name: </label>
									<div class="col-sm-6">
									 <input type="text" class="form-control" placeholder="Last Name" value="" id="lname" name="lname" style="width:300px;">	
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label" style="text-align:right;">Email: </label>
									<div class="col-sm-6">
									 <input type="email" class="form-control" placeholder="Email" value="" id="email" name="email" style="width:300px;">
									
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label" style="text-align:right;"> Mobile Number: </label>
									<div class="col-sm-6">
									 <input type="text" class="form-control" placeholder="Mobile Number" value="" id="mobile" name="phone" style="width:300px;">
									
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label" style="text-align:right;">Landline No: </label>
									<div class="col-sm-6">
									 <input type="text" class="form-control" placeholder="Landline No" value="" id="landlineno" name="landlineno" style="width:300px;">
									
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label" style="text-align:right;">Company Name: </label>
									<div class="col-sm-6">
									 <input type="text" class="form-control" placeholder="Company Name" value="" id="company" name="company" style="width:300px;">
									
									</div>
								</div>
								<br>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label" style="text-align:right;"> Company Address: </label>
									<div class="col-sm-6">
									 <input type="text" class="form-control" placeholder="Company Address" value="" id="company_address" name="company_address" style="width:300px;">
									
									</div>
								</div>
								<br>
								
								
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label" style="text-align:right;">&nbsp;</label>
									<div class="col-sm-6">
									 <input type="submit" name="submit"  class="btn btn-primary" value="Submit" >
									
									</div>
								</div>
							</td>
						</tr>	
					</tbody>
					<tfoot>
						<tr>
							<td>
							<input type="hidden" id="qestionnumber" value="1">
							<a id="btnClick" class="btn btn-primary" style="float:right;">Click me!</a></td>					
							
						</tr>
					</tfoot>
				  </table>
				</div>
				<?php }else  if($is_access == 0){ ?>
					<h3> Sorry, you don't have access to this folder. You can request the admin to give you access. <a href="<?php echo SITEURL.'users/access/0'; ?>">Click here</a></h3>
				<?php }?>
			</form>
			
			
			<?php if(($user['NappUser']['is_cpd_presentation'] == 0) && ($is_access == 0)){ ?>
			<br><br>
			<div class="durolab_product_section col-xs-12">		
				<div class="span3 widget-container-span ui-sortable">
					<div class="widget-box light-border">
					
						<div class="widget-body">
							<div class="widget-main padding-6">
								
								<div class="alert alert-danger" style=" font-size:20px; padding:50px; ">To gain access to this document <a href="<?php echo SITEURL.'/users/questionlist/otp'?>">Click Here</a> to receive a OTP(one time password) and gain access to the document.</div></a>
								
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
</div>
<script type="text/javascript">
jQuery(function(){ 
	$("#name").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter first name"
	});$("#lname").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter last name"
	});$("#email").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter email"
	});jQuery("#email").validate({
		expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
		message: "Please enter a valid Email ID"
	});$("#mobile").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter mobile number"
	});jQuery("#mobile").validate({
		expression: "if (VAL.match(/^[0-9]*$/) && VAL) return true; else return false;",
		message: "Please enter a valid integer"
	});jQuery("#mobile").validate({
		expression: "if (VAL.length==10  && VAL) return true; else return false;",
		message: "Please enter a valid 10 digit number"
	});$("#landlineno").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter landline  number"
	});$("#company").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter company  name"
	});$("#company_address").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter company address"
	});
});	
	
$('#btnClick').on('click',function(){
	
	var qestionnumber = $("#qestionnumber").val();
	qestionnumber = parseInt(qestionnumber);
	
	var checkboxes = document.getElementsByClassName('questions_'+qestionnumber);
	var questype = $("#questype_"+qestionnumber).val();	
	if(questype == 2){
		
		var total = checkboxes.length / 2;
		
		for (var i=0, n=total;  i<n;i++) 
		{
			var ans = $("#ans_"+qestionnumber+"_"+i).val();
			var title = $("#title_"+qestionnumber+"_"+i).val();
			
			if ($(".radio_"+i).prop("checked")) {
				var vals = 1;				
			}else{
				var vals = 0;
			}
			
			if(vals != ans){
				if(ans == 1){
					alert('You can select True option : '+title);
				}else{
					alert('You can select False  option : '+title);
				}						
				return false;
			}		
					
		}	
		
	}else{	
		for (var i=0, n=checkboxes.length;  i<n;i++) 
		{ 
			var ans = $("#ans_"+qestionnumber+"_"+i).val();
			if(questype == 3){
				var title = $("#imagetitle_"+qestionnumber+"_"+i).val();
			}else{	
				var title = $("#title_"+qestionnumber+"_"+i).val();
			}	
			if (checkboxes[i].checked) 
			{
				var vals = 1;
			}else{
				var vals = 0;
			}	
			if(vals != ans){
				if(ans == 0){
					alert('You can deselect the wrong   option : '+title);
				}else{
					alert('You can select the correct option : '+title);
				}	
					
				return false;
			}	
		} 
	} 
	
	if(qestionnumber <= <?php echo $nums; ?>){		
		if(qestionnumber == <?php echo $nums; ?>){
			$("#btnClick").hide();
			
			//$("#formSubmit").trigger('click');
		}
		var qestiono  = qestionnumber + 1;
		$(".question").hide();
		$("#question_"+qestiono).show();
		$("#qestionnumber").val(qestiono);
		$("#number").text(qestiono+'/<?php echo $nums ?>');		
		
	} 
	
		
});
</script>	