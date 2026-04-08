<?php 
$url = 'http://sendgrid.com/';
$user = 'durotect8901';
$pass = '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'; 
 
$json_string = array( 
  'to' => array(
    'web@xoroglobal.com'
  ),
  'category' => ''
); 
 
$params = array(
    'api_user'  => $user,
    'api_key'   => $pass,
    'x-smtpapi' => json_encode($json_string),
    'to'        => 'web@xoroglobal.com',
    'subject'   => 'testing from curl',
    'html'      => 'testing body',
    'text'      => 'testing body',
    'from'      => 'noreply@durotechindustries.com.au',
  );
 
 
$request =  'https://api.sendgrid.com/v3/mail/send';
 
// Generate curl request
$session = curl_init($request);
// Tell curl to use HTTP POST
curl_setopt ($session, CURLOPT_POST, true);
// Tell curl that this is the body of the POST
curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
 
// obtain response
$response = curl_exec($session);
curl_close($session);
 
// print everything out
print_r($response);