<?php

require_once __DIR__ . '/vendor/autoload.php';
session_start();


$client		 = new Google_Client();
$client->setAuthConfig( 'client_id.json' ); // Client Credentials. Sent to Google for Authentication.
$client->addScope( 'https://picasaweb.google.com/data/' ); // Scope or Permissions requested from the User.
$auth_url	 = $client->createAuthUrl();

if ( isset( $_SESSION[ 'access_token' ] ) && $_SESSION[ 'access_token' ] ) {
	$access_token	 = $_SESSION[ 'access_token' ];
	$client->setAccessToken( $_SESSION[ 'access_token' ] );
	$url			 = 'https://picasaweb.google.com/data/feed/api/user/default';

	$queryData = array( 'access_token' => $access_token[ 'access_token' ], 'v' => '2' );

	$query = http_build_query( $queryData );

	$query_url = $url . $query;

	$data		 = file_get_contents( $query_url );
	var_dump( $data );
	$response	 = simplexml_load_string( $data );
	$entries	 = $response->entry; // XML Feed returned from Google's Servers consisting of all the links to the User photo albums.
	$albumNames	 = array();
	// Iterating over the response.
	foreach ( $entries as $entry ) {
		echo "<pre>";
		array_push( $albumNames, (string) $entry->title );
		print_r( $albumNames ); // Each entry represents the Data related to an Album in Picasa Web Album.
		echo "</pre>";
	}
} else {
	$redirect_uri = 'http://' . $_SERVER[ 'HTTP_HOST' ] . '/oauth2callback.php'; // Redirecting to Oauth CallBack.
	header( 'Location: ' . filter_var( $redirect_uri, FILTER_SANITIZE_URL ) );
}


