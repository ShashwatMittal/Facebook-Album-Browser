<?php
session_start();

//Include Facebook PHP SDK
require_once __DIR__ . '/Facebook/autoload.php';

//Include the App ID, App Secret and the Callback url.
require_once 'config.php';
require_once 'functions.php';

// Saving the accessToken from the Session into an Access Token variable for sending requests.
$accessToken = $_SESSION[ 'accessToken' ];
?>

<html>

	<head>
		<title>Facebook Album Browser</title>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Bootstrap CSS File-->
		<link href="lib/css/bootstrap.min.css" rel="stylesheet">


	</head>

	<body>

		<?php
		$albums = getAlbumData( $fb, $accessToken );
		echo "<pre>";
		var_dump($albums);
		echo "</pre>";
		?>	
	</body>

</html>

