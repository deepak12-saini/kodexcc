<?php 
	
	
	require('config.php');	
	$exec = mysqli_query($link,"select * from  configs");
	$fetch = mysqli_fetch_assoc($exec);
	
	$refreshtoken = $fetch['refreshtoken'];
	$access_token = $fetch['access_token'];
	
	$client_id='TsIhOP3oP76NwINRDlJSX9EvwfwUjRL8';
	$client_secret='tvra3t7TBQEy8Ux73swW4MiDAjT8Hb4R';
		
	$param = "refresh_token=$refreshtoken&client_id=$client_id&client_secret=$client_secret&grant_type=refresh_token";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://xoros.vendhq.com/api/1.0/token");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$param);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close ($ch);
	$serveroutput  = json_decode($server_output, true);
	
	
	if(!empty($serveroutput['access_token'])){
		$access_token = $serveroutput['access_token'];		
		//echo "update configs set access_token = '".$access_token."' , refreshtoken = '".$refresh_token."'";
		mysqli_query($link,"update configs set access_token = '".$access_token."'");	
	}
	
	echo 'success';
?>