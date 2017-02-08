<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfigFile( 'client_id.json' );
$client->setRedirectUri( 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/oauth2callback.php' );
$client->addScope( 'https://picasaweb.google.com/data/' );

if ( !isset( $_GET[ 'code' ] ) ) {
	$auth_url = $client->createAuthUrl();
	header( 'Location: ' . filter_var( $auth_url, FILTER_SANITIZE_URL ) );
} else {
	$token = $client->fetchAccessTokenWithAuthCode( $_GET[ 'code' ] );
	echo "<pre>";
	print_r( $token );
	echo "</pre>";

	$client->authenticate( $_GET[ 'code' ] );
	$_SESSION[ 'access_token' ]	 = $client->getAccessToken();
	$redirect_uri				 = 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/googlelogin.php';
	header( 'Location: ' . filter_var( $redirect_uri, FILTER_SANITIZE_URL ) );
}