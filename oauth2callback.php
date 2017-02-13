<?php

session_start();
require_once __DIR__ . '/vendor/autoload.php'; //Required
require_once __DIR__ . '/googleConfig.php'; // Required

if ( !isset( $_GET[ 'code' ] ) ) {
	$auth_url = $client->createAuthUrl(); // Creating the Auth URL for Authorizing and granting access to the App 
	header( 'Location: ' . filter_var( $auth_url, FILTER_SANITIZE_URL ) );
} else {
	$client->authenticate( $_GET[ 'code' ] ); // Temporary Code sent by Google to receive Access Token.
	$_SESSION[ 'access_token' ]	 = $client->getAccessToken(); // Storing the Access Token in the Session from Google Client.
	
	if(isset($_SESSION['access_token']['refresh_token'])){
		$content = '<?php $refresh_token = "'.$_SESSION['access_token']['refresh_token'].'"; ?>';
		file_put_contents('refresh_token.php', $content);
	}
	
	$redirect_uri				 = 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/googleHome.php';
	header( 'Location: ' . filter_var( $redirect_uri, FILTER_SANITIZE_URL ) ); // Redirecting to home page after authorization.
}