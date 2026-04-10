<?php
$m = $MaterialArr['Material'] ?? [];
?>
	<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<a href="<?php echo h(SITEURL); ?>"><img src="<?php echo h(SITEURL); ?>customdurotech/images/durotech_logo.png" alt=""/></a>
								</h1>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Send Material Order Request 
											</h4>

											<div class="space-6"></div>

											<?php echo $this->Flash->render(); ?>
											<?php echo $this->Form->create(null, ['url' => ['action' => 'readqrcode', $m['id'] ?? '']]); ?>
												<?php echo $this->Form->hidden('id', ['value' => $m['id'] ?? '']); ?>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">					
															<input name="material_type" class="form-control ErrorField" type="text" value="<?php echo h((string)($m['material_type'] ?? '')); ?>" readonly style="background: #ddd !important;">
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">					
															<input name="weight" class="form-control ErrorField" type="text" value="<?php echo h((string)($m['weight'] ?? '')); ?>" readonly style="background: #ddd !important;">
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">					
															<input name="quantity" class="form-control ErrorField" type="text" value="<?php echo h((string)($m['quantity'] ?? '')); ?>">
														</span>
													</label>
													<p><small>E.g : <?php echo h((string)($m['description'] ?? '')); ?></small></p>
													<label class="block clearfix">														
														<span class="block input-icon input-icon-right">					
															<input name="name" class="form-control ErrorField" type="text" value="" placeholder="enter your name">
														</span>
													</label>
													<?php echo $this->Form->submit(__('Send Request'), ['div' => false, 'class' => 'width-35 pull-right btn btn-sm btn-primary']); ?>
													<div class="space-4"></div>
												</fieldset>
											<?php echo $this->Form->end(); ?>
										</div>
									</div>
								</div>
							</div>						
						</div>
					</div>
				</div>
