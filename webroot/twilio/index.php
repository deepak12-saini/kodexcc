<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Required if your environment does not handle autoloading
require __DIR__ . '/vendor/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
/* $sid = $_REQUEST['sid'];
$token  = $_REQUEST['sid'];
$client = new Client($sid, $token);

$to = $_REQUEST['to'];
$from = $_REQUEST['from'];
$message = $_REQUEST['message']; */

$sid = 'AC3f7b3945befee89738e41737a90e5b7e';
$token  = 'a74b0b4e36fc33423725c0dede1702bb';

$twilio = new Client($sid, $token);
$calls = $twilio->calls->read();
$calllogs = array();
foreach ($calls as $record) {
	echo '<pre>';	
	print($record->sid);
	echo '</pre>';
	//$call['sid'] = $record->sid;
	// $call['from'] = $record->from;
	// $call['to'] = $record->to;
	// $call['date'] = $record->startTime->date;
    //$calllogs[] = $call;
}
// echo '<pre>';	
// print($calllogs);
// echo '</pre>';


?>