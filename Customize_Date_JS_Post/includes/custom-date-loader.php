<?php

class Custom_Date_Loader
{
    protected $loader;

    protected $plugin_slug;

    protected $version;

    public function __construct() {
        $this->plugin_slug = 'custom-date-slug';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/custom-date-admin.php';

        require_once plugin_dir_path(__FILE__) . 'custom-date-add-hook.php';
        $this->loader = new Custom_Date_Add_Hook();
    }

    private function define_admin_hooks() {
        $admin = new Custom_Date_Admin($this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
        $this->loader->add_action('add_meta_boxes', $admin, 'add_meta_box');
        $this->loader->add_action('save_post', $admin, 'save_custom_post', 10, 2);
    }

    public function run() {
        $this->loader->run();
    }

    public function get_version() {
        return $this->version;
    }
}