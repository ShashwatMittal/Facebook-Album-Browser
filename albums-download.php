<?php

/**
 * Downloads the file after user authentication. Otherwise redirects the user to the Home Page.
 * 
 * PHP Version 7
 * 
 * @category Functionality
 * @package  FBAlbumBrowser
 * @author   Shashwat Mittal <shashwat.mittal5@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  1.0.0
 * @link     https://fb.shashwatmittal.com/albums-download.php
 */
session_start();

//Include Facebook PHP SDK
require_once __DIR__ . '/vendor/autoload.php';

//Include the App ID, App Secret and the Callback url. 
require_once 'config.php';
// Functions file stores all the functions required for the project.
require_once 'functions.php';

// Saving the accessToken from the Session into an Access Token variable for sending requests.
if ( isset( $_SESSION[ 'accessToken' ] ) ) {
    $accessToken = $_SESSION[ 'accessToken' ];

    $userID = getUserData( $fb, $accessToken );

    $file = '/var/www/albums-' . userHash( $userID ) . '.zip';
    if ( file_exists( $file ) ) {
        header( 'Content-Description: File Transfer' );
        header( 'Content-type: application/octet-stream' );
        header( 'Content-Disposition: attachment; filename=' . basename( $file ) );
        header( 'Content-Transfer-Encoding: binary' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header( 'Pragma: public' );
        header( 'Content-Length: ' . filesize( $file ) );
        ob_clean();
        flush();
        readfile( $file );
        exit;
    } else {
        // Redirects Logged in users to the Home Page.
        header( 'Location: http://fb.shashwatmittal.com/index.php' );
    }
} else {
    // Redirects unauthorized users to the Home Page.
    header( 'Location: http://fb.shashwatmittal.com/index.php' );
}