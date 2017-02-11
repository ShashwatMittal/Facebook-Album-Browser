<?php

require_once __DIR__ . '/vendor/autoload.php';
session_start();


$client = new Google_Client();
//$client->setApplicationName( 'Facebook Album Browser' );
//$client->setDeveloperKey( 'AIzaSyAuXZ6aTJY111jAksoUoqXG7mYjiwVOUh4' );
$client->setAuthConfig( 'client_secret.json' );
$client->addScope( 'https://picasaweb.google.com/data/' );
$client->setAccessType( 'offline' );
//$auth_url	 = $client->createAuthUrl();

if ( isset( $_SESSION[ 'access_token' ] ) && $_SESSION[ 'access_token' ] ) {
	//Refreshing the Access Token using the Refresh token recieved when the user authorizes the request.
	if ( $client->isAccessTokenExpired() ) {
		$client->revokeToken();
		$_SESSION[ 'access_token' ] = $client->refreshToken( '1/_k6Sk3rOPpCjSaHczgi-3i4xoeXxLWf59519N7qfQ58' );
	}

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


function getAlbumLink( $entry ) {
	$attributes	 = (array) $entry->link;
	$link		 = $attributes[ '@attributes' ][ 'href' ];
	echo "<pre>";
	uploadPhoto( $link );
//	print_r( $link );
	echo "</pre>";
}

// Converts the image data into binary and creates a CURL request and sends the post request to the servers.
function uploadPhoto( $link ) {
	$filename	 = __DIR__ . '/lib/images/image1.jpeg';
	$fileSize	 = filesize( $filename );
	$fh			 = fopen( $filename, 'rb' );
	$imgData	 = fread( $fh, $fileSize );
	fclose( $fh );
	$header		 = array('GData-Version: 3', 'Authorization: GoogleLogin auth= Bearer ' . $_SESSION[ 'access_token' ], 'Content-Type: image/jpeg', 'Content-Length: ' . $fileSize, 'Slug: ' . basename( $filename ) );
	echo "<pre>";
	print_r( curl_post( $link, $header, $imgData ) );
	echo "</pre>";
//	$context		 = stream_context_create( array( http => array( 'method' => 'POST', 'header' => 'Content-Type: image/jpeg\r\n' . 'Content-Length: ' . $imgDataLength . '\r\n' . 'Slug: ' . basename( $filename ) . '\r\n\r\n' . $imageData ) ) );
//	var_dump($response);exit;
//	echo "</pre>";
//	return $response;
}

function curl_post( $link, $header, $data ) {
	$ch		 = curl_init();
	$options = array(
		CURLOPT_URL				 => $link,
		CURLOPT_POST			 => TRUE,
		CURLOPT_RETURNTRANSFER	 => TRUE,
		CURLOPT_HEADER			 => TRUE,
		CURLOPT_FOLLOWLOCATION	 => TRUE,
		CURLOPT_POSTFIELDS		 => $data,
		CURLOPT_HTTPHEADER		 => $header,
	);
	curl_setopt_array( $ch, $options );
	$ret	 = curl_exec( $ch );
	curl_close( $ch );
	return $ret;
}
