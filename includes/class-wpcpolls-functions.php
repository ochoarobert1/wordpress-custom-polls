<?php

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

/* --------------------------------------------------------------
CREATE DATABASE
-------------------------------------------------------------- */

function wpcpolls_database () {
    global $wpdb;
    $table_name = $wpdb->prefix . "wpcpolls_meta";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  post_id int NOT NULL,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  ip text NOT NULL,
  selection tinytext NOT NULL,
  PRIMARY KEY  (id) ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'wpcpolls_version', '1.0.0', '', 'no' );
}


/* --------------------------------------------------------------
ADD FLUSH REWRITE FOR PERMALINKS
-------------------------------------------------------------- */

function wpcpolls_rewrite_flush() {
    wpcpolls_custom_post_type();
    flush_rewrite_rules();
}

/* --------------------------------------------------------------
ADD DROP TABLE FOR DEACTIVATION
-------------------------------------------------------------- */
function wpcpolls_remove_database() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'wpcpolls_meta';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
    delete_option('wpcpolls_version');
}

/* --------------------------------------------------------------
ADD PLUGIN PAGE IN ADMIN
-------------------------------------------------------------- */

function wpcpolls_admin_secion_handler () {
    add_menu_page( __('Custom Polls', 'wordpress-custom-polls'), __('Custom Polls', 'wordpress-custom-polls'), 'edit_posts', 'wordpress-custom-polls-admin', 'wpcpolls_admin_dashboard_function', 'dashicons-list-view' , 120 );
    add_submenu_page( 'wordpress-custom-polls-admin', __( 'Polls', 'wordpress-custom-polls' ), __( 'Polls', 'wordpress-custom-polls' ), 'manage_options', 'edit.php?post_type=wpcpolls_polls');
}

add_action('admin_menu', 'wpcpolls_admin_secion_handler');

function wpcpolls_admin_dashboard_function () {
    echo 'hola panas';
}

/* --------------------------------------------------------------
ADD PLUGIN PAGE IN ADMIN
-------------------------------------------------------------- */

function wpcpolls_frontend_styles_scripts() {
    $version_remove = '1.0.0';

    wp_enqueue_style('wpcpolls-css', plugins_url( '/wordpress-custom-polls/css/wpcpolls-frontend.css', '__FILE__'), false, $version_remove, 'all');

    wp_enqueue_script('wpcpolls-js', plugins_url( '/wordpress-custom-polls/js/wpcpolls-frontend.js', '__FILE__'), array('jquery'), $version_remove, true);

    wp_localize_script('wpcpolls-js', 'admin_url', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}

add_action('init', 'wpcpolls_frontend_styles_scripts');

/* --------------------------------------------------------------
SET VALUES
-------------------------------------------------------------- */

function wpcpolls_set_values($poll_id, $poll_option) {
    global $wpdb;

    /* GET TOTAL ON POLL */
    $query_total = $wpdb->get_results(
        "
    SELECT COUNT(id) FROM {$wpdb->prefix}wpcpolls_meta
    WHERE post_id = $poll_id", ARRAY_A);

    foreach($query_total as $query_data) {
        $total = $query_data['COUNT(id)'];
    }

    /* GET TOTAL ON OPTION */
    $query_array = $wpdb->get_results(
        "
    SELECT COUNT(id) FROM {$wpdb->prefix}wpcpolls_meta
    WHERE post_id = $poll_id AND selection = $poll_option", ARRAY_A);

    foreach($query_array as $query_data) {
        $result = $query_data['COUNT(id)'];
    }

    /* GET THE PERCENTAGE */
    if ($total != 0) {
        $result = ($result * 100) / $total;
    } else {
        $result = 0;
    }

    return round($result, 2);
}

/* --------------------------------------------------------------
SET VALUES ON CLICK - AJAX CALLER
-------------------------------------------------------------- */

function wpcpolls_update_values() {
    $poll_id = $_POST['id_post'];
    $poll_option = $_POST['id_poll'];
    global $wpdb;

    /* GET TOTAL ON POLL */
    $query_total = $wpdb->get_results(
        "
    SELECT COUNT(id) FROM {$wpdb->prefix}wpcpolls_meta
    WHERE post_id = $poll_id", ARRAY_A);

    foreach($query_total as $query_data) {
        $total = $query_data['COUNT(id)'];
    }

    for ($i = 1; $i <= 4; $i++) {
        /* GET TOTAL ON OPTION */
        $query_array = $wpdb->get_results(
            "
    SELECT COUNT(id) FROM {$wpdb->prefix}wpcpolls_meta
    WHERE post_id = $poll_id AND selection = $i", ARRAY_A);

        foreach($query_array as $query_data) {
            $result[$i] = $query_data['COUNT(id)'];
        }

        if ($poll_option == $i) {
            $result[$i] = $result[$i] + 1;
            $total = $total + 1;
        }
    }

    /* GET THE PERCENTAGE */
    for ($i = 1; $i <= 4; $i++) {
        $result[$i] = ($result[$i] * 100) / $total;
    }

    echo json_encode($result, JSON_PRETTY_PRINT);

    wp_die();
}

add_action('wp_ajax_nopriv_wpcpolls_update_values', 'wpcpolls_update_values');
add_action('wp_ajax_wpcpolls_update_values', 'wpcpolls_update_values');

/* --------------------------------------------------------------
ADD AJAX FUNCTIONS
-------------------------------------------------------------- */

function wpcpolls_insert_vote() {
    $poll_id = $_POST['id_post'];
    $poll_option = $_POST['id_poll'];
    $now = new DateTime();
    $ip = $_SERVER['REMOTE_ADDR'];
    global $wpdb;

    /* INSERT VOTE ON POLL */
    $wpdb->insert(
        $wpdb->prefix . 'wpcpolls_meta',
        array(
            'post_id'   => $poll_id,
            'time'      => $now->format('Y-m-d H:i:s'),
            'ip'        => $ip,
            'selection' =>  $poll_option
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s'
        )
    );

    wp_die();
}

add_action('wp_ajax_nopriv_wpcpolls_insert_vote', 'wpcpolls_insert_vote');
add_action('wp_ajax_wpcpolls_insert_vote', 'wpcpolls_insert_vote');
