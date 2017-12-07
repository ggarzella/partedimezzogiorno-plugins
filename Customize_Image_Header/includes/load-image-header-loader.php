<?php

class Load_Image_Header_Loader
{
    protected $loader;

    protected $plugin_slug;

    protected $version;

    public function __construct() {
        $this->plugin_slug = 'load-image-header-slug';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/load-image-header-admin.php';

        require_once plugin_dir_path(__FILE__) . 'load-image-header-add-hook.php';
        $this->loader = new Load_Image_Header_Add_Hook();
    }

    private function define_admin_hooks() {
        $admin = new Load_Image_Header_Admin($this->get_version());
        $this->loader->add_action('init', $admin, 'enqueue_styles');
    }

    public function run() {
        $this->loader->run();
    }

    public function get_version() {
        return $this->version;
    }
}