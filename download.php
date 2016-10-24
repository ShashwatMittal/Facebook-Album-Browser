<?php

session_start();

require_once __DIR__ . '/Facebook/autoload.php';

require_once 'config.php';
require_once 'functions.php';

$accessToken = $_SESSION[ 'accessToken' ];

if ( isset( $_GET[ 'albumID' ] ) ) {

	$data = getPhotosForAlbumId( $_GET[ 'albumID' ], $fb, $accessToken );
	foreach ( $data as $url ) {
		download_image( $url, 'images/' . trim( $_GET[ 'albumName' ], ' ' ) );
	}
	echo $_GET[ 'albumName' ] . ' Downloaded.';
}else{
	echo 'The page you are trying to access does not exist.';
}