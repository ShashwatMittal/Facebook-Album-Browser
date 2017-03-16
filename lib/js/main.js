/**
 * 
 * @param {type} param
 */
jQuery( document ).ready( function () {

    /**
     * Initializes the FB object to make calls to Facebook server for authorization.
     */
    window.fbAsyncInit = function () {
	FB.init( {
	    appId: '513040895560036',
	    cookie: true, // enable cookies to allow the server to access 
	    // the session
	    xfbml: true, // parse social plugins on this page
	    version: 'v2.8' // use graph api version 2.8
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

    /**
     * Triggers the download of photos for the album whose download button is clicked.
     */
    jQuery( '.single-dl-button' ).click( function ( e ) {
	e.preventDefault();
	console.log( 'Button Pressed.' );
	var albums = [ ];
	var name = jQuery( this ).attr( 'data-albumname' );
	var id = jQuery( this ).attr( 'data-albumid' );
	albums.push( {
	    'id': id,
	    'name': name
	} );
	downloadPhotos( albums );
    } );

    /**
     * Triggers the download of all the albums one by one selected by the user.
     */
    jQuery( '.selected-donwload-button' ).click( function ( e ) {
	e.preventDefault();
	var selected_albums = jQuery( '.selector:checked' );
	var albums = prepareAlbumArray( selected_albums );
	if ( albums.length > 0 ) {
	    //Send Ajax request
	    downloadPhotos( albums );
	}
    } );

    /**
     * Triggers the download of all the Albums.
     */
    jQuery( '.download-all-button' ).click( function ( e ) {
	e.preventDefault();
	var selected_albums = jQuery( '.selector' );
	var albums = prepareAlbumArray( selected_albums );
	if ( albums.length > 0 ) {
	    //Send Ajax request
	    downloadPhotos( albums );
	}

    } );

    /**
     * Presents the download link in a modal when the zip file is ready.
     */
    jQuery( '#downloadModal' ).on( 'hidden.bs.modal', function () {
	jQuery.get( 'download.php', { deleteZip: 'albums.zip' }, function ( data ) {
	    console.log( data );
	} );
    } );

    /**
     * Displays the Colorbox Slider when clicked on a thumbnail.
     */
    jQuery( 'a.slider' ).click( function ( e ) {
	e.preventDefault();
	jQuery( 'div.loader' ).css( { "visibility": "visible" } );
	var id = jQuery( this ).attr( 'data-ID' );
	jQuery.get( 'download.php', { ID: id }, function ( data ) {
	    var parsedData = jQuery.parseJSON( data );
	    jQuery( 'div.colorbox' ).html( "" );
	    jQuery( parsedData ).each( function ( i, val ) {
		jQuery( 'div.colorbox' ).append( '<a class="gallery" href="' + val + '"></a>' );
	    } );
	    jQuery( 'a.gallery' ).colorbox( { open: true, width: '100%', height: '100%', slideshow: true, rel: 'gal', onOpen: function () {
		    jQuery( 'div.loader' ).css( { "visibility": "hidden" } );
		} } );
	} );
    } );

    /**
     * Logs out the user when the user clicks on the Logout button.
     */
    jQuery( '.logout-button' ).click( function ( e ) {
	e.preventDefault();
	FB.logout( function ( response ) {
	    window.location.replace( 'logout.php' );
	    console.log( response );

	} );
    } );

    /**
     * Selects or deselects all the checkboxes.
     */
    jQuery( '#select-all' ).click( function ( e ) {
	if ( this.checked ) {
	    jQuery( '.selector' ).each( function () {
		this.checked = true;
	    } );
	} else {
	    jQuery( '.selector' ).each( function () {
		this.checked = false;
	    } );
	}
    } );


} );

/**
 * This function downloads the photos of the all the albums passed to it. And then zips them into a Single zip file.
 * 
 * @param {Array} albums
 */
function downloadPhotos( albums ) {
    $( '.btn' ).prop( 'disabled', true );
    $( 'div.loader' ).css( { 'visibility': 'visible' } );

    jQuery.get( 'download.php', { albums: albums }, function ( data ) {
	console.log( data, name );
	if ( data === name ) {
	    console.log( 'entered' );
	    createZip();
	}
    } );
}

/**
 * Creates the zip file of the Folder passed to it.
 */
function createZip() {
    console.log( 'zip started' );
    jQuery.get( 'download.php', { deleteZip: 'albums.zip' }, function ( data ) {
	console.log( data );
    } );
    jQuery.get( 'download.php', { zip: 'images' }, function ( data ) {
	console.log( 'zip created.' );
	jQuery.get( 'download.php', { deleteImages: 'images' }, function ( data ) {
	    console.log( data );
	    $( '.btn' ).each( function () {
		jQuery( 'div.loader' ).css( { "visibility": "hidden" } );
		$( this ).prop( 'disabled', false );
	    } );
	    $( '#downloadModal' ).modal();
	} );
    } );
}

/**
 * Prepares an array with the Name and ID of the Albums to be downloaded.
 * 
 * @param {Array} selected_albums The albums selected by the user to be downloaded.
 * @returns {Array|prepareAlbumArray.albums} Array with Album ID and Name.
 */
function prepareAlbumArray( selected_albums ) {

    var albums = [ ];
    if ( 'undefined' !== typeof ( selected_albums ) ) {
	selected_albums.each( function () {
	    var id = jQuery( this ).attr( 'data-albumid' );
	    var name = jQuery( this ).attr( 'data-albumname' );
	    albums.push( {
		'id': id,
		'name': name
	    } );
	} );
    }
    return albums;

}