<?php 
/**
 * Plugin Name: WP Remove Shortcodes
 * Plugin URI: 
 * Description:
 * Version: 1.0
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('REMOVESC_GF_FIELD_VERSION', '1.0');
define('REMOVESC_PLUGIN_DIR', dirname( __FILE__ ) );
define('REMOVESC_IMPORTER_LOG_FILE', REMOVESC_PLUGIN_DIR .'/logs/log_'.date("j.n.Y", current_time( 'timestamp', 0 )).'.log' );
define('REMOVESC_IMPORTER_LOG_ERROR_FILE', REMOVESC_PLUGIN_DIR .'/logs/log_error_'.date("j.n.Y", current_time( 'timestamp', 0 )).'.log' );

if ( !class_exists( 'Remove_Shortcodes_Class' ) ) {
    require_once( REMOVESC_PLUGIN_DIR . '/includes/remove-shortcodes-class.php' );
}

/**
 * Load plugin
 */
$REMOVESC = new Remove_Shortcodes();
$REMOVESC->init();