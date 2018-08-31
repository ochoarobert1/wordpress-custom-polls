<?php

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

/* --------------------------------------------------------------
ADD CUSTOM POST TYPE FOR POLLS
-------------------------------------------------------------- */

function wpcpolls_custom_post_type() {
    $labels = array(
        'name'                  => _x( 'Polls', 'Post Type General Name', 'wordpress-custom-polls' ),
        'singular_name'         => _x( 'Poll', 'Post Type Singular Name', 'wordpress-custom-polls' ),
        'menu_name'             => __( 'Polls', 'wordpress-custom-polls' ),
        'name_admin_bar'        => __( 'Polls', 'wordpress-custom-polls' ),
        'archives'              => __( 'Poll Archives', 'wordpress-custom-polls' ),
        'attributes'            => __( 'Poll Attributes', 'wordpress-custom-polls' ),
        'parent_item_colon'     => __( 'Parent Poll:', 'wordpress-custom-polls' ),
        'all_items'             => __( 'All Polls', 'wordpress-custom-polls' ),
        'add_new_item'          => __( 'Add New Poll', 'wordpress-custom-polls' ),
        'add_new'               => __( 'Add New', 'wordpress-custom-polls' ),
        'new_item'              => __( 'New Poll', 'wordpress-custom-polls' ),
        'edit_item'             => __( 'Edit Poll', 'wordpress-custom-polls' ),
        'update_item'           => __( 'Update Poll', 'wordpress-custom-polls' ),
        'view_item'             => __( 'View Poll', 'wordpress-custom-polls' ),
        'view_items'            => __( 'View Polls', 'wordpress-custom-polls' ),
        'search_items'          => __( 'Search Poll', 'wordpress-custom-polls' ),
        'not_found'             => __( 'Not found', 'wordpress-custom-polls' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'wordpress-custom-polls' ),
        'featured_image'        => __( 'Featured Image', 'wordpress-custom-polls' ),
        'set_featured_image'    => __( 'Set featured image', 'wordpress-custom-polls' ),
        'remove_featured_image' => __( 'Remove featured image', 'wordpress-custom-polls' ),
        'use_featured_image'    => __( 'Use as featured image', 'wordpress-custom-polls' ),
        'insert_into_item'      => __( 'Insert into Poll', 'wordpress-custom-polls' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Poll', 'wordpress-custom-polls' ),
        'items_list'            => __( 'Polls list', 'wordpress-custom-polls' ),
        'items_list_navigation' => __( 'Polls list navigation', 'wordpress-custom-polls' ),
        'filter_items_list'     => __( 'Filter Polls list', 'wordpress-custom-polls' ),
    );
    $args = array(
        'label'                 => __( 'Poll', 'wordpress-custom-polls' ),
        'description'           => __( 'Custom Polls', 'wordpress-custom-polls' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => false,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-list-view',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'rewrite'               => array('slug' => 'wpcpolls_polls'),
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type( 'wpcpolls_polls', $args );
}

add_action('init', 'wpcpolls_custom_post_type');

/* --------------------------------------------------------------
ADD CUSTOM METABOXES FOR OPTIONS
-------------------------------------------------------------- */

function wpcpolls_add_metaboxes() {
    add_meta_box('wpcpolls_metabox', __('Poll Options', 'wordpress-custom-polls'), 'wpcpolls_metaboxes_handler', 'wpcpolls_polls', 'normal', 'high', NULL);
}
add_action('add_meta_boxes', 'wpcpolls_add_metaboxes');

/* --------------------------------------------------------------
ADD META BOX INFO AND DATA
-------------------------------------------------------------- */
function wpcpolls_metaboxes_handler($post) {
    /* RECREATE FORM IN META BOX */
    wp_nonce_field(basename(__FILE__), 'wpcpolls_nonce');
?>
<table class="form-table">
    <tr>
        <th class="row-title">
            <?php _e('Add options for poll', 'wordpress-custom-polls'); ?>
        </th>
    </tr>
    <tr>
        <th class="row-title">
            <label for="option_1">a)</label>
        </th>
        <td>
            <input value="<?php echo esc_attr(get_post_meta($post->ID, 'wpcpolls_option_1', true)); ?>" type="text" id="option_1" name="wpcpolls_option_1" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th class="row-title">
            <label for="option_2">b)</label>
        </th>
        <td>
            <input value="<?php echo esc_attr(get_post_meta($post->ID, 'wpcpolls_option_2', true)); ?>" type="text" id="option_2" name="wpcpolls_option_2" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th class="row-title">
            <label for="option_3">c)</label>
        </th>
        <td>
            <input value="<?php echo esc_attr(get_post_meta($post->ID, 'wpcpolls_option_3', true)); ?>" type="text" id="option_3" name="wpcpolls_option_3" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th class="row-title">
            <label for="option_4">d)</label>
        </th>
        <td>
            <input value="<?php echo esc_attr(get_post_meta($post->ID, 'wpcpolls_option_4', true)); ?>" type="text" id="option_4" name="wpcpolls_option_4" class="regular-text" />
        </td>
    </tr>
</table>

<?php }

/* SAVE DATA FROM META BOX */
function wpcpolls_save_metaboxes($post_id, $post, $update) {
    /* SECURE METABOX */
    if (!isset($_POST['wpcpolls_nonce']) || !wp_verify_nonce($_POST['wpcpolls_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    /* DOING METABOX SAVING OPERATION */
    $option_1 = $option_2 = $option_3 = $option_4 = '';
    /* OPTION 1 */
    if(isset($_POST['wpcpolls_option_1'])) {
        $option_1 = sanitize_post($_POST['wpcpolls_option_1']);
    }
    update_post_meta($post_id, 'wpcpolls_option_1', $option_1);

    /* OPTION 2 */
    if(isset($_POST['wpcpolls_option_2'])) {
        $option_2 = sanitize_post($_POST['wpcpolls_option_2']);
    }
    update_post_meta($post_id, 'wpcpolls_option_2', $option_2);

    /* OPTION 3 */
    if(isset($_POST['wpcpolls_option_3'])) {
        $option_3 = sanitize_post($_POST['wpcpolls_option_3']);
    }
    update_post_meta($post_id, 'wpcpolls_option_3', $option_3);

    /* OPTION 4 */
    if(isset($_POST['wpcpolls_option_4'])) {
        $option_4 = sanitize_post($_POST['wpcpolls_option_4']);
    }
    update_post_meta($post_id, 'wpcpolls_option_4', $option_4);
}

add_action('save_post', 'wpcpolls_save_metaboxes', 10, 3);
