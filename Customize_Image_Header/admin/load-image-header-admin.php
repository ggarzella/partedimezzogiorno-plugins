<?php

class Load_Image_Header_Admin
{
    private $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        global $wp_query;

        if (!is_admin()) {

            wp_enqueue_script(
                'load-image-header',
                plugin_dir_url(__FILE__) . 'js/load-image-header-admin.js',
                array('jquery'),
                $this->version
            );

            wp_localize_script(
                'load-image-header',
                'loadimageheader',
                [
                    'url' => admin_url('admin-ajax.php'),
                    'query' => $wp_query->query
                ]
            );
        }

        register_taxonomy_for_object_type('category', 'attachment');
    }

    public function be_ajax_load_image_header()
    {
        $category_name = esc_attr($_POST['category']);

        $args = array(
            'post_type' => 'attachment',
            'category_name' => $category_name
        );

        ob_start();

        $result = new WP_Query($args);

        if ($result->have_posts()):
            while ($result->have_posts()):
                $result->the_post();
            endwhile;
        endif;

        wp_reset_postdata();
        $data = ob_get_clean();

        $result = array();
        $result["content"] = $data;

        wp_send_json_success($result);
        wp_die();
    }
}