var i = 1,
    x = 0;

function wpcpolls_select(id) {
    "use strict";
    jQuery('.wpcpolls-list li').removeClass('selected');
    jQuery('#item_wpcpolls_option_' + id).addClass('selected');
    jQuery.ajax({
        url: admin_url.ajax_url,
        type: 'post',
        data: {
            id_post: jQuery('.wpcpolls-container').attr('id'),
            id_poll: id,
            action: 'wpcpolls_update_values'
        },
        beforeSend: function () {
            for (i = 1; i <= 4; i++) {
                jQuery('#percentage_' + i).html('<div class="lds-dual-ring"></div>');
            }
        },
        success: function (response) {
            var data = JSON.parse(response);
            for (i = 1; i <= 4; i++) {
                x = Math.round(data[i]);
                jQuery('#wpcpolls_option_' + i + '_value').val(x);
                jQuery('#progress_bar_' + i).css('width', x + '%');
                jQuery('#percentage_' + i).html('<span>' + x + '%</span>');
            }
        }
    });
}

jQuery('.wpcpolls-button').on('click', function (e) {
    "use strict";
    e.preventDefault();
    var row = jQuery('input[name=wpcpolls_option]:checked', '#wpcpolls_form').val();
    var result = row.split("_");
    jQuery.ajax({
        url: admin_url.ajax_url,
        type: 'post',
        data: {
            id_post: jQuery('.wpcpolls-container').attr('id'),
            id_poll: result[2],
            action: 'wpcpolls_insert_vote'
        },
        beforeSend: function () {
            jQuery('.wpcpolls-result').html('<div class="lds-dual-ring"></div>');
        },
        success: function (response) {
            jQuery('.wpcpolls-result').html('<div class="sucess"><h2>Su voto ha sido procesado</h2></div>');
        }
    });

});
