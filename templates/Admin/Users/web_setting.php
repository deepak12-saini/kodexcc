<div class="page-content">
	<div class="page-header">
	<h1>
	Settings
	<small>
	<i class="ace-icon fa fa-angle-double-right"></i>
	Website Setting
	</small>
	</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<?php
				$cfg = [];
				if (isset($Config) && is_array($Config)) {
					$cfg = $Config['Config'] ?? $Config['config'] ?? $Config;
				}
				if (!is_array($cfg)) {
					$cfg = [];
				}
			?>
			<?php echo $this->Form->create(null,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Email : </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('Config.mailto',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'mailto','placeholder'=>'Email','value'=>$cfg['mailto'] ?? ''))?>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone Number : </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('Config.phone',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'phone','placeholder'=>'Phone Number','value'=>$cfg['phone'] ?? ''))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address : </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('Config.address',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'address','placeholder'=>'Address','value'=>$cfg['address'] ?? ''))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Facebook : </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('Config.facebook',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'facebook','placeholder'=>'Facebook','value'=>$cfg['facebook'] ?? ''))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Google Plus : </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('Config.google_plus',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'google_plus','placeholder'=>'Google Plus','value'=>$cfg['google_plus'] ?? ''))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Twitter : </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('Config.twiter',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'twiter','placeholder'=>'Twitter','value'=>$cfg['twiter'] ?? ''))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> LinkedIN : </label>
					<div class="col-sm-9">
						<?php echo $this->Form->input('Config.linkdin',array('type'=>'text','div'=>false,'label'=>false, 'class' => 'col-xs-10 col-sm-5','id'=>'linkdin','placeholder'=>'LinkedIN','value'=>$cfg['linkdin'] ?? ''))?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Logo Image : </label>
					<div class="col-sm-9">
						<input type="file" name="data[image]" id="image" ><br/>
						<?php
							$logoFile = '';
							if (defined('LOGO')) {
								$logoFile = (string)constant('LOGO');
							} elseif (!empty($cfg['logo'])) {
								$logoFile = (string)$cfg['logo'];
							}
						?>
						<?php if ($logoFile !== '') : ?>
							<p><img src="<?php echo h(SITEURL . 'img/' . $logoFile); ?>" /></p>
						<?php endif; ?>
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
			jQuery(function(){ //short for $(document).ready(function(){
	
				$("#images").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please select image"
                });$("#mailto").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter email"
                });$("#username").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter username"
                });$("#email").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter email"
                });$("#phone").validate({
                     expression: "if (VAL) return true; else return false;",
                    message: "Please enter phone"
                }); 
			});
			</script>