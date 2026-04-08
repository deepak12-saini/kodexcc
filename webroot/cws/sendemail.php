<?php

require 'sendgrid/vendor/autoload.php';
		
	$inserIndivdualtname = $_REQUEST['inserIndivdualtname'];
	$insertcompanyname = $_REQUEST['insertcompanyname'];
	$insertbuildersname = $_REQUEST['insertbuildersname'];
	$projectname = $_REQUEST['projectname'];
	$date = $_REQUEST['date'];
	$insertname = $_REQUEST['insertname']; 
	$senderemail = $_REQUEST['senderemail']; 
	if(!empty($_REQUEST['subject'])){
		$subject = $_REQUEST['subject']; 
	}else{
		$subject = ' Durotech Industries :- Waterproofing and Epoxy Flooring'; 
	}	

	$message = '<div class="certificate_container" style="width: 90%; margin: 0 auto;">
	   <div class="thanku_page">
		    <img style="width:100%;" src="https://www.durotechindustries.com.au/cws/Durotech_banner.jpg">
		    <div  class="content" style="padding:20px 0px 5px 10px;font-size:14px;font-family:lato,sans-serif;border: none; color:#353535; z-index: -999999;margin-top: -41px;margin-left:10px;margin-right: 20px;" > 
		    <p>Attn: '.$insertname.'</p>
			<p>Company: '.$insertcompanyname.'</p>
			<p>Project Name: '.$projectname.'</p>
			<p>Specification: Waterproofing and Epoxy Flooring</p>
			<p>Email: '.$insertbuildersname.'</p>
			<p style="margin-bottom:40px">Date: '.$date.'</p>
			

			<p style="margin-bottom:10px">Dear '.$inserIndivdualtname.',</p>
						
			<p style="text-align:;">On behalf of Durotech Industries, we are writing to express our interest in providing detailed product specifications and quotations for supply of waterproofing, Coatings and Epoxy Flooring for the following project('.$projectname.') and other projects you may have coming in future.</p>
			
			<p>I am attaching company profile and brochure for your perusal, kindly feel free to contact us at your convenience, I will follow up in the next few days.</p>
			
			<br/><br/>
			<p>Best Regards,</p>
			<p>THE DUTOTECH TEAM - Protecting Your Future</p>
			<p>Phone: (02) 9603 1177</p>
			<p>Web: www.durotechindustries.com.au</p>
			<p>Email: sales@durotechindustries.com.au</p>
			
			</div>	
		   <br>  <br>
		   <p><img  style="width:100%;" src="https://www.durotechindustries.com.au/cws/Untitled-1%20copy.jpg"></p>  	   
	  </div>  
	</div>';
	

	$fileName = 'Durotech Company Profile-min.pdf';
	$fileName_1 = 'Waterproofing System Brochure_compress.pdf';
	$filePath = dirname(__FILE__).'/'.$fileName;
	$filePath_1 = dirname(__FILE__).'/'.$fileName_1;
	
	try{
		#$subject = 'Durotech :- Waterproofing and Epoxy Flooring';
		$from = new SendGrid\Email(" Durotech Industries", $senderemail);
		$to = new SendGrid\Email(" Durotech Industries", $insertbuildersname);
		$content = new SendGrid\Content("text/html", $message);
		
		$file_encoded = base64_encode(file_get_contents($filePath));
		$attachment = new SendGrid\Attachment();
        $attachment->setContent($file_encoded);
        $attachment->setContentId("123");
        $attachment->setType("pdf");
        $attachment->setDisposition("attachment");
        $attachment->setFilename("Durotech Company Profile.pdf");	 
		
		$file_encoded_1 = base64_encode(file_get_contents($filePath_1));
		$attachment2 = new SendGrid\Attachment();
        $attachment2->setContent($file_encoded_1);
        $attachment2->setContentId("123");
        $attachment2->setType("pdf");
        $attachment2->setDisposition("attachment");
        $attachment2->setFilename("Durotech Waterproofing Brochure.pdf");	 
		
			
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$mail->addAttachment($attachment);
		$mail->addAttachment($attachment2);
		
		
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		
		$response = $sg->client->mail()->send()->post($mail); 
	}catch(Exception $e){
		
	}
		
	echo 'success';
?>