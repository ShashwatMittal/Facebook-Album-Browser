<!DOCTYPE html>
<html>

    <title>Facebook Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS File hosted from CDNs.-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <body>
        <script>
	    // This is called with the results from from FB.getLoginStatus().
	    function statusChangeCallback( response ) {
		//console.log( 'statusChangeCallback' );
		//console.log( response );
		// The response object is returned with a status field that lets the
		// app know the current login status of the person.
		// Full docs on the response object can be found in the documentation
		// for FB.getLoginStatus().
		if ( response.status === 'connected' ) {
		    // Logged into your app and Facebook.
		    document.getElementById( 'status' ).innerHTML = 'Logged in. Redirecting to home page.';
		    getAccessToken();
		} else if ( response.status === 'not_authorized' ) {
		    // The person is logged into Facebook, but not your app.
		    document.getElementById( 'status' ).innerHTML = 'Please log ' +
			'into this app.';
		} else {
		    // The person is not logged into Facebook, so we're not sure if
		    // they are logged into this app or not.
		    document.getElementById( 'status' ).innerHTML = 'Please log ' +
			'into Facebook.';
		}
	    }

	    // This function is called when someone finishes with the Login
	    // Button.  See the onlogin handler attached to it in the sample
	    // code below.
	    function checkLoginState() {
		FB.getLoginStatus( function ( response ) {
		    statusChangeCallback( response );
		} );
	    }

	    window.fbAsyncInit = function () {
		FB.init( {
		    appId: '513040895560036',
		    cookie: true, // enable cookies to allow the server to access 
		    // the session
		    xfbml: true, // parse social plugins on this page
		    version: 'v2.8' // use graph api version 2.8
		} );

		// Now that we've initialized the JavaScript SDK, we call 
		// FB.getLoginStatus().  This function gets the state of the
		// person visiting this page and can return one of three states to
		// the callback you provide.  They can be:
		//
		// 1. Logged into your app ('connected')
		// 2. Logged into Facebook, but not your app ('not_authorized')
		// 3. Not logged into Facebook and can't tell if they are logged into
		//    your app or not.
		//
		// These three cases are handled in the callback function.

		FB.getLoginStatus( function ( response ) {
		    statusChangeCallback( response );
		} );

	    };

	    // Load the SDK asynchronously
	    ( function ( d, s, id ) {
		var js, fjs = d.getElementsByTagName( s )[0];
		if ( d.getElementById( id ) )
		    return;
		js = d.createElement( s );
		js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore( js, fjs );
	    }( document, 'script', 'facebook-jssdk' ) );

	    // Here we run a very simple test of the Graph API after login is
	    // successful.  See statusChangeCallback() for when this call is made.
	    function getAccessToken() {
		window.location.replace( 'callback.php' );
	    }
        </script>

        <!--
          Below we include the Login Button social plugin. This button uses
          the JavaScript SDK to present a graphical Login button that triggers
          the FB.login() function when clicked.
        -->

        <div class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Facebook Album Browser</a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="">

                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container container-fluid">
            <div class="col-md-offset-3 col-md-6">
                <div class="jumbotron text-center">
                    <h4 id="status">
                    </h4>
                    <fb:login-button size="xlarge" scope="public_profile,email,user_photos" onlogin="checkLoginState();">
                        Facebook
                    </fb:login-button>
                </div>
            </div>
        </div>
    </body>
</html>