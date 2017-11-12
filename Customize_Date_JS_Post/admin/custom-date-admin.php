<?php

class Custom_Date_Admin
{
    private $version;

    public function __construct($version) {
        $this->version = $version;
    }

    public function enqueue_styles() {

        wp_enqueue_style(
            'jquery-datetimepicker-css',
            plugin_dir_url(__FILE__) . 'css/jquery.datetimepicker.min.css',
            array(),
            $this->version,
            FALSE
        );

        wp_enqueue_script(
            'jquery-datetimepicker-js',
            plugin_dir_url(__FILE__) . 'js/jquery.datetimepicker.full.min.js',
            array(),
            $this->version,
            FALSE
        );

        wp_enqueue_script(
            'customizing-date-js',
            plugin_dir_url(__FILE__) . 'js/custom-date-admin.js',
            array(),
            $this->version,
            FALSE
        );
    }

    public function add_meta_box() {
        add_meta_box(
            'custom-date-meta-box',
            'Data dell\'evento',
            array($this, 'render_meta_box'),
            'post',
            'side',
            'high'
        );
    }

    public function render_meta_box() {
        require_once plugin_dir_path(__FILE__) . 'partials/custom-date-view.php';
    }

    public function save_custom_post($post_id, $post) {

        /*if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
            return $post_id;*/

        if(!current_user_can("edit_post", $post_id))
            return $post_id;

        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
            return $post_id;

        $slug = "post";
        if($slug != $post->post_type)
            return $post_id;

        if(isset($_POST["meta-box-date"])) {

            $date_selected = date('Y-m-d H:i:s', strtotime($_POST["meta-box-date"]) . ':00');

            update_post_meta($post_id, "meta-box-date", $date_selected);
        }
    }
}