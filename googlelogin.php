<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php'; // Required!
require_once __DIR__ . '/googleConfig.php';  // Required!

if ( isset( $_SESSION[ 'access_token' ] ) && $_SESSION[ 'access_token' ] ) {
	// Checks if the Access Token is expired and refreshes it if required.
	if ( $client->isAccessTokenExpired() ) {
		$_SESSION[ 'access_token' ] = $client->refreshToken( '1/_k6Sk3rOPpCjSaHczgi-3i4xoeXxLWf59519N7qfQ58' );
	}
	error_log(var_export($_SESSION[ 'access_token' ],true));
	$client->setAccessToken( $_SESSION[ 'access_token' ] );
	$data		 = array( 'access_token' => $_SESSION[ 'access_token' ][ 'access_token' ] );
	$response	 = file_get_contents( 'https://picasaweb.google.com/data/feed/api/user/default' . '?' . http_build_query( $data ) );
	$result		 = simplexml_load_string( $response );

	foreach ( $result->entry as $entry ) {
		echo "<pre>";
		$albumData = getAlbumLink( $entry );
		print_r( $albumData );
		echo "</pre>";
	}
} else {
	$redirect_uri = 'https://' . $_SERVER[ 'HTTP_HOST' ] . '/oauth2callback.php';
	header( 'Location: ' . filter_var( $redirect_uri, FILTER_SANITIZE_URL ) );
}

