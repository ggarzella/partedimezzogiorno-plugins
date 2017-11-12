<?php

class Load_More_Loader
{
    protected $loader;

    protected $plugin_slug;

    protected $version;

    public function __construct() {
        $this->plugin_slug = 'load-more-slug';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/load-more-admin.php';

        require_once plugin_dir_path(__FILE__) . 'load-more-add-hook.php';
        $this->loader = new Load_More_Add_Hook();
    }

    private function define_admin_hooks() {
        $admin = new Load_More_Admin($this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $admin, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_be_ajax_load_more', $admin, 'be_ajax_load_more');
        $this->loader->add_action('wp_ajax_nopriv_be_ajax_load_more', $admin, 'be_ajax_load_more');
    }

    public function run() {
        $this->loader->run();
    }

    public function get_version() {
        return $this->version;
    }
}