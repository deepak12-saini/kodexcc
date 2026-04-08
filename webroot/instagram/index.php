<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$url = 'https://api.instagram.com/oauth/access_token';
$redirectUri = 'https://www.durotechindustries.com.au/instagram/index.php';
$clientId = 'baa62c52f86d4f779755efab94d44844';
$client_secret = ' d10c1f75f7f04a46b313739c3ea6e905 ';
$code = '';

require('autoload.php');

use Instagram\InstagramClient;

$config = array(
	'client_id' => $clientId,
	'client_secret' => $client_secret,
	'redirect_uri' => $redirectUri
);

try {
	$ig = new InstagramClient( $config );
} catch (Exception $e) {
	echo $e->getMessage();
}



if ( isset($ig) && $ig ) {


	/**
	 * Get a new access token with OAuth
	 */

	if ( isset($_GET['code']) ) {
		$ig->get_access_token( $fresh = true, $_GET['code'] );

		print_r( $ig->get_data() );

		/**
		 * Make API requests. See various methods underneath.
		 */
	}

	/**
	 * Or display a login with Instagram link for redirect user for OAuth
	 */
	else {
		echo '<a href="' . $ig->get_oauth_url() . '">Login with Insgatram</a>';
	}


	/**
	 * Or set an existing access token
	 */

	$ig->set_access_token( 'A_valid_access_token_obtained_previously' );

	print_r( $ig->get_data() );

	/**
	 * Make API requests. See various methods underneath.
	 */

}




?>