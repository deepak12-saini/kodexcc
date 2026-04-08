<?php

require 'sendgrid/vendor/autoload.php';
		
	$name = ucfirst($_REQUEST['name']);
	$email = $_REQUEST['email'];
	$subject = $_REQUEST['subject'];
	$type = $_REQUEST['type'];
	$company = $_REQUEST['company'];
	$phone = $_REQUEST['phone']; 
	if($type == 1){
		$doc = 'INSTALLATION OF DUROMASTIC P-15 WATERPROOFING MEMBRANE';
	}else{
		$doc = 'Waterproofing Specification';
	}	
	
	echo $message = '<div class="certificate_container" style="width: 90%; margin: 0 auto;">
	   <div class="thanku_page">
			
			<p style="text-align:center;"><img  src="https://www.durotechindustries.com.au/customdurotech/images/durotech_logo.png"></p>
			
		    <div  class="content" style="padding:20px 0px 5px 10px;font-size:14px;font-family:lato,sans-serif;border: none; color:#353535; z-index: -999999;margin-top: -41px;margin-left:10px;margin-right: 20px;" > 
		   
			<p style="margin-bottom:10px">Dear '.$name.',</p>
			
		
			<p style="text-align:;">We have attached '.strtolower($doc).'. Please find the attachedment!</p>			
			
			<p>Best Regards,</p>
			<p>Durotechindustries.com.au</p>		
			</div>			       
			
			</div>	
		   <br>  <br>
		       
	  </div>  
	</div>';
	
	if($type == 2){
		$fileName = '4 seasons Saba tiling.pdf';
	}else{
		$fileName = 'Method statement duromastic P15.pdf';
	}	
	
	$filePath = dirname(__FILE__).'/'.$fileName;
	
	try{
		$senderemail = 'sales@durotechindustries.com.au';
		#$subject = 'CWS :- Waterproofing and Epoxy Flooring';
		$from = new SendGrid\Email("Durotech Specification", $senderemail);
		$to = new SendGrid\Email("Durotech Specification", $email);
		$content = new SendGrid\Content("text/html", $message);
		
		$file_encoded = base64_encode(file_get_contents($filePath));
		$attachment = new SendGrid\Attachment();
        $attachment->setContent($file_encoded);
        $attachment->setContentId("123");
        $attachment->setType("pdf");
        $attachment->setDisposition("attachment");
       
		if($type == 2){
			$attachment->setFilename("4 seasons Saba tiling.pdf");				
		}else{
			$attachment->setFilename("Method statement duromastic P15.pdf");	
		}	
		
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$mail->addAttachment($attachment);
		
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		
		$response = $sg->client->mail()->send()->post($mail); 
	}catch(Exception $e){
		
	}
	echo 'success';
?>