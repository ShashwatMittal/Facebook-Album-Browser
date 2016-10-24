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

		<!-- Bootstrap CSS File hosted from CDNs.-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- Custom CSS file to implement custom CSS throughout the project.-->
		<link rel="stylesheet" href="/lib/css/main.css">

	</head>

	<body>
		<div class="container container-fluid">
			<div class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="#">Facebook Album Browser</a>
					</div>
				</div>
			</div>
			<form action = 'download.php' method = 'post'>
				<?php
				$albums		 = getAlbumData( $fb, $accessToken );
				foreach ( $albums[ 'albums' ][ 'data' ] as $value ) {
					$name	 = $value[ 'name' ];
					$id		 = $value[ 'id' ];
					$url	 = $value[ 'picture' ][ 'data' ][ 'url' ];
					?>
					<div class = 'col-md-4 col-sm-6'>
						<a class="colorbox" href="#">
							<div class = "col-md-12 thumb" style="background-image:url(<?php echo $url; ?>);">
								<div class="thumbGradient">
									<div class = "thumbText col-md-12 "><?php echo $name; ?></div>
								</div>							
								
							</div>
						</a>
						<div class = 'col-md-12 thumbBottom'>
							<div class="col-md-2 col-md-offset-1">
								<input class="selector" type="checkbox" placeholder="Select" data-albumID = "<?php echo $id ?>" data-albumName = "<?php echo $name; ?>">
							</div>
							<div class="col-md-6">
								<a class="single_dl_button btn btn-default" data-albumID = "<?php echo $id; ?>" data-albumName ="<?php echo $name; ?>" >Download This Album</a>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<button type ="submit" class="btn btn-default dl_button" href="">Download Selected Photos</button>
			</form>
		</div>
		<!-- Jquery -->
		<script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="lib/js/main.js"></script>
	</body>

</html>

