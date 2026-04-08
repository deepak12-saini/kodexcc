<?php

require 'sendgrid/vendor/autoload.php';
		

	$message = '<div class="certificate_container" style="width: 90%; margin: 0 auto;">
	   <div class="thanku_page">			

			<p style="margin-bottom:10px">Dear sam,</p>			
		
			<p style="text-align:;">We have attached Durotech Architects CPD Points. Please find the attachedment!</p>			
			
			<p>Best Regards,</p>
			<p>Durotechindustries.com.au</p>		
			</div>			       
	  </div>  
	</div>';

	$fileName = 'New Refuel CPD presentation.pdf';
	$filePath = dirname(__FILE__).'/'.$fileName;
	$toemail = 'web@xoroglobal.com';
	try{
		$subject = 'Durotech Architects CPD Presentation';
		$from = new SendGrid\Email("Durotechindustries", 'sales@durotechindustries.com.au');
		$to = new SendGrid\Email("Durotech Architects CPD Presentation", $toemail);
		$content = new SendGrid\Content("text/html", $message);
		
		$file_encoded = base64_encode(file_get_contents($filePath));
		$attachment = new SendGrid\Attachment();
        $attachment->setContent($file_encoded);
        $attachment->setContentId("123");
        $attachment->setType("pdf");
        $attachment->setDisposition("attachment");
        $attachment->setFilename("New Refuel CPD presentation.pdf");	
			
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$mail->addAttachment($attachment);
		
		$bcc = new SendGrid\Email("Durotechindustries", 'rsb@durotechindustries.com.au');
		$mail->personalization[0]->addBcc($bcc);
	
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		
		$response = $sg->client->mail()->send()->post($mail); 
	}catch(Exception $e){
		
	}
	echo 'success';
?>