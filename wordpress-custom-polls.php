<?php
/**
 * Plugin Name: WordPress Custom Polls
 * Plugin URI: http://robertochoa.com.ve
 * Description: Plugin for creating custom polls and info.
 * Version: 1.0.0
 * Author: Robert Ochoa
 * Author URI: http://robertochoa.com.ve
 * License: GPL2+
 * Text Domain: wordpress-custom-polls
 * Domain Path: /languages/
 *
 */


// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

/* --------------------------------------------------------------
    AGREGAR SOPORTE PARA CPT
    -------------------------------------------------------------- */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcpolls-cpt.php';

/* --------------------------------------------------------------
    AGREGAR FUNCIONES PRINCIPALES
    -------------------------------------------------------------- */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcpolls-functions.php';

/* --------------------------------------------------------------
    AGREGAR FUNCION DEL SHORTCODE
    -------------------------------------------------------------- */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcpolls-shortcode.php';

/* --------------------------------------------------------------
    ACTIVATE PLUGIN PROCESS
    -------------------------------------------------------------- */

register_activation_hook(__FILE__, 'wpcpolls_rewrite_flush');
register_activation_hook(__FILE__, 'wpcpolls_database');
