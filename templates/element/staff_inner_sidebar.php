		<?php
			$chkuserpermission = $this->requestAction('/app/chkuserpermission');
		
		?>	
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar responsive menu-min" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true">
			<!-- <div id="sidebar" class="sidebar                  responsive"> -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				

				<ul class="nav nav-list">
					<li class="<?php if ($this->params['controller']=='staffs' && $this->params['action']=='dashboard') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>staffs/dashboard">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>
					
					<?php						
						if(in_array(12,$chkuserpermission) ){
					?>
					<li class="<?php if ($this->params['controller']=='clients') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>clients">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text"> CRM </span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php } ?>
					<?php						
						if(in_array(32,$chkuserpermission) ){
					?>
					<li class="<?php if ($this->params['controller']=='label_stocks') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>label_stocks">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text"> Label Stock </span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php } ?>
					<?php						
						if(in_array(33,$chkuserpermission) ){
					?>
					<li class="<?php if ($this->params['controller']=='label_stocks') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>package_stocks">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text"> Package Stock </span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php } ?>
					<?php						
						if(in_array(13,$chkuserpermission) ){
					?>
					<li class="<?php if ($this->params['controller']=='duro_orders' && ($this->params['action']=='index')) { echo 'active'; }?>"> 
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-building"></i>
							<span class="menu-text">Duro Order </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>
						<b class="arrow"></b>
						<ul class="submenu">
							<li class="<?php if ($this->params['controller']=='duro_orders') { echo 'active';}?>">
								<a href="<?php echo SITEURL?>duro_orders">
									<i class="menu-icon fa fa-file"></i>
									<span class="menu-text"> Duro Order List </span>
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					<?php } ?>
					<li class="<?php if ($this->params['controller']=='users' && ($this->params['action']=='cpdpresentation' ||   $this->params['action']=='presentation' ||   $this->params['action']=='questionlist'  ||  $this->params['action']=='cert'  ||  $this->params['action']=='welcomemailer')) { echo 'active'; }?>" style="display:none;"> 
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-building"></i>
							<span class="menu-text">Architect </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>
						<b class="arrow"></b>
						<ul class="submenu">			
							
							<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='presentation') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>users/presentation">
									<i class="menu-icon fa fa-setting"></i>
									Architect Presentation
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='cpdpresentation') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>users/cpdpresentation">
									<i class="menu-icon fa fa-setting"></i>
									Workshop  CPD Presentation
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='questionlist') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>users/questionlist">
									<i class="menu-icon fa fa-setting"></i>
									Architect  CPD Questionnair
								</a>
								<b class="arrow"></b>
							</li>
							<li  class="<?php if ($this->params['controller']=='users' && $this->params['action']=='welcomemailer') { echo 'active';}?>">
								<a href="<?php echo SITEURL;?>users/welcomemailer">
									<i class="menu-icon fa fa-setting"></i>
									CPD Presentation Mailer
								</a>
								<b class="arrow"></b>
							</li>
							<li class="" class="<?php if ($this->params['controller']=='users' && $this->params['action']=='cert') { echo 'active';}?>">
								<a href="<?php echo SITEURL;?>users/cert">
									<i class="menu-icon fa fa-setting"></i>
									Send Certificate 
								</a>
								<b class="arrow"></b>
							</li>
						</ul>	
					</li>
					<?php						
						if(in_array(30,$chkuserpermission) ){
					?>	
					<li class="<?php if ($this->params['controller']=='purchases') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>purchases">
							<i class="menu-icon fa fa-file"></i>
							<span class="menu-text">Purchase Request</span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php } ?>
					<?php						
						if(in_array(28,$chkuserpermission) ){
					?>
					<li class="<?php if ($this->params['controller']=='ProductionReports') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>production-reports">
							<i class="menu-icon fa fa-money "></i>
							<span class="menu-text">Production Report</span></b>
						</a>
					</li>
					<?php } ?>
					<?php						
						if(in_array(22,$chkuserpermission) ){
					?>
					<li class="<?php if ($this->params['controller']=='productions') {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text">Production App</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							
								<li class="<?php if ($this->params['controller']=='productions' && $this->params['action']=='index') {echo 'active';}?>">
								<a href="<?php echo SITEURL.'productions'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									Batches Made 
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='productions' && $this->params['action']=='batch_count_sheet') {echo 'active';}?>">
								<a href="<?php echo SITEURL.'productions/batch_count_sheet'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									QC Result
								</a>
								<b class="arrow"></b>
							</li>
							
						</ul>
					</li>	
					<?php } ?>
					<!--li class="<?php if (($this->params['controller']=='users') && $this->params['action']=='labfile') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>users/labfile">
							<i class="menu-icon fa fa-money "></i>
							<span class="menu-text">Price List</span></b>
						</a>
					</li-->
					
					<?php						
						if(in_array(14,$chkuserpermission) ){
					?>
					<li class="<?php if ((strtolower((string)$this->request->getParam('controller')) === 'mailers') && $this->request->getParam('action') === 'eoi') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>mailers/eoi">
							<i class="menu-icon fa fa-money "></i>
							<span class="menu-text">EOI Mailer List</span></b>
						</a>
					</li>
					<?php } ?>
					
					<?php						
						if(in_array(15,$chkuserpermission) ){
					?>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'mailers' && $this->request->getParam('action') === 'index') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>mailers">
							<i class="menu-icon fa fa-image "></i>
							<span class="menu-text">DuroEzy Spec</span></b>
						</a>
					</li>
					<?php } ?>
					<!--li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='contact') {echo 'active';}?>">
						<a href="<?php echo SITEURL.'users/contact'; ?>">
							<i class="menu-icon fa fa-users"></i>
							<span class="menu-text">Dept Members</span>
						</a>
						<b class="arrow"></b>
					</li>
					<li class="<?php if (($this->params['controller']=='products') && ($this->params['action']=='label') || $this->params['action']=='datasheet') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>products/label">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text">Product Label</span></b>
						</a>
					</li-->
					<?php
							
						if(in_array(6,$chkuserpermission) || in_array(7,$chkuserpermission)){
					?>
					<li class="<?php if ($this->params['controller']=='tasks') {echo 'active';}?>">
						<a href="<?php echo SITEURL.'tasks/type'; ?>">
							<i class="menu-icon fa fa-flask"></i>
							<span class="menu-text">DuroLab</span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php } ?>
					<?php						
						if(in_array(10,$chkuserpermission) ){
					?>
					<li class="<?php if ($this->params['controller']=='natas') {echo 'active';}?>">
						<a href="<?php echo SITEURL.'natas'; ?>">
							<i class="menu-icon fa fa-certificate"></i>
							<span class="menu-text"> NATA </span>
						</a>
						<b class="arrow"></b>
					</li>
					<?php } ?>
					<li class="<?php if ($this->params['controller']=='sheets') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>sheets">
							<i class="menu-icon fa fa-file-excel-o"></i>
							<span class="menu-text"> Smart Sheet </span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php						
						if(in_array(21,$chkuserpermission)){
						?>
						<li class="<?php if ($this->params['controller']=='materials') {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text">Material Order</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>
					
						<b class="arrow"></b>

						<ul class="submenu">
						
							<li class="<?php if (($this->params['controller']=='materials') && ($this->params['action']=='index' || $this->params['action']=='add' || $this->params['action']=='edit' )) {echo 'active'; } ?>">
								<a href="<?php echo SITEURL?>materials">
									<i class="menu-icon fa fa-user"></i>
									 Materials 
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if (($this->params['controller']=='materials') && ($this->params['action']=='order')){ echo 'active'; } ?>">
								<a href="<?php echo SITEURL?>materials/order">
									<i class="menu-icon fa fa-user"></i>
									 Materials Order
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
						</li>
						<?php } ?>
					<?php						
						if(in_array(20,$chkuserpermission) ){
					?>
					
					<li class="<?php if (($this->params['controller']=='purchases') ||  ($this->params['controller']=='organisations') || ($this->params['controller']=='documents') ||  ($this->params['controller']=='audits') ||  ($this->params['controller']=='complaints') || ($this->params['controller']=='staffs') || ($this->params['controller']=='hrs')) { echo 'active'; } ?>" >
					   
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-certificate"></i>
							<span class="menu-text"> ISO </span>
							<b class="arrow fa fa-angle-down"></b>
						</a>	
						<ul class="submenu">
						
						<li class="<?php if (($this->params['controller']=='staffs') && ($this->params['action']=='document') || $this->params['action']=='datasheet') {echo 'active';}?>">
							<a href="<?php echo SITEURL?>staffs/datasheet">
								<i class="menu-icon fa fa-file "></i>
								<span class="menu-text">Product Document</span></b>
							</a>
						</li>
						
						<li class="<?php if (($this->params['controller']=='staffs') && ($this->params['action']=='conformancelist')) {echo 'active';}?>">
							<a href="<?php echo SITEURL?>staffs/conformancelist">
								<i class="menu-icon fa fa-tasks "></i>
								<span class="menu-text">Non Conformance</span></b>
							</a>
						</li>
						<li class="<?php if ($this->params['controller']=='complaints') {echo 'active';}?>">
							<a href="<?php echo SITEURL?>complaints">
								<i class="menu-icon fa fa-image "></i>
								<span class="menu-text">Complaints  List</span></b>
							</a>
						</li>
						<?php						
						if(in_array(23,$chkuserpermission) ){
						?>
						<li class="<?php if ($this->params['controller']=='pos') { echo 'active';}?>">
							<a href="<?php echo SITEURL?>pos">
								<i class="menu-icon fa fa-user"></i>
								<span class="menu-text"> DT PO </span>
							</a>

							<b class="arrow"></b>
						</li>
						<?php } ?>
						<?php						
						if(in_array(11,$chkuserpermission) ){
						?>
							<li class="<?php if ($this->params['controller']=='hrs') {echo 'active';}?>">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-file "></i>
									<span class="menu-text"> HR </span>

									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
									
									<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='index') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs">
											<i class="menu-icon fa fa-setting"></i>
											Training Need Assessment
										</a>
										<b class="arrow"></b>
									</li>
									<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='attendence') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs/attendence">
											<i class="menu-icon fa fa-setting"></i>
											Attendence
										</a>
										<b class="arrow"></b>
									</li>
									<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='performancefeedback') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs/performancefeedback">
											<i class="menu-icon fa fa-setting"></i>
											Performance Feedback
										</a>
										<b class="arrow"></b>
									</li>
									<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='newjoining') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs/newjoining">
											<i class="menu-icon fa fa-setting"></i>
											New Joining 
										</a>
										<b class="arrow"></b>
									</li>
									<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='reportorganization') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs/reportorganization">
											<i class="menu-icon fa fa-setting"></i>
											Joining Report to the Organization
										</a>
										<b class="arrow"></b>
									</li>
									<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='res_during_leave') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs/res_during_leave">
											<i class="menu-icon fa fa-setting"></i>
											Responsibilties During Leave
										</a>
										<b class="arrow"></b>
									</li>
									<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='format_training_calendars') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs/format_training_calendars">
											<i class="menu-icon fa fa-setting"></i>
											Format Training Calendars
										</a>
										<b class="arrow"></b>
									</li>
										<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='format_evaluation_employe') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs/format_evaluation_employe">
											<i class="menu-icon fa fa-setting"></i>
											Format Evaluation Employe
										</a>
										<b class="arrow"></b>
									</li>
									<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='hr_appraisal_form') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>hrs/hr_appraisal_form">
										<i class="menu-icon fa fa-setting"></i>
											Performance Appraisal Form
										</a>
										<b class="arrow"></b>
									</li>
								</ul>	
							</li>	
							<?php } ?>	
							
							<?php						
								if(in_array(19,$chkuserpermission) ){
							?>	
							<li class="<?php if ($this->params['controller']=='audits') {echo 'active';}?>">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-thumbs-up "></i>
									<span class="menu-text"> Quality Assurance </span>

									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">							
									
									<li class="<?php if ($this->params['controller']=='audits'  && $this->params['action']=='index') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>audits">
											<i class="menu-icon fa fa-setting"></i>
											Audit
										</a>
										<b class="arrow"></b>
									</li>
									
									<li class="<?php if ($this->params['controller']=='audits'  && $this->params['action']=='circularinternalauditlist') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>audits/circularinternalauditlist">
											<i class="menu-icon fa fa-setting"></i>
											Circular Internal Audit
										</a>
										<b class="arrow"></b>
									</li>
									
								</ul>
							</li>
							<?php } ?>
							
					<?php						
						if(in_array(17,$chkuserpermission) ){
					?>	
					<li class="<?php if ($this->params['controller']=='documents') {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-bars "></i>
							<span class="menu-text"> Document</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='index') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>documents">
									<i class="menu-icon fa fa-setting"></i>
									Record of library
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='receiving_insepction') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>documents/receiving_insepction">
									<i class="menu-icon fa fa-setting"></i>
									Receive Inspection
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='preventive_maintenance') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>documents/preventive_maintenance">
									<i class="menu-icon fa fa-setting"></i>
									Preventive Maintenance
								</a>
								<b class="arrow"></b>
							</li>
							
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='executive_duty_requisition') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>documents/executive_duty_requisition">
									<i class="menu-icon fa fa-setting"></i>
									Executive Duty Requisition
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='calibration_equipment') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>documents/calibration_equipment">
									<i class="menu-icon fa fa-setting"></i>
									Calibration Equipment
								</a>
								<b class="arrow"></b>
							</li>
							
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='admin_nc_closure') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/documents/nc_closure">
									<i class="menu-icon fa fa-setting"></i>
									Format For NC and NC Closure
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					<?php } ?>
					
					<?php						
						if(in_array(18,$chkuserpermission) ){
					?>	
					<li class="<?php if ($this->params['controller']=='organisations' || $this->params['controller']=='purchases') {echo 'active';}?>">
						<a href="" class="dropdown-toggle">
							<i class="menu-icon fa fa-briefcase"></i>
							<span class="menu-text"> Organisation </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='organisations') {echo 'active';}?>">
								 
								<a href="<?php echo SITEURL;?>organisations/circular"> 
									<i class="menu-icon fa fa-setting"></i>
									Circular Review
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='purchases' && $this->params['action']=='index') {echo 'active';}?>">
								 
								<a href="<?php echo SITEURL;?>purchases"> 
									<i class="menu-icon fa fa-setting"></i>
									Purchases
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='purchases'  && $this->params['action']=='resource_requirement') {echo 'active';}?>">
								 
								<a href="<?php echo SITEURL;?>purchases/resource_requirement"> 
									<i class="menu-icon fa fa-setting"></i>
									Resource Requirement
								</a>
								<b class="arrow"></b>
							</li>
							
						</ul>
					</li>	
					<?php } ?>
							
						</ul>	
					</li>
					<?php } ?>
										
					
					<!--li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text"> Test certificates </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="">
								<a href="">
									<i class="menu-icon fa fa-setting"></i>
									CSIRO
								</a>
								<b class="arrow"></b>
							</li>
							<li class="">
								<a href="">
									<i class="menu-icon fa fa-setting"></i>
									CETEC
								</a>
								<b class="arrow"></b>
							</li>
							<li class="">
								<a href="">
									<i class="menu-icon fa fa-setting"></i>
									BRANZ
								</a>
								<b class="arrow"></b>
							</li>
							<li class="">
								<a href="">
									<i class="menu-icon fa fa-setting"></i>
									EUROPEAN 
								</a>
								<b class="arrow"></b>
							</li>
						</ul>	
					</li-->	
						
					<li class="<?php if (($this->params['controller']=='staffs') && ($this->params['action']=='profile'  ||  $this->params['action']=='change_password') ) {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-cog "></i>
							<span class="menu-text"> Settings </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='staffs' && $this->params['action']=='profile') { echo 'active'; }?>">
								<a href="<?php echo SITEURL;?>staffs/profile">
									<i class="menu-icon fa fa-setting"></i>
									Profile
								</a>

								<b class="arrow"></b>
							</li>
							
							<li class="<?php if ($this->params['controller']=='staffs' && $this->params['action']=='change_password') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>staffs/change_password">
									<i class="menu-icon fa fa-setting"></i>
									Change Password
								</a>

								<b class="arrow"></b>
							</li>
							<li>
								<a href="<?php echo SITEURL;?>staffs/logout">
									<i class="menu-icon fa fa-setting"></i>
									Logout
									</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>
				
							
				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>