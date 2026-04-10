	<style>
	select {
		padding: 3px 4px;
		height: 30px;
		font-size: 12px;
	}
	</style>
	<div class="page-content">
		<div class="page-header">
			<div class="right_btn pull-right" ><a href="<?php echo SITEURL.'admin/sales'?>" class="btn btn-inverse" >Back</a></div>
			<h1>DuroTeam <small><i class="ace-icon fa fa-angle-double-right"></i> Set Target<small>
			</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Rating: </label>
				<div class="col-sm-9">					
					<select name="data[NappUser][rating]" style="width:120px" id="selectrating" onchange="ratingupdate(this.value)" >		
						<option value="">Select Rating</option>
						<?php for($j=1; $j<=5; $j++){ ?>
						<option value="<?php echo $j; ?>" <?php if($napuser['NappUser']['rating'] == $j){ echo 'selected'; } ?>><?php echo $j; ?> Star</option>
						<?php } ?>	
					</select>
						
				</div>
			</div>
			<div class="form-group rating"  <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9"><h4 style="text-decoration:underline;">Package</h4></div>
			</div>
		
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Base Salary($):</label>
				<div class="col-sm-9">					
					<input type="text" id="basic_sal" name="data[NappUser][basic_sal]" class="col-xs-10 col-sm-5" value="<?php if(!empty($napuser['NappUser']['basic_sal'])){ echo $napuser['NappUser']['basic_sal']; } ?>" >
				</div>
			</div>
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Super($):</label>
				<div class="col-sm-9">					
					<input type="text" id="super" name="data[NappUser][super]" class="col-xs-10 col-sm-5" value="<?php if(!empty($napuser['NappUser']['super'])){ echo $napuser['NappUser']['super']; } ?>" >
				</div>
			</div>
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Phone($):</label>
				<div class="col-sm-9">					
					<input type="text" id="phone_expnse" name="data[NappUser][phone_expnse]" class="col-xs-10 col-sm-5" 
					value="<?php if(!empty($napuser['NappUser']['phone_expnse'])){ echo $napuser['NappUser']['phone_expnse']; } ?>" >
				</div>
			</div>
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">FMV (Full Maintained Vehicle):</label>
				<div class="col-sm-9">					
					<input type="text" id="fmv" name="data[NappUser][fmv]" class="col-xs-10 col-sm-5" value="<?php if(!empty($napuser['NappUser']['fmv'])){ echo $napuser['NappUser']['fmv']; } ?>" >
				</div>
			</div>
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Total Annual Package:</label>
				<div class="col-sm-9">					
					<input type="text" id="total_annual_package" name="data[NappUser][total_annual_package]" class="col-xs-10 col-sm-5" value="<?php if(!empty($napuser['NappUser']['total_annual_package'])){ echo $napuser['NappUser']['total_annual_package']; } ?>" >
				</div>
			</div>
			
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9"><h4 style="text-decoration:underline;">Sales Targets(Invoiced sales): </h4></div>
			</div>
		
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Sale Target Per Week :</label>
				<div class="col-sm-9">					
					<input type="text" id="sale_targe_per_week" name="data[NappUser][sale_targe_per_week]" class="col-xs-10 col-sm-5" value="<?php if(!empty($napuser['NappUser']['sale_targe_per_week'])){ echo $napuser['NappUser']['sale_targe_per_week']; } ?>" >
				</div>
			</div>
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Sale Target Per Month :</label>
				<div class="col-sm-9">					
					<input type="text" id="sale_targe_per_month" name="data[NappUser][sale_targe_per_month]" class="col-xs-10 col-sm-5" value="<?php if(!empty($napuser['NappUser']['sale_targe_per_month'])){ echo $napuser['NappUser']['sale_targe_per_month']; } ?>" >
				</div>
			</div>
			<div class="form-group rating" <?php if(empty($napuser['NappUser']['rating'])){ ?> style="display:none;" <?php } ?>>
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Sale Target Per Annual :</label>
				<div class="col-sm-9">					
					<input type="text" id="sale_targe_per_annum" name="data[NappUser][sale_targe_per_annum]" class="col-xs-10 col-sm-5" value="<?php if(!empty($napuser['NappUser']['sale_targe_per_annum'])){ echo $napuser['NappUser']['sale_targe_per_annum']; } ?>" >
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>
				<div class="col-sm-9"><h4 style="text-decoration:underline;">Activity KPI:</h4></div>
			</div>
					
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> F2F Meeting: </label>
				<div class="col-sm-9">		
					<select name="data[NappUser][ff_day]" id="ff_day" style="width:120px">		
						<?php for($i=1; $i<=15; $i++){ ?>
						<option value="<?php echo $i; ?>" <?php if($napuser['NappUser']['ff_day'] == $i){ echo 'selected'; } ?>><?php echo $i; ?> per Day</option>
						<?php } ?>	
					</select>
					<select name="data[NappUser][ff_meeting]" id="ff_meeting" style="width:120px">		
						<?php for($i=1; $i<=25; $i++){ ?>
						<option value="<?php echo $i; ?>" <?php if($napuser['NappUser']['ff_meeting'] == $i){ echo 'selected'; } ?>><?php echo $i; ?> per week</option>
						<?php } ?>	
					</select>
					<select name="data[NappUser][ff_month]" id="ff_month" style="width:120px">		
						<?php for($i=1; $i<=35; $i++){ ?>
						<option value="<?php echo $i; ?>" <?php if($napuser['NappUser']['ff_month'] == $i){ echo 'selected'; } ?>><?php echo $i; ?> per month</option>
						<?php } ?>	
					</select>
						
				</div>
			</div>	 	
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Calls per week(spoken to):</label>
				<div class="col-sm-9">	
					<select name="data[NappUser][cc_day]" id="cc_day" style="width:120px">		
						<?php for($k=1; $k<=100; $k++){ ?>
						<option value="<?php echo $k; ?>"  <?php if($napuser['NappUser']['cc_day'] == $k){ echo 'selected'; } ?>><?php echo $k; ?> per day</option>
						<?php } ?>	
					</select>
					<select name="data[NappUser][cc_meeting]" id="cc_meeting" style="width:120px">		
						<?php for($k=1; $k<=200; $k++){ ?>
						<option value="<?php echo $k; ?>"  <?php if($napuser['NappUser']['cc_meeting'] == $k){ echo 'selected'; } ?>><?php echo $k; ?> per week</option>
						<?php } ?>	
					</select>
					<select name="data[NappUser][cc_month]" id="cc_month" style="width:120px">		
						<?php for($k=1; $k<=700; $k++){ ?>
						<option value="<?php echo $k; ?>"  <?php if($napuser['NappUser']['cc_month'] == $k){ echo 'selected'; } ?>><?php echo $k; ?> per month</option>
						<?php } ?>	
					</select>	
				</div>
			</div>	 	
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit',array('div'=>false,'label'=>false, 'class' => 'btn btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
	
	function ratingupdate(rating){
		$(".rating").show();
		if(rating == 1){
			$("#basic_sal").val('63,000');
			$("#super").val('5,985');
			$("#phone_expnse").val('1,200');
			$("#fmv").val('18,000');
			$("#total_annual_package").val('89,041');
			
			$("#sale_targe_per_week").val('12,500');
			$("#sale_targe_per_month").val('50,000');
			$("#sale_targe_per_annum").val('600,000');
			
			$("#ff_day").val(2);
			$("#ff_meeting").val(10);
			$("#ff_month").val(40);
			
			$("#cc_day").val(10);
			$("#cc_meeting").val(50);
			$("#cc_month").val(200);
			
		}else if(rating == 2){
			$("#basic_sal").val('75,000');
			$("#super").val('7,125');
			$("#phone_expnse").val('1,200');
			$("#fmv").val('18,000');
			$("#total_annual_package").val('102,181');
			
			$("#sale_targe_per_week").val('20,000');
			$("#sale_targe_per_month").val('80,000');
			$("#sale_targe_per_annum").val('960,000');
			
				
			$("#ff_day").val(3);
			$("#ff_meeting").val(15);
			$("#ff_month").val(60);
			
			$("#cc_day").val(15);
			$("#cc_meeting").val(75);
			$("#cc_month").val(300);
		}else if(rating == 3){
			$("#basic_sal").val('90,000');
			$("#super").val('8,550');
			$("#phone_expnse").val('1,200');
			$("#fmv").val('18,000');
			$("#total_annual_package").val('118,606');
			
			$("#sale_targe_per_week").val('35,000');
			$("#sale_targe_per_month").val('140,000');
			$("#sale_targe_per_annum").val('1,680,00');
			
			$("#ff_day").val(20);
			$("#ff_meeting").val(100);
			$("#ff_month").val(400);
			
			$("#cc_day").val(4);
			$("#cc_meeting").val(4);
			$("#cc_month").val(4);
			
		}else if(rating == 4){
			$("#basic_sal").val('110,000');
			$("#super").val('5,985 – 10,450 ');
			$("#phone_expnse").val('1,200');
			$("#fmv").val('18,000');
			$("#total_annual_package").val('146,491');
			
			$("#sale_targe_per_week").val('45,000');
			$("#sale_targe_per_month").val('180,000');
			$("#sale_targe_per_annum").val('2,160,000');
			
			$("#ff_day").val(4);
			$("#ff_meeting").val(4);
			$("#ff_month").val(4);
			
			$("#cc_day").val(20);
			$("#cc_meeting").val(100);
			$("#cc_month").val(400);
			
		}else if(rating == 5){
			$("#basic_sal").val('140,000');
			$("#super").val('13,300');
			$("#phone_expnse").val('1,200');
			$("#fmv").val('18,000');
			$("#total_annual_package").val('183,806');
			
			$("#sale_targe_per_week").val('55,000');
			$("#sale_targe_per_month").val('220,000');
			$("#sale_targe_per_annum").val('2,640,000');
			
			$("#ff_day").val(6);
			$("#ff_meeting").val(6);
			$("#ff_month").val(6);
			
			$("#cc_day").val(25);
			$("#cc_meeting").val(125);
			$("#cc_month").val(500);
		}
	
	}

	function checkremider(type){
		 var is_both = document.getElementById("is_both");
		if(type ==1){
			$("#is_both").prop('checked', false);
		}else{
			if (is_both.checked) {
				$("#is_email").prop('checked', false);
				$("#is_sms").prop('checked', false);
			}	
		}	
	}	
    function Check() {
		
        var chkPassport = document.getElementById("is_reminder");
        if (chkPassport.checked) {
            $(".reminder").show();
        } else {
             $(".reminder").hide();
        }
    }
</script>	
<script type="text/javascript">
jQuery(function(){ 
	
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',	
		 minDate: 0	
	});
	
	$("#title").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter  employe id"
	}); $("#assignedto").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please select employee"
	}); $("#assignedby").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please eneter assigned by user"
	}); 
	
	
});
</script>
