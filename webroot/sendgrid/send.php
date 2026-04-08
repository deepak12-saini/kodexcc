<?php
require __DIR__ . '/vendor/autoload.php';

isset($_REQUEST['subject'])? $subject = $_REQUEST['subject'] : $subject = '';
isset($_REQUEST['message'])? $message = $_REQUEST['message'] : $message = '';
isset($_REQUEST['sendmail'])? $sendmail = $_REQUEST['sendmail'] : $sendmail = '';


/* $from = new SendGrid\Email("Durotech", "noreply@durotechindustries.com.au");
$to = new SendGrid\Email("Durotech", $sendmail);
$content = new SendGrid\Content("text/plain", 'test');
$mail = new SendGrid\Mail($from, $subject, $to, $content); */

$from = new SendGrid\Email("Durotech", "noreply@durotechindustries.com.au");
$to = new SendGrid\Email("Example User", "web@xoroglobal.com");
$content = new SendGrid\Content("text/plain", "and easy to do anywhere, even with PHP");
$mail = new SendGrid\Mail($from, $subject, $to, $content);

$apiKey = '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';
$sg = new \SendGrid($apiKey);

$response = $sg->client->mail()->send()->post($mail);

echo '<pre>';
print_r($response);
print_r($response);
die();

return 'success';

?>