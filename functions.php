<?php
// Functions.php file contains all the Functions required in the project.
require_once __DIR__ .'/Facebook/autoload.php';

function getAlbumData( $fb, $accessToken ) {

	$fbApp	 = $fb->getApp();
	$request = new Facebook\FacebookRequest( $fbApp, $accessToken, 'GET', '/me/', array( 'fields' => 'albums'));
	try {
		$response = $fb->getClient()->sendRequest( $request );
	} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	return $response;
}

?>