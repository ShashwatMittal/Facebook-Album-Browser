<?php

// This file contains all the Functions required in the project.
require_once __DIR__ . '/Facebook/autoload.php';

// Sends a request to the FB Graph API and returns the Name, Id, and Cover Photo of all the Albums.
function getAlbumData( $fb, $accessToken ) {

	$fbApp	 = $fb->getApp(); // Gets the FB App to send the request.
	$request = new Facebook\FacebookRequest( $fbApp, $accessToken, 'GET', '/me/', array( 'fields' => 'name,albums{id,name,picture{url}}' ) );
	try {
		$response = $fb->getClient()->sendRequest( $request );
	} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
		// When validation fails or other local issues
		header( "Location: index.php" ); // Redirects the user to the Login page to get the access token.
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	$responseData = $response->getDecodedBody(); // Decoding the data from the response object.
	return $responseData; // Returning the response data.
}

// Sends a request to the FB Graph API and returns all the photos for the Specific Album the request has been made for.
// Arguments involve the Album ID for which the request has been made, Facebook Object and Access Token.
function getPhotosForAlbumId( $id, $fb, $accessToken ) {
	$fbApp	 = $fb->getApp(); // Gets the Fb App to send the request.
	$request = new Facebook\FacebookRequest( $fbApp, $accessToken, 'GET', $id, array( 'fields' => 'photos{images}' ) );
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
	// Decoding the response Data into an Array object.
	$responseData	 = $response->getDecodedBody();
	// Inititalizing an Array which will hold the urls of all the images of the album.
	$photoArray		 = [ ];
	// Iterating over the response and saving the photos url in the $photoArray.
	foreach ( $responseData[ 'photos' ][ 'data' ] as $value ) {
		array_push( $photoArray, $value[ 'images' ][ 0 ][ 'source' ] );
	}
	// Checking for next page in case more than 25 Photos exist.
	if ( !isset( $responseData[ 'photos' ][ 'paging' ][ 'next' ] ) ) {
		return $photoArray;
	} else {
		// Parsing the next page URL from the Response.
		$nextPageRequest = $responseData[ 'photos' ][ 'paging' ][ 'next' ];
		while ( $nextPageRequest != NULL ) {
			$responseData		 = file_get_contents( $nextPageRequest );
			$formattedResponse	 = json_decode( $responseData, True ); // Converting the Response into an Array object for Parsing

			foreach ( $formattedResponse[ 'data' ] as $value ) {
				array_push( $photoArray, $value[ 'images' ][ 0 ][ 'source' ] );
			}
			$nextPageRequest = $responseData[ 'paging' ][ 'next' ];
		}

		return $photoArray;
	}
}

// Downloads the images on the server on the specified location into specific album folders.
function download_image( $url, $destination_path = '' ) {
	// CHECKS IF CURL DOES EXISTS. SOMETIMES WEB HOSTING DISABLES FILE GET CONTENTS
	if ( function_exists( 'curl_version' ) ) {
		$ch			 = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$content	 = curl_exec( $ch );
		$httpcode	 = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
	} else {
		$content = file_get_contents( $url );
	}
	// CHECKS IF DIRECTORY DOESNT EXISTS AND DESTINATION PATH IS NOT EMPTY
	if ( !file_exists( $destination_path ) && $destination_path != '' ) {
		mkdir( $destination_path, 0755, true );
	}
	// ATTEMPT TO CREATE THE FILE
	$fp = fopen( $destination_path . '/' . date( 'YmdHis' ) . ".jpg", "a+" );
	fwrite( $fp, $content );
	fclose( $fp );
}

// Zips the folder path provided to the function into a zip file as per the specified destination of the zip.
function zip( $source, $destination ) {
	if ( !extension_loaded( 'zip' ) || !file_exists( $source ) ) {
		return false;
	}
	$zip = new ZipArchive();
	if ( !$zip->open( $destination, ZIPARCHIVE::CREATE ) ) {
		return false;
	}

	$source = str_replace( '\\', '/', realpath( $source ) );

	if ( is_dir( $source ) === true ) {
		$files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $source ), RecursiveIteratorIterator::SELF_FIRST );

		foreach ( $files as $file ) {
			$file = str_replace( '\\', '/', $file );

			// Ignore "." and ".." folders
			if ( in_array( substr( $file, strrpos( $file, '/' ) + 1 ), array( '.', '..' ) ) )
				continue;

			$file = realpath( $file );

			if ( is_dir( $file ) === true ) {
				$zip->addEmptyDir( str_replace( $source . '/', '', $file . '/' ) );
			} else if ( is_file( $file ) === true ) {
				$zip->addFromString( str_replace( $source . '/', '', $file ), file_get_contents( $file ) );
			}
		}
	} else if ( is_file( $source ) === true ) {
		$zip->addFromString( basename( $source ), file_get_contents( $source ) );
	}

	return $zip->close();
}

// Deletes the Folder passed to it recursively. Should be cautiously. 
function deleteImages( $dirPath ) {
	$dir	 = $dirPath;
	$it		 = new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS );
	$files	 = new RecursiveIteratorIterator( $it, RecursiveIteratorIterator::CHILD_FIRST );
	foreach ( $files as $file ) {
		if ( $file->isDir() ) {
			rmdir( $file->getRealPath() );
		} else {
			unlink( $file->getRealPath() );
		}
	}
	rmdir( $dir );
}

function deleteZip( $dirPath ) {

	if ( file_exists( $dirPath ) ) {
		unlink( $dirPath );
	}
	else{
		echo "No file deleted.";
	}
}

?>