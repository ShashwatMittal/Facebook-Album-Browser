jQuery( document ).ready( function () {
    jQuery( '.single_dl_button' ).click( function ( e ) {
        e.preventDefault();
//        var albumID = jQuery( this ).attr('data-albumID');
//        var albumName = jQuery( this ).attr('data-albumName');
//        jQuery.get( 'download.php' , { albumID: albumID, albumName: albumName } , function ( data ) {
//            $.get( 'functions.php', { location: 'images' }, function ( data ) {
//                console.log( data );
//            } );
//            console.log(data);
//        } );
        executeQuery(this);
    } );

    jQuery( '.dl_button' ).click( function ( e ) {
        e.preventDefault();
        jQuery( '.checkbox:checked' ).each( function () {
            var albumID = jQuery( this ).attr('data-albumID');
            var albumName = jQuery( this ).attr('data-albumName');
            jQuery.get( 'download.php', { albumID : albumID, albumName : albumName }, function (data) {
                console.log(data)
            } );

        } );
    } );
} );

function executeQuery(element){
        var albumID = jQuery( element ).attr('data-albumID');
        var albumName = jQuery( element ).attr('data-albumName');
        jQuery.get( 'download.php' , { albumID: albumID, albumName: albumName } , function ( data ) {
            $.get( 'functions.php', { location: 'images' }, function ( data ) {
                console.log( data );
            } );
            console.log(data);
        } );    
}