<?php

session_start();

//Required
require_once __DIR__ . '/vendor/autoload.php';

//Include the App ID, App Secret and the Callback url. Required!
require_once 'config.php';
// Functions file stores all the functions required for the project.
require_once 'functions.php';

$accessToken = $_SESSION[ 'accessToken' ];

//Donwloads the Photos on the server and saves them inside a folder named as the Album Name.
if ( isset( $_GET[ 'albumID' ] ) && isset( $_GET[ 'albumName' ] ) ) {

	$data = getPhotosForAlbumId( $_GET[ 'albumID' ], $fb, $accessToken );
	foreach ( $data as $url ) {
		download_image( $url, 'images/' . trim( $_GET[ 'albumName' ], ' ' ) );
	}
	echo $_GET[ 'albumName' ];
}

//This zips all the albums downloaded on the server.
if ( isset( $_GET[ 'zip' ] ) ) {
	zip( $_SERVER[ 'DOCUMENT_ROOT' ] . '/' . $_GET[ 'zip' ], 'albums.zip' );
}

// Deletes the images once they are zipped and ready to be downloaded.
if ( isset( $_GET[ 'deleteImages' ] ) ) {
	deleteImages( $_GET[ 'deleteImages' ] );
	echo 'Images Deleted.';
}

// Deletes the zip file if the Modal is closed.
if ( isset( $_GET[ 'deleteZip' ] ) ) {
	deleteZip( $_GET[ 'deleteZip' ] );
	echo 'Zip file deleted.';
}

if( isset($_GET[ 'ID' ])){
	$data = getPhotosForAlbumId( $_GET[ 'ID' ], $fb, $accessToken );
	echo json_encode($data);
}