<?php

session_start();
require_once __DIR__ . '/vendor/autoload.php'; // Required
require_once __DIR__ . '/googleConfig.php'; // Required

// Setting the access token for the Google Client.
$client->setAccessToken( $_SESSION[ 'access_token' ] );

if ( isset( $_SESSION[ 'access_token' ] ) && $_SESSION[ 'access_token' ] ) {
	// Checks if the Access Token is expired and refreshes it if required.
	if ( $client->isAccessTokenExpired() ) {
		$_SESSION[ 'access_token' ] = $client->refreshToken( '1/OeKcbqo8yR8_c90MmRG5i5ADaJtsHeJaGFE4TfUPMEg' );
		$client->setAccessToken( $_SESSION[ 'access_token' ] );
	}
	// Setting the query parameter in the data array to be sent with the GET request.
	$data		 = array( 'access_token' => $_SESSION[ 'access_token' ][ 'access_token' ], 'v' => '3' );
	$response	 = file_get_contents( 'https://picasaweb.google.com/data/feed/api/user/default' . '?' . http_build_query( $data ) );
	$result		 = simplexml_load_string( $response ); // Loading XML data from the response recieved from google.

	foreach ( $result->entry as $entry ) {
//		$link = getAlbumLink($entry);
//		var_dump($link);
		echo "<pre>";
		uploadPhoto( $client );
		echo "</pre>";

	}
}

// Gets the post link along with Album and User ID.
function getAlbumLink( $entry ) {
	$attributes	 = (array) $entry->link;
	$link		 = $attributes[ '@attributes' ][ 'href' ];
	return $link;
}

// Converts the image data into binary and the post request to the servers.
function uploadPhoto( $client ) {
	$filename	 = __DIR__ . '/lib/images/profile.JPG';
	$fileSize	 = filesize( $filename ); // Calculating the size of the File to be uploaded.
	$fh			 = fopen( $filename, 'rb' );
	$imgData	 = fread( $fh, $fileSize ); // Converting the file into Binary.
	fclose( $fh );
	// Setting the Header data to be sent with the POST request.
	$header		 = array( 'Authorization: GoogleLogin auth= ' . $client->getAccessToken(), 'Content-Type: image/jpeg', 'Content-Length: ' . $fileSize, 'Slug: ' . basename( $filename ) );
	print_r($header);
	print_r( curl_post( 'https://picasaweb.google.com/data/feed/api/user/default/albumid/default', $header, $imgData ) );
}

// Post request using CURL.
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
