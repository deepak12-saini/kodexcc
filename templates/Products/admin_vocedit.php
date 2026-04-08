	<script src="<?php echo SITEURL; ?>ckeditor/ckeditor.js"></script>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Voc Certificate<small><i class="ace-icon fa fa-angle-double-right"></i> Edit Voc Certificate</small>
		</h1>
		</div>
	
	<div class="row">
		<div class="col-xs-12">
	
		<?php echo $this->Form->create('Product',array('class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data'));?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date: </label>
				<div class="col-sm-9">
					<input type="text" name="data[VocCertificate][date]" id="date" class="col-xs-10 col-sm-6" placeholder="Tuesday, 19th December 2017" value="<?php echo $VocCertificateArr['VocCertificate']['date']; ?>">
				</div>
			</div>
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Simple Description: </label>
				<div class="col-sm-9">
					<input type="text" name="data[VocCertificate][simple_description]" id="simple_description" class="col-xs-10 col-sm-6" placeholder="Durotech Water Proofing Adhesive - Duroseal 25LM"  value="<?php echo $VocCertificateArr['VocCertificate']['simple_description']; ?>">
				</div>
			</div>
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date Tested: </label>
				<div class="col-sm-9">
					<input type="text" name="data[VocCertificate][date_tested]" id="date_tested" class="col-xs-10 col-sm-6" placeholder="November 2017 (Tested by FORAY Laboratories - NATA Accreditation 1231)" value="<?php echo $VocCertificateArr['VocCertificate']['date_tested']; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Test Method: </label>
				<div class="col-sm-9">
					<textarea name="data[VocCertificate][test_method]" id="test_method"  class="col-xs-10 col-sm-6" placeholder="Test Method" ><?php echo $VocCertificateArr['VocCertificate']['test_method']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sepecification: </label>
				<div class="col-sm-9">
					<textarea name="data[VocCertificate][sepecification]" id="sepecification"  class="col-xs-10 col-sm-6" placeholder="Sepecification" ><?php echo $VocCertificateArr['VocCertificate']['sepecification']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Product Name: </label>
				<div class="col-sm-9">
					<textarea name="data[VocCertificate][product_name]" id="product_name"  class="col-xs-10 col-sm-6" placeholder="Product Name"><?php echo $VocCertificateArr['VocCertificate']['product_name']; ?></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sepecification 2: </label>
				<div class="col-sm-9">
					<textarea name="data[VocCertificate][sepecification_2]" id="sepecification_2"  class="col-xs-10 col-sm-6" placeholder="sepecification 2"><?php echo $VocCertificateArr['VocCertificate']['sepecification_2']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Size: </label>
				<div class="col-sm-9">
					<textarea name="data[VocCertificate][weight]" id="weight"  class="col-xs-10 col-sm-6" placeholder="65 gram per Litre as VOC content per material"><?php echo $VocCertificateArr['VocCertificate']['weight']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description: </label>
				<div class="col-sm-9">
					<textarea name="data[VocCertificate][description]" id="description"  class="col-xs-10 col-sm-6" placeholder="We hereby certify Duroseal 25LM Product is well below the VOC content limits of chosen category set by Green Building Council of Australia."><?php echo $VocCertificateArr['VocCertificate']['description']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Certificate Number: </label>
				<div class="col-sm-9">
					<input type="text" name="data[VocCertificate][certificate_number]" value="<?php echo $VocCertificateArr['VocCertificate']['certificate_number']; ?>" id="certificate_number" class="col-xs-10 col-sm-6" placeholder="Certificate Number">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit',array('div'=>false,'label'=>false, 'class' => 'btn btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					<?php echo $this->Html->link('Cancel','javascript:window.history.back();',array('class' => 'btn btn-danger'));?>

				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>

<script type="text/javascript">
jQuery(function(){ 
	$("#date").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter date"
	});$("#simple_description").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter simple description"
	}); $("#date_tested").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter date tested"
	}); $("#test_method").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter test method"
	});  $("#sepecification").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter sepecification"
	}); $("#product_name").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter product name"
	}); $("#sepecification_2").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter  sepecification 2"
	}); $("#weight").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter weight"
	});$("#description").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter description"
	});$("#certificate_number").validate({
		 expression: "if (VAL) return true; else return false;",
		message: "Please enter certificate number"
	});
});
</script>
