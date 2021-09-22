
/**
 * 
 * @returns Returns manual import
 */
function remove_shortcodes_run () {

    event.preventDefault();

    console.log(jQuery('#post_type_clean').val() );

    var formData = new FormData();
        formData.append('action',  'run_remover' );
        formData.append('post_type_clean', jQuery('#post_type_clean').val() );

    jQuery.ajax({
        cache: false,
        url: bms_vars.ajaxurl,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {
            jQuery( '#response_import' ).html( '<p class="text-info">Cleaning...</p>' );
        },
        success: function ( response ) {
            jQuery( '#response_import' ).html( '<p class="text-success">cleaning completed!</p>' );
            jQuery("#remove_shortcodes_field").val( response.data );
        }
    });
    return false;
}