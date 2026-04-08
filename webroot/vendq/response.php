
<?php 
	
	require('config.php');
	
	$client_id='YimWWhGJFu3pRMqBl4eqsqtiBb3Ett1v';
	$client_secret='mIw0fHEIK23ibbjoZ632R3mweE2klvph';
	$code = $_REQUEST['code'];
	$domain_prefix = $_REQUEST['domain_prefix'];
	$user_id = $_REQUEST['user_id'];
	$signature = $_REQUEST['signature'];
	
	$param = "code=$code&client_id=$client_id&client_secret=$client_secret&grant_type=authorization_code&redirect_uri=https://kgcc.com.au/customkodex/vendq/response.php";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://".$domain_prefix.".vendhq.com/api/1.0/token");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$param);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	echo $server_output = curl_exec($ch);

	curl_close ($ch);
	$serveroutput  = json_decode($server_output, true);
	
	if(!empty($serveroutput['access_token'])){
		$access_token = $serveroutput['access_token'];
		$token_type = $serveroutput['token_type'];
		$refresh_token = $serveroutput['refresh_token'];
		
		//echo "update configs set access_token = '".$access_token."' , refreshtoken = '".$refresh_token."'";
		mysqli_query($link,"update configs set access_token = '".$access_token."' , refreshtoken = '".$refresh_token."'");	
	}
	echo 'success';
	
?>

