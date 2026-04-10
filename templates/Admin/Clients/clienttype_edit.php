<link rel="stylesheet" href="<?php echo SITEURL; ?>theme/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>theme/css/font-awesome.css" />
<!-- page specific plugin styles -->
<!-- text fonts -->
<link rel="stylesheet" href="<?php echo SITEURL; ?>theme/css/ace-fonts.css" />
<!-- ace styles -->
<link rel="stylesheet" href="<?php echo SITEURL; ?>theme/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
<script src="<?php echo SITEURL; ?>theme/js/jquery.js"></script>
<script src="<?php echo SITEURL; ?>theme/js/jquery.validate.js"></script>
<style>
	.ErrorField{ border-color:red !important;}
</style>
	<div class="page-content" style="height:402px;">
		<div class="page-header">
		<div class="right_btn pull-right" ><a href="<?php echo SITEURL.'admin/clients/clienttype'?>" class="btn btn-xs btn-inverse " >Back</a></div>
		<h1><small>Edit Category</small></h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
		<?php echo $this->Form->create($clientType, ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']); ?>
		
			<div class="form-group">
				
				<div class="col-sm-9">
					<?php echo $this->Form->control('name', ['div' => false, 'label' => false, 'class' => 'col-xs-10 col-sm-5', 'id' => 'name', 'placeholder' => 'Client Category Name', 'value' => $ClientTypeArr['ClientType']['name'] ?? '']); ?>&nbsp;
				
				</div>
			</div>			
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit('Edit',array('div'=>false,'label'=>false, 'class' => 'btn btn-xs btn-success','id'=>'add_ser_prd_btn','value'=>'Submit'));?>&nbsp;
					
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>	
	</div>	
<script type="text/javascript">
	jQuery(function(){ 
		$("#name").validate({
			 expression: "if (VAL) return true; else return false;",
			message: ""
		});
	});
</script>	