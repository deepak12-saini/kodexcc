<?php 
	
	
	require('config.php');
	
	$sql = mysqli_query($link,"select * from configs");
		$num  =  mysqli_num_rows($sql);	
		if($num > 0){
			$fetch  =  mysqli_fetch_assoc($sql);
			
			$access_token = $fetch['access_token'];
			$refreshtoken = $fetch['refreshtoken'];
		}
	}

	$client_id='TsIhOP3oP76NwINRDlJSX9EvwfwUjRL8';
	$client_secret='tvra3t7TBQEy8Ux73swW4MiDAjT8Hb4R';

	
	$param = "refresh_token=$refreshtoken&client_id=$client_id&client_secret=$client_secret&grant_type=refresh_token";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://".$domain_prefix.".vendhq.com/api/1.0/token");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$param);

	// In real life you should use something like:
	// curl_setopt($ch, CURLOPT_POSTFIELDS, 
	//          http_build_query(array('postvar1' => 'value1')));

	// Receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close ($ch);
	$server_output  = json_decode($server_output);

	echo '<pre>';
	print_r($server_output);
	
?>