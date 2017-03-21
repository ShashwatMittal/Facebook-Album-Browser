<?php
/**
 * HTML Mark up file for FB Album Browser. Controls the layout of the Website.
 * 
 * PHP Version 7
 * 
 * Fetches the Album Data from Facebook Graph API.
 * Displays the Fetched data in Thumbnails.
 * 
 * @category Markup
 * @package  FBAlbumBrowser
 * @author   Shashwat Mittal <shashwat.mittal5@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  1.0.0
 * @link     https://fb.shashwatmittal.com/home.php
 */
session_start();

//Include Facebook PHP SDK
require_once __DIR__ . '/vendor/autoload.php';

//Include the App ID, App Secret and the Callback url. 
require_once 'config.php';
// Functions file stores all the functions required for the project.
require_once 'functions.php';

// Saving the accessToken from the Session into an Access Token variable for sending requests.
$accessToken = $_SESSION[ 'accessToken' ];

// Getting the Data from the Facebook.
$albums      = getAlbumData($fb, $accessToken);
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
        <link rel="stylesheet" href="/lib/css/colorbox/colorbox.css">

    </head>

    <body>
        <div class="navbar navbar-inverse navbar-fixed-top"><!-- Navigation bar-->
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="glyphicon glyphicon-menu-hamburger" aria-label="true"></span>
                    </button>
                    <a class="navbar-brand" href="#">Hello! <?php echo $albums[ 'name' ]; ?></a><!--Greeting user in Navbar-->
                                                                                             
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav navbar-right">
                        <!--Download and Move button which downloads or moves all the selected Albums by the user. -->
                        <!--<li><button type="button" class="btn btn-default navbar-btn selected-move-button btn-opt">Move</button></li>-->
<!--                        <li><button type="button" class="btn btn-default navbar-btn selected-donwload-button btn-opt">Download Selected Albums</button></li>
                        <li><button type="button" class="btn btn-default navbar-btn btn-opt download-all-Button">Download All</button></li>-->
                        <li><button type="button" class="btn btn-default navbar-btn logout-button">Logout</button></li>
                    </ul>
                </div>
            </div>
        </div>



        <div class="container">
            <div class="download-options">
                <label for="select-all"><input id="select-all" type="checkbox" value="Select All Checkbox"> Select All</label>
                <button type="button" class="btn btn-default selected-donwload-button btn-opt">Download Selected Albums</button>
                <button type="button" class="btn btn-default btn-opt download-all-button btn-opt">Download All</button>
            </div>
            
            <div class="row">
                <?php
                foreach ( $albums[ 'albums' ][ 'data' ] as $value ) { 
                    $name = $value[ 'name' ]; // Album Name
                    $id   = $value[ 'id' ]; // Album ID
                    $url  = $value[ 'picture' ][ 'data' ][ 'url' ]; // Cover Photo For Album
                    ?>	

                    <div class="col-md-4">
                        <a href="#" class="slider" data-ID="<?php echo $id ?>" title="<?php echo $name; ?>">
                            <div class="thumb" style="background-image:url(<?php echo $url; ?>);">
                                <div class="thumb-gradient">
                                    <div class="col-md-12"><h3><?php echo $name; ?></h3></div>
                                </div>
                            </div>
                        </a>
                        <div class="thumb-bottom">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-left">
                                <input class="selector" type="checkbox" data-albumID = "<?php echo $id ?>" data-albumName = "<?php echo $name; ?>">
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 text-right no-padding">
                                <button class="single-dl-button btn btn-default btn-opt" data-albumID = "<?php echo $id; ?>" data-albumName ="<?php echo $name; ?>" >Download This Album</button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="modal fade" id="downloadModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Files ready to be downloaded.</h4>
                        </div>
                        <div class="modal-body">
                            <p>Click on the link below to download the file.</p>
                            <a href="/albums-download.php" download="albums.zip">Zipped Files.</a>
                        </div>
                        <div class="modal-footer">
                            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="colorbox"></div>
            <div class="loader">
                <div class="loader-wrap-inner">
                    <img src="/lib/images/ring-alt.svg" alt="loading..."/>
                    <p>Hold on!<br/>While we fetch your photos from Facebook.</p> 
                </div>
        </div>

        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="lib/js/main.js"></script>
        <script src="lib/js/colorbox/jquery.colorbox-min.js"></script>
    </body>

</html>

