<?php

class Save_Group_Loader
{
    protected $loader;

    protected $plugin_slug;

    protected $version;

    public function __construct() {
        $this->plugin_slug = 'save-group-slug';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/save-group-admin.php';

        require_once plugin_dir_path(__FILE__) . 'save-group-add-hook.php';
        $this->loader = new Save_Group_Add_Hook();
    }

    private function define_admin_hooks() {
        $admin = new Save_Group_Admin($this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
        $this->loader->add_action('add_meta_boxes', $admin, 'add_meta_box');
        $this->loader->add_action('post_edit_form_tag', $admin, 'add_edit_form_multipart_encoding');
        $this->loader->add_action('wp_ajax_save_member_group', $admin, 'save_member_meta_box_ajax_handler');
    }

    public function run() {
        $this->loader->run();
    }

    public function get_version() {
        return $this->version;
    }
}