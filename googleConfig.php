<?php

require_once __DIR__ . '/vendor/autoload.php';

// Google Config file which holds the Client config.
$client = new Google_Client();
$client->setAuthConfig( 'client_secret.json' ); // Client Credentials are stored in the CLient_secret.json file.
$client->addScope( 'https://picasaweb.google.com/data/' ); // Scope of the client. Permissions granted by the user to access its apps.
$client->addScope('https://www.googleapis.com/auth/plus.media.upload');
$client->setAccessType( 'offline' ); // Required for getting the refresh token from Google in response header.
$client->setIncludeGrantedScopes( true );
