jQuery( document ).ready( function () {

// Triggers the download of photos for the album whose download button is clicked.
    jQuery( '.single_dl_button' ).click( function ( e ) {
        e.preventDefault();
        var sentinel = jQuery( this ).attr( 'data-albumName' );
        downloadPhotos( this, sentinel );
    } );
// Triggers the download of all the albums one by one selected by the user.
    jQuery( '.selectedDonwloadButton' ).click( function ( e ) {
        e.preventDefault();
        var sentinel = jQuery( '.selector:checked' ).last().attr( 'data-albumName' );
        console.log( sentinel );
        jQuery( '.selector:checked' ).each( function () {
            downloadPhotos( this, sentinel );
        } );
    } );
} );

// This function downloads the photos of the all the albums passed to it. And then zips them into a Single zip file.
function downloadPhotos( element, name ) {
    var albumID = jQuery( element ).attr( 'data-albumID' );
    var albumName = jQuery( element ).attr( 'data-albumName' );
    jQuery.get( 'download.php', { albumID: albumID, albumName: albumName }, function ( data ) {
        console.log(data, name);
        if ( data === name ) {
            console.log('entered');
            createZip();
        }
    } );
}

// Creates the zip file of the Folder passed to it.
function createZip() {
    console.log( 'zip started' );
    jQuery.get( 'download.php', { zip: 'images' }, function ( data ) {
        console.log( 'zip created.' );
        jQuery.get('download.php', { delete:'images'}, function(data){
           console.log(data); 
        });
    } );

}