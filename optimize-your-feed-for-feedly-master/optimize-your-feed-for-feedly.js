jQuery(document).ready( function($) {

    jQuery('.color-picker').wpColorPicker();

    var file_frame;

    jQuery( '.oyfff-media-select' ).on( 'click', function( event ) {

        var insert_into = jQuery( this ).data( 'insert' );

        event.preventDefault();

        file_frame = wp.media({
            title: jQuery( this ).data( 'title' ),
            button: {
                text: 'Select'
            },
            multiple: false
        });

        file_frame.on( 'select', function() {

            attachment = file_frame.state().get('selection').first().toJSON();
            jQuery( 'input[name="' + insert_into + '"]' ).val( attachment.url );

        });

        file_frame.open();

    });

} );