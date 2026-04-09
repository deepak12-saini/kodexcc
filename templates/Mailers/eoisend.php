	<style>
		.form-group {
			margin-bottom: 4px;
		}
	</style>
	<div class="page-content">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="javascript:window.history.back();" class="btn btn-inverse" >Back</a></div>
		<h1>Email  <small><i class="ace-icon fa fa-angle-double-right"></i>Send Emailer</small>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create(null, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>

			<div style="border:1px solid #000; width:80%; margin:20px 0px; padding:10px;" class="formdiv">

			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Insert Individual Name:</label>
				<div class="col-sm-9">
					<input type="text" name="inserIndivdualtname[]" value="" required class = "col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Insert Company Name:</label>
				<div class="col-sm-9">
					<input type="text" name="insertcompanyname[]" value="" required class = "col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Project Name:</label>
				<div class="col-sm-9">
					<input type="text" name="projectname[]" value="" required  class = "col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Insert Builders Email:</label>
				<div class="col-sm-9">
					<input type="text" name="insertbuildersname[]" value="" required class = "col-xs-10 col-sm-5" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date:</label>
				<div class="col-sm-9">
					<input type="date" name="date[]" value="" required class = "col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Insert Name:</label>
				<div class="col-sm-9">
					<input type="text" name="insertname[]" value="" required class = "col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Mobile Number:</label>
				<div class="col-sm-9">
					<input type="text" name="mobile[]" value=""  class = "col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Landline Number:</label>
				<div class="col-sm-9">
					<input type="text" name="landline[]" value=""  class = "col-xs-10 col-sm-5">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sender Email:</label>
				<div class="col-sm-9">
					<input type="text" name="senderemail[]" value="sales@durotechindustries.com.au" required class = "col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Subject:</label>
				<div class="col-sm-9">
					<input type="text" name="subject[]" value="Durotech Industries :- Waterproofing and Epoxy Flooring" required class = "col-xs-10 col-sm-5">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Client Requested :</label>
				<div class="col-sm-9">
					<select name="client_requested" class="col-xs-10 col-sm-5" >
						<option value="EOI Email">EOI Email</option>
						<option value="Pricing email">Pricing email </option>
					</select>
				</div>
			</div>
			</div>
			<div id="newdiv"></div>
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Submit', ['div' => false, 'label' => false, 'class' => 'btn btn-mini  btn-success', 'id' => 'add_ser_prd_btn', 'value' => 'Submit']); ?>&nbsp;
					<a class="btn btn-mini btn-danger" href="#null" onclick="addmore()">Add More</a>

				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
	</div>

<script type="text/javascript">
			jQuery(function(){

				$("#category_name").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter category name"
                }); $("#imagse").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please select image"
                });
			});

 	function addmore(){

		var numItems = $('.formdiv').length ;
		numItems = numItems + 1 ;
		var data = '<div class="formdiv" id="add_'+numItems+'" style="border:1px solid #000; width:80%; margin:20px 0px; padding:10px;"><div class="form-group"><label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Insert Individual Name:</label><div class="col-sm-9"><input type="text" name="inserIndivdualtname[]" value="" required class = "col-xs-10 col-sm-5"></div></div><div class="form-group"><label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Insert Company Name:</label><div class="col-sm-9"><input type="text" name="insertcompanyname[]" value="" required class = "col-xs-10 col-sm-5"></div></div><div class="form-group">	<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Project Name:</label><div class="col-sm-9"><input type="text" name="projectname[]" value="" required  class = "col-xs-10 col-sm-5">	</div>	</div>	<div class="form-group">		<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Insert Builders Email:</label>			<div class="col-sm-9">					<input type="text" name="insertbuildersname[]" value="" required class = "col-xs-10 col-sm-5" >				</div>			</div>			<div class="form-group">				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date:</label>				<div class="col-sm-9">	<input type="date" name="date[]" value="" required class = "col-xs-10 col-sm-5">	</div></div>		<div class="form-group">				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Insert Name:</label>				<div class="col-sm-9">					<input type="text" name="insertname[]" value="" required class = "col-xs-10 col-sm-5">				</div>			</div><div class="form-group"><label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Mobile Number:</label><div class="col-sm-9">	<input type="text" name="mobile[]" value=""  class = "col-xs-10 col-sm-5"></div></div><div class="form-group"><label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Landline Number:</label><div class="col-sm-9"><input type="text" name="landline[]" value=""  class = "col-xs-10 col-sm-5"></div></div>			<div class="form-group">				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Sender Email:</label>				<div class="col-sm-9">					<input type="text" name="senderemail[]" value="info@cwsaus.com.au" required class = "col-xs-10 col-sm-5"></div>	</div><div class="form-group">				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Subject:</label>				<div class="col-sm-9">					<input type="text" name="subject[]" value="CWS :- Waterproofing and Epoxy Flooring" required class = "col-xs-10 col-sm-5">				</div>			</div>			</div>';

		$('#newdiv').append(data);
	}
</script>
