	<style>
		.btn{ margin-bottom:5px;}
		.durolab_product {
	    padding: 50px 0px;
}
.widget-main.padding-6 {
	text-align: center;
}

@media only screen and (min-width: 320px) and (max-width: 640px){
.durolab_product {
	    padding: 0px 0px;
}
.durolab_product_section {
	width: 100%;
}

}
	</style>
	
	<div class="page-content">
		<div class="page-header" >
			<h1 style="text-align:center !important; font-weight:600;" >Welcome To DuroLab</h1>
		</div>
	
	<div class="row">
	    <div class="durolab_product col-lg-12 col-md-12 col-sm-12">
		    <div class="durolab_product_section col-xs-1">	
			</div>
			<div class="durolab_product_section col-xs-3">		
			
				<div class="row-fluid">
					<div class="span3 widget-container-span ui-sortable">
						<div class="widget-box">
							
							<div class="widget-body">
								<div class="widget-main padding-6" >
									<?php if(in_array(6,$userArr)){?>
										<a href="<?php echo SITEURL.'tasks/type/product'; ?>"> <div class="alert alert-info" style="background:#438eb9; color:#fff; font-size:30px; padding:50px" >Product <br> Development</div></a>
									<?php }else{ ?>
											<div class="alert alert-danger" style=" font-size:30px; padding:26px">Sorry, you have no access product development</div>
									<?php  }  ?>	
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="durolab_product_section col-xs-3">		
				<div class="span3 widget-container-span ui-sortable">
					<div class="widget-box light-border">
					
						<div class="widget-body">
							<div class="widget-main padding-6">
								<?php if(in_array(7,$userArr)){?>
									<a href="<?php echo  SITEURL.'tasks/type/technical' ?>"><div class="alert alert-danger" style=" font-size:30px; padding:50px; background:#438eb9; color:#fff; ">Technical <br> Service </div></a>
								<?php }else{ ?>
										<div class="alert alert-danger" style=" font-size:30px; padding:26px">Sorry, you have no access Technical Service</div>
								<?php  }  ?>	
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="durolab_product_section col-xs-3">		
				<div class="span3 widget-container-span ui-sortable">
					<div class="widget-box light-border">
					
						<div class="widget-body">
							<div class="widget-main padding-6">
								<?php if(in_array(7,$userArr)){?>
									<a href="<?php echo  SITEURL.'tasks/type/project_enquiry' ?>"><div class="alert alert-danger" style=" font-size:30px; padding:50px; background:#438eb9; color:#fff; ">Project <br> Enquiry </div></a>
								<?php }else{ ?>  <div class="alert alert-danger" style=" font-size:30px; padding:26px">Sorry, you have no access Technical Service</div>
								<?php  }  ?>
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="durolab_product_section col-xs-2">	
			</div>
		</div>
	</div>
		
	
</div>		