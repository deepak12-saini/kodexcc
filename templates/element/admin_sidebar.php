	<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar                  responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				

				<ul class="nav nav-list">
					<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_dashboard') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/users/dashboard">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>
						<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_salemeet') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/users/salemeet">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Salemeet Questionary</span>
						</a>

						<b class="arrow"></b>
					</li>
					
					<li class="<?php if ($this->params['controller']=='ProductionReports') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/production-reports">
							<i class="menu-icon fa fa-file"></i>
							<span class="menu-text"> Production Reports </span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if ($this->params['controller']=='RewardProducts') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/reward-products">
							<i class="menu-icon fa fa-file"></i>
							<span class="menu-text">Reward Products</span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'duroorders' && $this->request->getParam('action') === 'userreward') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/duro-orders/userreward">
							<i class="menu-icon fa fa-file"></i>
							<span class="menu-text">Reward Points</span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'duroorders' && $this->request->getParam('action') === 'feedback') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/duro-orders/feedback">
							<i class="menu-icon fa fa-file"></i>
							<span class="menu-text">Sample Feedback</span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'labelstocks') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/label-stocks">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text"> Label Stock </span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'packagestocks') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/package-stocks">
							<i class="menu-icon fa fa-user"></i>
							<span class="menu-text"> Package Stock </span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'purchases') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/purchases">
							<i class="menu-icon fa fa-file"></i>
							<span class="menu-text">Purchase Request</span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php 
					$userid = $this->Session->read('User.id');
					if($userid != 7){ ?>
					
					
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'duroorders' && $this->request->getParam('action') === 'index') { echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/duro-orders">
							<i class="menu-icon fa fa-file"></i>
							<span class="menu-text"> Duro Order </span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'natas') { echo 'active'; } ?>">
						<a href="<?php echo SITEURL?>admin/natas">
							<i class="menu-icon fa fa-certificate"></i>
							<span class="menu-text"> NATA </span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_loginhisotry') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/users/loginhisotry">
							<i class="menu-icon fa fa-certificate"></i>
							<span class="menu-text"> Login History </span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'materials') { echo 'active'; } ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text">Material Order</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>
					
						<b class="arrow"></b>

						<ul class="submenu">
						
							<li class="<?php
								$ma = (string)$this->request->getParam('action');
								if (strtolower((string)$this->request->getParam('controller')) === 'materials' && in_array($ma, ['index', 'add', 'edit'], true)) { echo 'active'; }
							?>">
								<a href="<?php echo SITEURL?>admin/materials">
									<i class="menu-icon fa fa-user"></i>
									 Materials 
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'materials' && $this->request->getParam('action') === 'order') { echo 'active'; } ?>">
								<a href="<?php echo SITEURL?>admin/materials/order">
									<i class="menu-icon fa fa-user"></i>
									 Materials Order
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>
							
					<li class="<?php $sc = strtolower((string)$this->request->getParam('controller')); $sa = (string)$this->request->getParam('action'); if ($sc === 'sales' && in_array($sa, ['saledasboard', 'index', 'salereminder'], true)) { echo 'active'; } ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text">Sales</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>
					
						<b class="arrow"></b>

						<ul class="submenu">
						
							<li class="<?php if ($this->request->getParam('controller') === 'Clients') {echo 'active'; } ?>">
								<a href="<?php echo SITEURL?>admin/clients">
									<i class="menu-icon fa fa-user"></i>
									 CRM
								</a>
								<b class="arrow"></b>
							</li>
							
							<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'sales' && $this->request->getParam('action') === 'saledasboard') { echo 'active'; } ?>">
								<a href="<?php echo SITEURL.'admin/sales/saledasboard'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									Sale Dashboard
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'sales' && $this->request->getParam('action') === 'index') { echo 'active'; } ?>">
								<a href="<?php echo SITEURL.'admin/sales'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									Sale Team
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'sales' && $this->request->getParam('action') === 'salereminder') { echo 'active'; } ?>">
								<a href="<?php echo SITEURL.'admin/sales/salereminder'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									Sale Reminder
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'productions') { echo 'active'; } ?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text">Production App</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							
								<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'productions' && $this->request->getParam('action') === 'index') { echo 'active'; } ?>">
								<a href="<?php echo SITEURL.'admin/productions'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									Batches Made 
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'productions' && $this->request->getParam('action') === 'batchCountSheet') { echo 'active'; } ?>">
								<a href="<?php echo SITEURL.'admin/productions/batch_count_sheet'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									QC Result
								</a>
								<b class="arrow"></b>
							</li>
							
						</ul>
					</li>	
					<li class="<?php if ($this->params['controller']=='pos' && $this->params['action']=='admin_quizz') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/pos">
							<i class="menu-icon fa fa-user "></i>
							<span class="menu-text"> DT PO</span></b>
						</a>

					</li>	
					<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_quizz') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/users/quizz">
							<i class="menu-icon fa fa-user "></i>
							<span class="menu-text"> Quest Attendees</span></b>
						</a>

					</li>
					
					
					<li class="<?php if ($this->params['controller']=='fronts' && $this->params['action']=='admin_index') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/fronts">
							<i class="menu-icon fa fa-bell "></i>
							<span class="menu-text"> Push Notification</span></b>
						</a>

					</li>
					<?php // } ?>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'mailers') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/mailers">
							<i class="menu-icon fa fa-image "></i>
							<span class="menu-text">DuroEzy Spec</span></b>
						</a>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'tasks') {echo 'active';}?>">
						<a href="<?php echo SITEURL.'admin/tasks/type'; ?>">
							<i class="menu-icon fa fa-flask"></i>
							<span class="menu-text">DuroLab</span>
						</a>

						<b class="arrow"></b>
					</li>

					<?php  } ?>
					
					<li class="<?php if ($this->params['controller']=='Categories' || $this->params['controller']=='Subcategories' || $this->params['controller']=='Products') {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-bars "></i>
							<span class="menu-text"> Product</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='Categories') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/categories">
									<i class="menu-icon fa fa-setting"></i>
									Category
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='Subcategories') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/subcategories">
									<i class="menu-icon fa fa-setting"></i>
									Subcategory
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='Products' && $this->params['action']=='index') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/products">
									<i class="menu-icon fa fa-setting"></i>
									Product List
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='Products' && $this->params['action']=='label') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/products/label">
									<i class="menu-icon fa fa-setting"></i>
									Product Label
								</a>
								<b class="arrow"></b>
							</li>
							
							<li class="<?php if($this->params['controller']=='Products'  && $this->params['action']=='vocVertificate') { echo 'active'; }?>">
								<a href="<?php echo SITEURL?>admin/products/voc-vertificate">
									<i class="menu-icon fa fa-setting"></i>
									Voc Certificate
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_staff') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/users/staff">
							<i class="menu-icon fa fa-user "></i>
							<span class="menu-text"> Staff List</span></b>
						</a>
					</li>
					<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'users' && $this->request->getParam('action') === 'customer') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/users/customer">
							<i class="menu-icon fa fa-user "></i>
							<span class="menu-text">Customers </span></b>
						</a>

					</li>
					<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_labfile') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/users/labfile">
							<i class="menu-icon fa fa-money "></i>
							<span class="menu-text">Price List</span></b>
						</a>

					</li>
						
					<li class="<?php if ($this->params['controller']=='sheets') {echo 'active';}?>">
						<a href="<?php echo SITEURL?>admin/sheets">
							<i class="menu-icon fa fa-file-excel-o"></i>
							<span class="menu-text"> Smart Sheet </span>
						</a>

						<b class="arrow"></b>
					</li>
					<li class="<?php if (($this->params['controller']=='purchases') ||  ($this->params['controller']=='organisations') || ($this->params['controller']=='documents') ||  ($this->params['controller']=='audits') ||  ($this->params['controller']=='complaints') || ($this->params['controller']=='staffs') || ($this->params['controller']=='hrs')) { echo 'active'; } ?>" >
					   
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-certificate"></i>
							<span class="menu-text"> ISO </span>
							<b class="arrow fa fa-angle-down"></b>
						</a>	
						<ul class="submenu">
						
						<li class="<?php if (($this->params['controller']=='staffs') && ($this->params['action']=='admin_conformancelist') || $this->params['action']=='datasheet') {echo 'active';}?>">
							<a href="<?php echo SITEURL?>admin/staffs/conformancelist">
								<i class="menu-icon fa fa-tasks"></i>
								<span class="menu-text">Non Conformance</span></b>
							</a>
						</li>
						<li class="<?php if ($this->params['controller']=='complaints') {echo 'active';}?>">
							<a href="<?php echo SITEURL?>admin/complaints">
								<i class="menu-icon fa fa-image "></i>
								<span class="menu-text">Complaints  List</span></b>
							</a>
						</li>
						
						<li class="<?php if ($this->params['controller']=='documents') {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-bars "></i>
							<span class="menu-text"> Document</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='admin_index') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/documents">
									<i class="menu-icon fa fa-setting"></i>
									Record of library
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='admin_receiving_insepction') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/documents/receiving_insepction">
									<i class="menu-icon fa fa-setting"></i>
									Receive Inspection
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='admin_preventive_maintenance') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/documents/preventive_maintenance">
									<i class="menu-icon fa fa-setting"></i>
									Preventive Maintenance
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='admin_executive_duty_requisition') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/documents/executive_duty_requisition">
									<i class="menu-icon fa fa-setting"></i>
									Executive Duty Requisition
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='documents' && $this->params['action']=='admin_calibration_equipment') {echo 'active';}?>">
								<a href="<?php echo SITEURL?>admin/documents/calibration_equipment">
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
					<li class="<?php if ($this->params['controller']=='hrs') {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text"> HR </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='admin_index') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/hrs">
									<i class="menu-icon fa fa-setting"></i>
									Training Need Assessment
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='attendence') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/hrs/attendence">
									<i class="menu-icon fa fa-setting"></i>
									Attendence
								</a>
								<b class="arrow"></b>
							</li>
								<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='admin_performancefeedback') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/hrs/performancefeedback">
									<i class="menu-icon fa fa-setting"></i>
									Performance Feedback
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='admin_newjoining') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/hrs/newjoining">
									<i class="menu-icon fa fa-setting"></i>
									New Joining 
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='admin_reportorganization') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/hrs/reportorganization">
									<i class="menu-icon fa fa-setting"></i>
									Joining Report to the Organization
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='admin_format_training_calendars') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/hrs/format_training_calendars">
									<i class="menu-icon fa fa-setting"></i>
									Format Training Calendars
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='admin_format_evaluation_employe') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/hrs/format_evaluation_employe">
									<i class="menu-icon fa fa-setting"></i>
									Format Evaluation Employee
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='hrs'  && $this->params['action']=='admin_hr_appraisal_form') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/hrs/hr_appraisal_form">
								<i class="menu-icon fa fa-setting"></i>
									Performance Appraisal Form
								</a>
								<b class="arrow"></b>
							</li>
						</ul>	
					</li>
					<li class="<?php $c = strtolower((string)$this->request->getParam('controller')); if ($c==='organisations' || $c==='purchases') {echo 'active';}?>">
						<a href="" class="dropdown-toggle">
							<i class="menu-icon fa fa-briefcase"></i>
							<span class="menu-text"> Organisation </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='organisations' ) {echo 'active';}?>">
								 
								<a href="<?php echo SITEURL;?>admin/organisations"> 
									<i class="menu-icon fa fa-setting"></i>
									Circular Review
								</a>
								<b class="arrow"></b>
							</li>
							
							<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'purchases' && $this->request->getParam('action') === 'index') {echo 'active';}?>">
								 
								<a href="<?php echo SITEURL;?>admin/purchases"> 
									<i class="menu-icon fa fa-setting"></i>
									Purchases
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'purchases' && $this->request->getParam('action') === 'resourceRequirement') {echo 'active';}?>">
								 
								<a href="<?php echo SITEURL;?>admin/purchases/resource-requirement"> 
									<i class="menu-icon fa fa-setting"></i>
									Resource Requirement
								</a>
								<b class="arrow"></b>
							</li>
							
						</ul>
					</li>	
				
							<li class="<?php if ($this->params['controller']=='audits') {echo 'active';}?>">
								<a href="#" class="dropdown-toggle">
									<i class="menu-icon fa fa-thumbs-up "></i>
									<span class="menu-text"> Quality Assurance </span>

									<b class="arrow fa fa-angle-down"></b>
								</a>

								<b class="arrow"></b>

								<ul class="submenu">
										<li class="<?php if ($this->params['controller']=='audits'  && $this->params['action']=='admin_index') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>admin/audits">
											<i class="menu-icon fa fa-setting"></i>
											Audit
										</a>
										<b class="arrow"></b>
									</li>
									<li class="<?php if ($this->params['controller']=='audits'  && $this->params['action']=='admin_circularinternalauditlist') {echo 'active';}?>">
										<a href="<?php echo SITEURL;?>admin/audits/circularinternalauditlist">
											<i class="menu-icon fa fa-setting"></i>
											Circular Internal Audit
										</a>
										<b class="arrow"></b>
									</li>						
								</ul>
							</li>					
						</ul>					
					</li>
					
					<li class="" style="display:none;">
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
					</li>
						<li class="<?php if ($this->params['controller']=='users' && (($this->params['action']=='admin_subscriber_list') || ($this->params['action']=='admin_contactus') || ($this->params['action']=='admin_contact'))) {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file "></i>
							<span class="menu-text">Subscriber</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_subscriber_list') {echo 'active';}?>">
								<a href="<?php echo SITEURL.'admin/users/subscriber_list'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									Subscriber List
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_contactus') {echo 'active';}?>">
								<a href="<?php echo SITEURL.'admin/users/contactus'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									Contact List
								</a>
								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_contact') {echo 'active';}?>">
								<a href="<?php echo SITEURL.'admin/users/contact'; ?>">
									<i class="menu-icon fa fa-setting"></i>
									Dept Members
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>
										
					<li class="<?php if (($this->params['controller']=='users') && ($this->params['action']=='admin_payment_setting' ||	$this->params['action']=='admin_profile'  ||  $this->params['action']=='admin_change_password') ) {echo 'active';}?>">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-cog "></i>
							<span class="menu-text"> Settings </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							
							<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_profile') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/users/profile">
									<i class="menu-icon fa fa-setting"></i>
									Profile
								</a>

								<b class="arrow"></b>
							</li>
							<li class="<?php if (strtolower((string)$this->request->getParam('controller')) === 'users' && in_array((string)$this->request->getParam('action'), ['web_setting', 'webSetting'], true)) {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/users/web-setting">
									<i class="menu-icon fa fa-setting"></i>
									Website Settings
								</a>

								<b class="arrow"></b>
							</li>
							<li class="<?php if ($this->params['controller']=='users' && $this->params['action']=='admin_change_password') {echo 'active';}?>">
								<a href="<?php echo SITEURL;?>admin/users/change_password">
									<i class="menu-icon fa fa-setting"></i>
									Change Password
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