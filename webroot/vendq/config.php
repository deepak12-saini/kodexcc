<?php

session_start();
$db['hostname'] = 'localhost';
$db['username'] = 'customkodex';
$db['password'] = '5cE2Cx74tJQvD9@1233'; 
$db['database'] = 'admin_customkodex';
$link = mysqli_connect($db['hostname'], $db['username'], $db['password'], $db['database']);

date_default_timezone_set('Asia/Kolkata');
$site_url = "https://kgcc.com.au/customkodex/";
mysqli_query($link,"SET SESSION SQL_MODE := ''");

?>