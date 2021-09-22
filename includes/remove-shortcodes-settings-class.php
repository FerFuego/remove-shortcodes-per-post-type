<?php 

class Remove_Shortcode_Settings {

    public $settings = array();

    public function __construct() {
        add_action('admin_menu', array( $this, 'remove_shortcodes_admin_menu' ) );
        add_action('admin_init', array($this, 'settings_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        $this->settings = get_option('remove_shortcode_settings');
    }

    /*--------------------------------*/
    /* Add Admin Menu
    /*--------------------------------*/

    public function remove_shortcodes_admin_menu() {
        add_options_page( 'Remove Shortcodes', 'Remove Shortcodes', 'manage_options', 'remove-shortcodes', array( $this, 'options_page' ) );
    }
    
    public function settings_init() {
        register_setting( 'remove_shortcode_settings', 'remove_shortcode_settings' );
    }

    public function options_page() {
        ?>
        <div class="wrap">
            <div class="container">
                <div class="postbox-container">
                    <form action='options.php' method='post'>
                        <h1>Remove Shortcodes Settings</h1>
                        <h4>This plugin is responsible for Removing Shortcodes from Posts and Pages</h4>
                    </form>
                    <hr>
                    <div class="form-group">
                        <label for="post_type_clean">Select Post Type to clean</label><br>
                        <select id="post_type_clean" class="form-control" name="post_type">
                            <?php $post_types = get_post_types(array('public' => true), 'objects');
                            foreach ($post_types as $post_type) {
                                echo '<option value="'.$post_type->name.'">'.$post_type->label.'</option>';
                            }
                            ?>
                        </select>
                        <br><br>    
                    </div>
                    <a href="#" onclick="remove_shortcodes_run();" class="button btn button-primary">Run Cleaner Shortcodes</a>
                    <div id="response_import"></div>
                    <br>
                    <b>Summary</b>
                    <textarea class="form-control" rows="5" id="remove_shortcodes_field" name="log" style="width:100%; height:300px; background-color:#fff;" readonly><?php echo $this->read_log_file(); ?></textarea> 
                </div>
            </div>
        </div>
        <?php
    }

    /*--------------------------------*/
    /* Read Log File
    /*--------------------------------*/

    public function read_log_file() {
        $read = '';
        if ($myfile = @fopen(REMOVESC_IMPORTER_LOG_FILE, 'rt') ) {
            flock($myfile, LOCK_SH);
            $read = file_get_contents(REMOVESC_IMPORTER_LOG_FILE);
            fclose($myfile);
        }

        return $read;
    }

    /*--------------------------------*/
    /* Enqueue Scripts
    /*--------------------------------*/

    public function enqueue_scripts() {

        wp_enqueue_script('remove-shortcodes-script', plugin_dir_url( __FILE__ ) . '../assets/js/remove-shortcodes.js', array('jquery'), '1.0.0', true);
        wp_localize_script('remove_shortcodes_script', 'remove_shortcodes_ajax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('remove_shortcodes_nonce'),
        ));

        ?>
        <script type='text/javascript'>
        /* <![CDATA[ */
        var bms_vars = {"ajaxurl":"<?php echo bloginfo('url');?>\/wp-admin\/admin-ajax.php"};
        /* ]]> */
        </script>
    <?php }

}