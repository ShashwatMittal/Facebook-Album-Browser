<?php
session_start();

require_once __DIR__ . '/Facebook/autoload.php';

require_once 'config.php';
require_once 'functions.php';

$accessToken = $_SESSION[ 'accessToken' ];

if ( isset( $_GET[ 'albumID' ] ) && isset( $_GET[ 'albumName' ] ) ) {

	$data = getPhotosForAlbumId( $_GET[ 'albumID' ], $fb, $accessToken );
	foreach ( $data as $url ) {
		download_image( $url, 'images/' . trim( $_GET[ 'albumName' ], ' ' ) );
	}
	echo $_GET[ 'albumName' ];
}

if ( isset( $_GET[ 'zip' ] ) ) {
	zip( $_SERVER[ 'DOCUMENT_ROOT' ] . '/' . $_GET[ 'zip' ], 'albums.zip' );
}

if( isset($_GET['delete'])){
	deleteImages($_GET['delete']);
	echo 'Images Deleted.';
}