<?php
$nu = $cus_arr['NappUser'] ?? [];
$isApproved = (int)($nu['is_approved'] ?? 0);
$deptId = (int)($nu['dept_id'] ?? 0);
$mobile = trim((string)($nu['mobile_number'] ?? ''));
?>
<!-- #section:basics/content.breadcrumbs -->
<div class="breadcrumbs" id="breadcrumbs">
	<script type="text/javascript">
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="#">Home</a>
		</li>
			<li class="active">Dashboard</li>
	</ul><!-- /.breadcrumb -->

</div>
<style>
.small-box {
    border-radius: 2px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    display: block;
    margin-bottom: 20px;
    position: relative;
}
.small-box .icon {
    color: rgba(0, 0, 0, 0.15);
    font-size: 90px;
    position: absolute;
    right: 10px;
    top: -10px;
    transition: all 0.3s linear 0s;
    z-index: 0;
}
.small-box > .small-box-footer {
    background: rgba(0, 0, 0, 0.1) none repeat scroll 0 0;
    color: rgba(255, 255, 255, 0.8);
    display: block;
    padding: 3px 0;
    position: relative;
    text-align: center;
    text-decoration: none;
    z-index: 10;
}
</style>
	<div class="page-content">
	<div class="page-header">
		<h1>
			Dashboard
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				overview &amp; stats
			</small>
		</h1>
	</div><!-- /.page-header -->
	<div class="row">
	<div class="col-xs-12">
	
	
	<?php if ($isApproved === 0) { ?>
		<div class="alert alert-block alert-danger" style="border-radius:4px;">
			<i class="ace-icon fa fa-clock-o"></i>
			<strong>Pending approval.</strong>
			Your account is not approved by admin yet. Please wait for approval. Thank you.
		</div>
	<?php } elseif ($isApproved === 2) { ?>
		<div class="alert alert-block alert-warning" style="border-radius:4px;">
			<i class="ace-icon fa fa-ban"></i>
			<strong>Account not active.</strong>
			Your account has been disapproved by admin. Please contact
			<b>technical@durotechindustries.com.au</b> or <b>sals@durotechindustries.com.au</b>.
		</div>
	<?php } elseif ($deptId === 0 || $mobile === '') { ?>
		<div class="alert alert-block alert-info" style="border-radius:4px;">
			<i class="ace-icon fa fa-user"></i>
			<strong>Complete your profile.</strong>
			Please <a href="<?php echo h(SITEURL . 'staffs/profile'); ?>" class="alert-link">open your profile</a> to add department and mobile number.
		</div>
	<?php } else { ?>
		<div class="alert alert-block alert-success" style="border-radius:4px;">
			<button type="button" class="close" data-dismiss="alert">
				<i class="ace-icon fa fa-times"></i>
			</button>
			<i class="ace-icon fa fa-check green"></i>
			Welcome to <strong class="green"><?php echo h(SITENAME); ?> Staff Panel</strong>
		</div>
	<?php } ?>
	<div class="row">
        
    </div><!-- /.row -->

	</div>
	</div>
	
	<?php
		$chkuserpermission = $this->requestAction('/app/chkuserpermission');
		if (!empty($nu) && in_array(8, $chkuserpermission, true)) {
	?>
	<div class="row">
		<div class="col-xs-12">	

			<span class="label label-info" style="float:left; margin: 0px 0px 20px 35px; ">Current Package</span>
			<div class="row">
			<div class="space-6"></div>
			
			<div class="col-sm-12 infobox-container">
				<!-- #section:pages/dashboard.infobox -->
				
				<div class="infobox infobox-pink">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-dollar"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number"><?php echo h($nu['basic_sal'] ?? ''); ?></span>
						<div class="infobox-content">Base Salary</div>
					</div>
					
				</div>

				<div class="infobox infobox-blue">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-dollar"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number"><?php echo h($nu['super'] ?? ''); ?></span>
						<div class="infobox-content">Super</div>
					</div>

					<div class="badge badge-success"></div>
				</div>

				<div class="infobox infobox-pink">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-dollar"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number"><?php echo h($nu['phone_expnse'] ?? ''); ?></span>
						<div class="infobox-content">Phone</div>
					</div>					
				</div>

				<div class="infobox infobox-red">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-dollar"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number"><?php echo h($nu['fmv'] ?? ''); ?></span>
						<div class="infobox-content">Full Maintained Vehicle</div>
					</div>
				</div>

				<div class="infobox infobox-orange2">
					<!-- #section:pages/dashboard.infobox.sparkline -->
					<div class="infobox-icon">
						<i class="ace-icon fa fa-dollar"></i>
					</div>

					<!-- /section:pages/dashboard.infobox.sparkline -->
					<div class="infobox-data">
						<span class="infobox-data-number"><?php echo h($nu['total_annual_package'] ?? ''); ?></span>
						<div class="infobox-content">Total Annual Package</div>
					</div>

					<div class="badge badge-success"></div>
				</div>


				<!-- /section:pages/dashboard.infobox.dark -->
			</div>
			
			<span class="label label-info" style="float:left; margin: 40px 0px 20px 50px; ">Sales Targets(Invoiced sales)</span>
			<div class="row">
			<div class="space-6"></div>
			
			<div class="col-sm-12 infobox-container">
				<!-- #section:pages/dashboard.infobox -->
				
				<div class="infobox infobox-pink">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-dollar"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number"><?php echo h($nu['sale_targe_per_week'] ?? ''); ?></span>
						<div class="infobox-content">Sale Target Per Week</div>
					</div>
					
				</div>

				<div class="infobox infobox-blue">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-dollar"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number"><?php echo h($nu['sale_targe_per_month'] ?? ''); ?></span>
						<div class="infobox-content">Sale Target Per Month</div>
					</div>

					<div class="badge badge-success"></div>
				</div>

				<div class="infobox infobox-pink">
					<div class="infobox-icon">
						<i class="ace-icon fa fa-dollar"></i>
					</div>

					<div class="infobox-data">
						<span class="infobox-data-number"><?php echo h($nu['sale_targe_per_annum'] ?? ''); ?></span>
						<div class="infobox-content">Sale Target Per Annual</div>
					</div>					
				</div>



				<!-- /section:pages/dashboard.infobox.dark -->
			</div>

		</div><!-- /.row -->

		<!-- #section:custom/extra.hr -->
		<div class="hr hr32 hr-dotted"></div>

		<!-- /section:custom/extra.hr -->
		<div class="row">
			<div class="col-sm-6">
				<div class="widget-box transparent">
					<div class="widget-header widget-header-flat">
						<h4 class="widget-title lighter">
							<i class="ace-icon fa fa-star orange"></i>
							Activity KPI
						</h4>

						<!-- <div class="widget-toolbar">
							<a href="#" data-action="collapse">
								<i class="ace-icon fa fa-chevron-up"></i>
							</a>
						</div> -->
					</div>

					<div class="widget-body">
						<div class="widget-main no-padding">
							<table class="table table-bordered table-striped">
								<thead class="thin-border-bottom">
									<tr>
										<th>
											<i class="ace-icon fa fa-caret-right blue"></i> Meeting Types
										</th>

										<th>
											# Per Day
										</th>

										<th class="hidden-480">
											# Per Week
										</th>
										<th class="hidden-480">
											# Per Month
										</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td><span class="label label-info">F2F Meeting</span></td>
										<td class="hidden-480">
											<i class="label label-primary arrowed-in arrowed-in-right" ><?php echo h($nu['ff_day'] ?? ''); ?> Per Day</i>
										</td>
										<td class="hidden-480">
											<i class="label label-warning arrowed-in arrowed-in-right" ><?php echo h($nu['ff_meeting'] ?? ''); ?> Per Week</i>
										</td>
										<td class="hidden-480">
											<i class="label label-info arrowed-in arrowed-in-right" ><?php echo h($nu['ff_month'] ?? ''); ?> Per Month</i>
										</td>
									</tr>
									<tr>
										<td><span class="label label-info">Calls per week(spoken to)</span></td>
										<td class="hidden-480">
											<i class="label label-primary arrowed-in arrowed-in-right" ><?php echo h($nu['cc_day'] ?? ''); ?> Per Day</i>
										</td>
										<td class="hidden-480">
											<i class="label label-warning arrowed-in arrowed-in-right" ><?php echo h($nu['cc_meeting'] ?? ''); ?> Per  Week</i>
										</td>
										<td class="hidden-480">
											<i class="label label-info arrowed-in arrowed-in-right" ><?php echo h($nu['cc_month'] ?? ''); ?> Per Month</i>
										</td>
									</tr>	
									
								</tbody>
							</table>
						</div><!-- /.widget-main -->
					</div><!-- /.widget-body -->
				</div><!-- /.widget-box -->
			</div><!-- /.col -->

		</div><!-- /.row -->

	
		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
	</div><!-- /.row -->	
	</div>
	<?php } ?>
	</div>
