<?php
session_start();

//Include Facebook PHP SDK
require_once __DIR__ . '/Facebook/autoload.php';

//Include the App ID, App Secret and the Callback url. 
require_once 'config.php';
// Functions file stores all the functions required for the project.
require_once 'functions.php';

// Saving the accessToken from the Session into an Access Token variable for sending requests.
$accessToken = $_SESSION[ 'accessToken' ];
// Getting the Data from the Facebook.
$albums		 = getAlbumData( $fb, $accessToken );
?><!doctype html>
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
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="glyphicon glyphicon-menu-hamburger" aria-label="true"></span>
					</button>
					<a class="navbar-brand" href="#">Facebook Album Browser</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#">Hello! <?php echo $albums[ 'name' ]; ?></a></li>
						<button type="button" class="btn btn-default navbar-btn selectedDonwloadButton">Download Selected Albums</button>
					</ul>

				</div>
			</div>
		</div>

		<div class="container container-fluid">
			<?php
			foreach ( $albums[ 'albums' ][ 'data' ] as $value ) {
				$name	 = $value[ 'name' ];
				$id		 = $value[ 'id' ];
				$url	 = $value[ 'picture' ][ 'data' ][ 'url' ];
				?>
				<div class = 'col-md-4 col-sm-6 col-xs-12'>
					<a class="colorbox" href="#">
						<div class = "col-md-12 thumb" style="background-image:url(<?php echo $url; ?>);">
							<div class="thumbGradient">
								<div class = " col-md-12 "><h3><?php echo $name; ?></h3></div>
							</div>							
						</div>
					</a>
					<div class = 'col-md-12 thumbBottom'>
						<div class="col-md-2 col-md-offset-1 col-sm-offset-1 col-xs-offset-2 col-sm-2 col-xs-2">
							<input class="selector" type="checkbox" data-albumID = "<?php echo $id ?>" data-albumName = "<?php echo $name; ?>">
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<button class="single_dl_button btn btn-default" data-albumID = "<?php echo $id; ?>" data-albumName ="<?php echo $name; ?>" >Download This Album</button>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			<div class="modal fade" id="downloadModal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Files ready to be downloaded.</h4>
						</div>
						<div class="modal-body">
							<p>Click on the link below to download the file.</p>
							<a href='/albums.zip' download="albums.zip">Zipped Files.</a>
						</div>
						<div class="modal-footer">
							<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>


			<!-- Jquery -->
			<script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
			<!-- Latest compiled and minified JavaScript -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
			<script src="lib/js/main.js"></script>
	</body>

</html>

