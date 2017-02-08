<?php

require_once __DIR__ . '/vendor/autoload.php';
session_start();

use GuzzleHttp\Psr7\Request;
// Get a service account key from https://console.developers.google.com/ 
putenv('GOOGLE_APPLICATION_CREDENTIALS=client_id.json');
$user_to_impersonate = "shashwat.mittal5@gmail.com"; // email address of the user to upload data with
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setSubject($user_to_impersonate);
$client->addScope('https://picasaweb.google.com/data/');
$client->setAuthConfig('client_id.json');


//$client		 = new Google_Client();
//$client->setAuthConfig( 'client_id.json' ); // Client Credentials. Sent to Google for Authentication.
//$client->addScope( 'https://picasaweb.google.com/data/' ); // Scope or Permissions requested from the User.
//$auth_url	 = $client->createAuthUrl();
// returns a Guzzle HTTP Client
$httpClient	 = $client->authorize();
echo "<pre>HTTP Client Object";
print_r( $httpClient );
echo "</pre>";

function create_album_test() {
	if ( isset( $_SESSION[ 'access_token' ] ) && $_SESSION[ 'access_token' ] ) {
		$access_token	 = $_SESSION[ 'access_token' ];
		$client->setAccessToken( $_SESSION[ 'access_token' ] );
		$url			 = 'https://picasaweb.google.com/data/feed/api/user/default';
		$rawXML			 = "<entry xmlns='http://www.w3.org/2005/Atom'
    xmlns:media='http://search.yahoo.com/mrss/'
    xmlns:gphoto='http://schemas.google.com/photos/2007'>
  <title type='text'>Test Album</title>
  <summary type='text'>This is a test album.</summary>
  <gphoto:location>Pune</gphoto:location>
  <gphoto:access>public</gphoto:access>
  <gphoto:timestamp>1152255600000</gphoto:timestamp>
  <media:group>
    <media:keywords>test, picasa</media:keywords>
  </media:group>
  <category scheme='http://schemas.google.com/g/2005#kind'
    term='http://schemas.google.com/photos/2007#album'></category>
</entry>";

		$queryData	 = array( 'access_token' => $access_token[ 'access_token' ], 'v' => '2' );
		$query		 = http_build_query( $queryData );
		$query_url	 = $url . $query;
		$data		 = http_post( $query_url, $rawXML );
		var_dump( $data );
		die();
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
}

function create_album( $httpClient ) {
	echo "=======================================\n";
	echo "Creating album\n";
	echo "---------------------------------------\n";
	$xmlData		 = "" .
	"<entry xmlns='http://www.w3.org/2005/Atom' xmlns:media='http://search.yahoo.com/mrss/' xmlns:gphoto='http://schemas.google.com/photos/2007'>
	  <title type='text'>1234123</title>
	  <category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/photos/2007#album'></category>
	</entry>";
	$xml			 = simplexml_load_string( $xmlData );
	$xml->title		 = 'Test Album';
	$response		 = $httpClient->post( 'https://picasaweb.google.com/data/feed/api/user/default', [
		'headers'	 => ['Content-Type' => 'application/atom+xml' ],
		'body'		 => $xml->asXML()
	] );
	echo "<pre>";
	print_r( $response );
	echo "</pre>";
	$xml_response	 = simplexml_load_string( $response->getBody() );
	echo "id: " . $xml_response->id . "\n";
	echo "title: " . $xml_response->title . "\n";
	echo "=======================================\n";
}

function http_post( $url, $rawXML ) {
//	$ch = curl_init();
//	curl_setopt( $ch, CURLOPT_URL, $url );
//	curl_setopt( $ch, CURLOPT_POST, true );
//	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//	curl_setopt( $ch, CURLOPT_HEADER, 'GData-Version:  2' );
//	curl_setopt( $ch, CURLOPT_HEADER, 'Content-Length: 987' );
//
//	$result = curl_exec( $ch );
//	curl_close( $ch );

	$ch			 = curl_init();
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_HEADER, array( 'Content-Type', 'application/atom+xml' ) );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, urlencode( "XML=" . $rawXML ) );
	$content	 = curl_exec( $ch );
	$header_size = curl_getinfo( $content, CURLINFO_HEADER_SIZE );
	$header		 = substr( $content, 0, $header_size );
	$body		 = substr( $content, $header_size );
	echo "<pre>";
	print_r( $header );
	print_r( $body );
	echo "</pre>";
	exit;
	return $content;
}
create_album($httpClient);