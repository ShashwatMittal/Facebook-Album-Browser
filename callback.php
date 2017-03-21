<?php

/**
 * Uses the JS Helper class and gets the Facebook Client and Access Token 
 * retrieved from the Facebook Graph API and stores these variables in session.
 * 
 * @category Authorisation
 * @package  FBAlbumBrowser
 * @author   Shashwat Mittal <shashwat.mittal5@gmail.com>
 * @license  http://URL name
 * @version  1.0.0
 * @link     https://fb.shashwatmittal.com/callback.php
 */
session_start();

//Include Facebook PHP SDK
require_once __DIR__ . '/vendor/autoload.php';

//Include the App ID, App Secret and the Callback url.
require_once 'config.php';

// JS Helper Returns the Access Token and the Facebook CLient stored in the 
$jsHelper       = $fb->getJavaScriptHelper();
// @TODO This is going away soon
$facebookClient = $fb->getClient();

try {
    $accessToken = $jsHelper->getAccessToken($facebookClient);
} catch ( Facebook\Exceptions\FacebookResponseException $e ) {
    // When Graph returns an error
    header('Location: http://fb.shashwatmittal.com/index.php');
    //    echo 'Graph returned an error: ' . $e->getMessage();
} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
}

if (!isset($accessToken) ) {
    if ($helper->getError() ) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
$_SESSION[ 'accessToken' ] = $accessToken->getValue();

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('513040895560036'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();


if (!$accessToken->isLongLived() ) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch ( Facebook\Exceptions\FacebookSDKException $e ) {
        echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
        exit;
    }
}


// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
header('Location: http://fb.shashwatmittal.com/home.php');
exit;
