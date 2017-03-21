<?php
/**
 * Handles the requests sent by the project.
 * 
 * @category Functionality
 * @package  FBAlbumBrowser
 * @author   Shashwat Mittal <shashwat.mittal5@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  1.0.0
 */
session_start();

//Required
require_once __DIR__ . '/vendor/autoload.php';

//Include the App ID, App Secret and the Callback url. Required!
require_once 'config.php';
// Functions file stores all the functions required for the project.
require_once 'functions.php';

$accessToken = $_SESSION[ 'accessToken' ];

$userID = getUserData($fb, $accessToken);

$zipPath = '/var/www/albums-'.userHash($userID).'.zip';

/**
 * Donwloads the Photos on the server and saves them inside a folder named as the Album Name.
 */
if (isset($_GET[ 'albums' ]) ) {

    $albums = $_GET[ 'albums' ];
    if (!empty($albums) && is_array($albums) ) {
        foreach ( $albums as $album ) {
            $data = getPhotosForAlbumId($album[ 'id' ], $fb, $accessToken);
            foreach ( $data as $url ) {
                $albumPath = 'images-' . userHash( $userID ) . '/' . trim( $album[ 'name' ] );
                //Download and store image in the folder
                downloadImage( $url, $albumPath );
            }
        }
    }
}


//Download is finished, This zips all the albums downloaded on the server.
if (isset($_GET[ 'zip' ]) ) {
    zip($_SERVER[ 'DOCUMENT_ROOT' ] . '/' . 'images-'.userHash($userID), $zipPath);
}

// Deletes the images once they are zipped and ready to be downloaded.
if (isset($_GET[ 'deleteImages' ]) ) {
    deletePath('images-'.  userHash( $userID).'');
    echo 'Images Deleted.';
}

// Deletes the zip file if the Modal is closed.
if (isset($_GET[ 'deleteZip' ]) ) {
    deletePath( $zipPath );
    echo 'Zip file deleted.';
}

if (isset($_GET[ 'ID' ]) ) {
    $data = getPhotosForAlbumId($_GET[ 'ID' ], $fb, $accessToken);
    echo json_encode($data);
}