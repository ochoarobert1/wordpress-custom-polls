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
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  ip text NOT NULL,
  selection tinytext NOT NULL,
  PRIMARY KEY  (id) ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}


/* --------------------------------------------------------------
ADD FLUSH REWRITE FOR PERMALINKS
-------------------------------------------------------------- */

function wpcpolls_rewrite_flush() {
    wpcpolls_custom_post_type();
    flush_rewrite_rules();
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

add_action('wp_enqueue_scripts', 'wpcpolls_frontend_styles_scripts');
