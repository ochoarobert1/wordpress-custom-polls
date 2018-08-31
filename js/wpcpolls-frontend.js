var id_handler = '';

jQuery('.wpcpolls-list li').on('click', function() {
    jQuery(this).addClass('selected');
    id_handler = this.id.split('_');

    jQuery.ajax({
        url : admin_ajax.ajax_url,
        type : 'post',
        data : {
            id_post : id_post,
            action : 'ajax_posts'
        },
        beforeSend : function () {
            jQuery('.post-container-ajax').append('<div id="loader"><span class="dot dot_1"></span> <span class="dot dot_2"></span> <span class="dot dot_3"></span> <span class="dot dot_4"></span></div>');
        },
        success : function (response) {
            jQuery('#loader').remove();
            jQuery('.post-container-ajax').append(response);
        }
    });
});
