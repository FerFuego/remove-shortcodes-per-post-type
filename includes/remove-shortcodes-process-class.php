<?php

class Remove_Shortcodes_Process extends Remove_Shortcode_Settings {

    use Errors;

    private $items_posts = array();
    private $count_posts = 0;
    private $count_updated = 0;
    private $count_error = array();
    public $log_error = array();

    public function __construct($post_type_clean) {
        // TO DO: set a CPT to run process
        // TO DO: make a resume process
        $this->settings = get_option('remove_shortcode_settings');
        $this->anubis_importer_process_init($post_type_clean);
    }

    /**
    * Get all posts with shortcodes
    */
    public function anubis_importer_process_init($post_type_clean) {

        global $wpdb;
        $this->items_posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = '$post_type_clean' AND post_status = 'publish'", OBJECT);
        
        if ( !empty($this->items_posts) ):
            $this->count_posts = @count($this->items_posts);
            $this->remove_shortcodes_process_posts();
        endif;

        wp_send_json_success( $this->log_resume() );
    }

    /**
    * Process Products
    */
    public function remove_shortcodes_process_posts() {

        foreach ( $this->items_posts as $item_post ):
            
            if ( $content = $item_post->post_content ) {
                $content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $content);
                $content = preg_replace('~(?:\[/?).*?"]~s', '', $content);
                $content = preg_replace('(\\[([^[]|)*])', '', $content );
                $content = preg_replace('/\[(.*?)\]/', '', $content );
                //$content = htmlentities( $content );

                $this->update_items_posts( $item_post->ID, $content );
            }

        endforeach;
    }

    /**
    * Update Products
    */
    public function update_items_posts($post_id, $content) {

        try {
            // Do Something
            $args = array(
                'ID'           => $post_id,
                'post_content' => $content,
                'post_date'     => date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ),
                'post_date_gmt' => gmdate( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) ),
            );
           
            wp_update_post( $args );

            $this->count_updated++;

        } catch (\Throwable $th) {
            $this->log_error[] = 'Error updating product: ' . $th->getMessage();
            $this->log_error[] = 'Post ID: ' . $post_id;
            $this->log_errors();
        }
    }

    /**
     * Resume Log
     */
    public function log_resume() {
        $log = date("F j, Y, g:i a", current_time( 'timestamp', 0 )).PHP_EOL.
        "Posts Found: " . $this->count_posts.PHP_EOL.
        "Posts Updated: " . $this->count_updated.PHP_EOL.
        "Quantity of Errors: " . count($this->count_error).PHP_EOL;
        if (count($this->count_error) > 0) {
            $log .= "Posts Error: " . implode(", ", $this->count_error).PHP_EOL;
        }
        $log .= "---------------------------------------------".PHP_EOL;
        file_put_contents(REMOVESC_IMPORTER_LOG_FILE, $log, FILE_APPEND);

        return $log;
    }

}