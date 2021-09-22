<?php

class Remove_Shortcodes {

    public function __construct() {
        require_once( REMOVESC_PLUGIN_DIR . '/includes/remove-shortcodes-errors-trait.php' );
        require_once( REMOVESC_PLUGIN_DIR . '/includes/remove-shortcodes-settings-class.php' );
        require_once( REMOVESC_PLUGIN_DIR . '/includes/remove-shortcodes-process-class.php' );
        add_action('wp_ajax_nopriv_run_remover', array( $this, 'remove_shortcodes_hook' ) );
        add_action('wp_ajax_run_remover', array( $this, 'remove_shortcodes_hook' ) );
    }
    
    public function init() {    
        new Remove_Shortcode_Settings;
    }

    /**
     * Activation Manual
     * @param $action
     */
    public function remove_shortcodes_hook() {
        new Remove_Shortcodes_Process($_POST['post_type_clean']);
    }

} 