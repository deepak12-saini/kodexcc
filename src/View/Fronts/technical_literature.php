<script type="text/javascript" src="<?php echo SITEURL ;?>fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL ;?>fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<script type="text/javascript" src="<?php echo SITEURL ;?>fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();	
	});
</script>
<style>
.technical_search .form-control {
	border: 1px solid #ccc !important;
}
.technical_search .form-control {
	height: 50px;
}
.technical_search .btn.btn-primary {
	padding: 14px 14px;
}
</style>		
		<div class="banner_durolab col-lg-12 col-md-12 col-sm-12">
		    <img src="<?php echo SITEURL ;?>customdurotech/images/technical_banner_2.jpg">
		</div>
		<div class="technical_search col-lg-12 col-md-12 col-sm-12">
		    <div class="container" style="margin-top: 2%;">
				<div class="col-md-6 col-md-offset-3">
				
					<form role="form" action="" method="post">					
					
						<div class="row">
							<div class="form-group">
								<div class="input-group">
									<input class="form-control" type="text" name="search" placeholder="Search" value="<?php echo $searchtitle; ?>" required/>
									<span class="input-group-btn">
										<button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i>
										<span style="margin-left:10px;">Search</span></button>						 
									</span>
								</div>
							</div>
						</div>
						
					</form>
			
				</div>
			</div>
		</div>
		
		<div class="technical_literature col-lg-12 col-md-12 col-sm-12">
		     <div class="container">
			 <div class="row">
				<div class="col-md-12">
					<div class="tab" role="tabpanel">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#Section1" aria-controls="home" role="tab" data-toggle="tab">Data Sheet</a></li>
							<li role="presentation"><a href="#Section2" aria-controls="profile" role="tab" data-toggle="tab" id="project" >Project Reference</a></li>
							<li role="presentation"><a href="#Section3" aria-controls="messages" role="tab" data-toggle="tab">Product Brochure</a></li>
							<li role="presentation"><a href="#Section4" aria-controls="messages" role="tab" data-toggle="tab">Quick Spec</a></li>
							<li role="presentation"><a href="#Section5" aria-controls="messages" role="tab" data-toggle="tab">Method Statement</a></li>
							<li role="presentation"><a href="#Section1" aria-controls="messages" role="tab" data-toggle="tab">Project Specification</a></li>
						</ul>
						<!-- Tab panes -->
						
						   


						<div class="tab-content tabs">
							<div role="tabpanel" class="tab-pane fade" id="Section4">
								<h3>Coming Soon</h3>								
							</div>
							<div role="tabpanel" class="tab-pane fadee" id="Section5">
								<h3>Coming Soon</h3>								
							</div>							
							<div role="tabpanel" class="tab-pane fade" id="Section6">
								<h3>Coming Soon</h3>								
							</div>
							
							<div role="tabpanel" class="tab-pane fade active in" id="Section1">
								<h3>Data Sheet</h3>
								<?php 
									foreach($product as $products){
									
									$datasheet = explode("##",$products['Product']['datasheet']);
									isset($datasheet[0])? $datasheet_1 = $datasheet[0] : $datasheet_1 = '';
									isset($datasheet[1])? $datasheetdownload_2 = $datasheet[1] : $datasheetdownload_2 = '';
									if(!empty($datasheet_1)){
								?>
									<a target="_blank" href="<?php echo $datasheet_1; ?>" class="btn effect5"><?php echo $products['Product']['title']; ?></a>
								
								
								<?php } } ?>
								
							</div>
							<div role="tabpanel" class="tab-pane fade" id="Section2">
								<h3>Project Reference</h3>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/28158464_597730207233729_4450512397072859136_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/28158464_597730207233729_4450512397072859136_n.jpg" /><div class="info"><h2>Durotech Project</h2>
								  </div></div>
								</a>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/28156943_1851826481503969_6097429456538828800_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/28156943_1851826481503969_6097429456538828800_n.jpg" /><div class="info"><h2>Durotech Project</h2>
								  </div></div>
								</a>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/28156646_754753851380933_7812861694031429632_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/28156646_754753851380933_7812861694031429632_n.jpg" /><div class="info"><h2>Durotech Project</h2>
								  </div></div>
								</a>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/27575623_1428286160614390_6940262366848221184_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/27575623_1428286160614390_6940262366848221184_n.jpg" /><div class="info"><h2>Durotech Project</h2>
								  </div></div>
								</a>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/26300610_186308615435407_4746681947256782848_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/26300610_186308615435407_4746681947256782848_n.jpg" /><div class="info"><h2>Durotech Project</h2>
								  </div></div>
								</a>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/25038912_1970632336531034_9071819245322502144_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/25038912_1970632336531034_9071819245322502144_n.jpg" /><div class="info"><h2> Durotech Project</h2>
								  </div></div>
								</a>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/25012773_1962412800649322_1587339729857150976_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/25012773_1962412800649322_1587339729857150976_n.jpg" /><div class="info"><h2> Durotech Project</h2>
								  </div></div>
								</a>
								
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/24331982_151214638850996_6773179849746939904_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/24331982_151214638850996_6773179849746939904_n.jpg" /><div class="info"><h2>Durotech Project </h2>
								  </div></div>
								</a>
								
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/24274376_2003518576599332_8243324477740417024_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/24274376_2003518576599332_8243324477740417024_n.jpg" /><div class="info"><h2>Durotech Project </h2>
								  </div></div>
								</a>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/23966908_236811296855571_457670542535688192_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/23966908_236811296855571_457670542535688192_n.jpg" /><div class="info"><h2>Durotech Project </h2>
								  </div></div>
								</a>
								
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/23825230_289386534903904_2511455901265690624_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/23825230_289386534903904_2511455901265690624_n.jpg" /><div class="info"><h2>Durotech Project </h2>
								  </div></div>
								</a>
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/23825158_1962145440667760_3328675765515452416_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/23825158_1962145440667760_3328675765515452416_n.jpg" /><div class="info"><h2>Durotech Project </h2>
								  </div></div>
								</a>
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/23824077_398964173853966_7099736216240652288_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/23824077_398964173853966_7099736216240652288_n.jpg" /><div class="info"><h2> Durotech Project</h2>
								  </div></div>
								</a>
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/23594329_460542551009104_5923777297332043776_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/23594329_460542551009104_5923777297332043776_n.jpg" /><div class="info"><h2> Durotech Project</h2>
								  </div></div>
								</a>
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/23279660_1788961471397267_5797136359793623040_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/23279660_1788961471397267_5797136359793623040_n.jpg" /><div class="info"><h2> Durotech Project</h2>
								  </div></div>
								</a>
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/23279301_132247677489651_433455559870513152_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/23279301_132247677489651_433455559870513152_n.jpg" /><div class="info"><h2>Durotech Project </h2>
								  </div></div>
								</a>
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/22858188_362050654216651_6599849516734611456_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/22858188_362050654216651_6599849516734611456_n.jpg" /><div class="info"><h2>Durotech Project </h2>
								  </div></div>
								</a>
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/22710293_1941582779426397_6436727509236056064_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/22710293_1941582779426397_6436727509236056064_n.jpg" /><div class="info"><h2> Durotech Project</h2>
								  </div></div>
								</a>
								<a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_images/22709357_2386165134855601_8803433224396079104_n.jpg" title="" >
									<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_images/22709357_2386165134855601_8803433224396079104_n.jpg" /><div class="info"><h2>Durotech Project </h2>
								  </div></div>
								</a>
								
								<!--a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_1.jpg" title="Kurnell, Substation" ><div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_1.jpg" />
								  <div class="info">
									<h2>Kurnell, Substation   </h2>
								  </div>
								  
							  </div></a>
							
							  <a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_2.jpg" title="Belmore Park, Substation" >	<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_2.jpg" />
								  <div class="info">
									<h2>Belmore Park, Substation</h2>
								  </div>
								 
							  </div></a>
							 
							  	 <a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_3.jpg" title="City North, Substation" ><div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_3.jpg" />
								  <div class="info">
									<h2>City North, Substation</h2>
								  </div>
								 
							  </div></a>
							  
							   <a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_4.jpg" title="Queenwood School for Girls, Mosman" >	<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_4.jpg" />
								  <div class="info">
									<h2>Queenwood School for Girls, Mosman</h2>
								  </div>
								  
							  </div></a>
							 
							   <a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_5.jpg" title="Railcorp" >	<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_5.jpg" />
								  <div class="info">
									<h2>Railcorp</h2>
								  </div>
								 
							  </div></a>
							 
							   <a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_6.jpg" title="Turramurra-Substation " >	<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_6.jpg" />
								  <div class="info">
									<h2>Turramurra-Substation </h2>
								  </div>
								 
							  </div></a>
							
							   <a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_7.jpg" title="western sydney university" >  	<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_7.jpg" />
								  <div class="info">
									<h2>western sydney university</h2>
								  </div>
								  
							  </div></a>
							  
							   <a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_8.jpg" title="western sydney university parramatta" >  	<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_8.jpg" />
								  <div class="info">
									<h2>western sydney university parramatta</h2>
								  </div>
								  
							  </div></a>
							 
							  <a class="fancybox" href="<?php echo SITEURL ;?>customdurotech/images/project_9.jpg" title="Global Switch 400-Harris St Ultimo" >     	<div class="item">
									<img src="<?php echo SITEURL ;?>customdurotech/images/project_9.jpg" />
								  <div class="info">
									<h2>Global Switch 400-Harris St Ultimo</h2>
								  </div>
								  
							  </div></a-->
							  
							</div>
							<div role="tabpanel" class="tab-pane fade" id="Section3">
								<h3>Product Brochure</h3>
								<a target="_blank" href="<?php echo SITEURL.'wp-content/uploads/Durotech_Brochure.pdf'?>" class="btn effect5">Company Profile</a>
								<a target="_blank" href="<?php echo SITEURL.'wp-content/uploads/Product system.pdf'?>"  class="btn effect5">Waterproofing Systems</a>
								<a target="_blank" href="<?php echo SITEURL.'wp-content/uploads/Epoxy Flooring Catalog 8 pages.pdf'?>"  class="btn effect5">Epoxy Flooring System</a>
								<a target="_blank" href="<?php echo SITEURL.'wp-content/uploads/Product Catalog- Yellow book.pdf'?>"  class="btn effect5">Product Catalogue </a>
								<a target="_blank" href="<?php echo SITEURL.'wp-content/uploads/Dow Durotech Brochure 8 pages.pdf'?>"  class="btn effect5">Dow Spray Polyurea</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		    </div>
		

		
        </div>
		<?php if(!empty($project)){ ?>
		<script>		
			$(document).ready(function(){
				$("#project").trigger('click');			
			});		
		</script>
		<?php } ?>