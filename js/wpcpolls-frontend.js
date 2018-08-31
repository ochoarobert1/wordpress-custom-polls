var id_handler = '';
function wpcpolls_select(id) {
    jQuery('.wpcpolls-list li').removeClass('selected');
    jQuery('#item_wpcpolls_option_' + id).addClass('selected');
    jQuery.ajax({
        url : admin_url.ajax_url,
        type : 'post',
        data : {
            id_post : jQuery('.wpcpolls-container').attr('id'),
            id_poll : id,
            action : 'wpcpolls_select_option'
        },
        success : function (response) {
            console.log(response);
        }
    });
}
