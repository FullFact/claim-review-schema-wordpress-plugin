jQuery(document).ready( function() {
    jQuery('.allclaims-box').on( 'click', 'button.claim-more-fields', function( e ) {
        e.preventDefault();
        jQuery(this).parent().next(".claim-more-fields-box").toggle();
    });

    jQuery('.cr-add-claim-field').on( 'click', function(e) {
        e.preventDefault();
        var number = jQuery(this).data('target');
        var newmetabox = metabox.metabox.replace(/%%JS%%/g, number );
        number++;
        jQuery(this).data('target', number );
        jQuery('.allclaims-box').append(newmetabox);
    });

    jQuery('body').on('focus', ".crdatepicker", function () {
        jQuery(this).datepicker({
            dateFormat: "yy-mm-dd"
        });
    });

    jQuery('.allclaims-box').on('click', 'a.add-claim-appearance', function(e) {
        e.preventDefault();
        var arraykey = jQuery(this).data('arraykey');
        var html = '<tr><td style="width:75%;"><input class="widefat" type="text" name="claim[' + arraykey + '][appearance][url][]" value="" placeholder="" /></td><td style="width:25%;"><button class="button button-secondary cr-remove-row">Remove</button></td></tr>';
        jQuery(this).closest('.claimbox').find('table.claim-appearance > tbody').append(html);
    });

    jQuery('.allclaims-box').on('click', 'button.cr-remove-claim', function (e) {
        e.preventDefault();
        var number   = jQuery(this).data('remove-target' );
        var claimbox = jQuery(this).closest('.claimbox');
        var box      = claimbox.data('box');

        if ( number == box ) {
            claimbox.remove();
        }
    });

    jQuery('.allclaims-box').on('click', 'button.cr-remove-row', function (e) {
        e.preventDefault();
        var claimrow = jQuery(this).closest('tr');
        claimrow.remove();
    });
});