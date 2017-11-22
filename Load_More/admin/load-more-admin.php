<?php

class Load_More_Admin
{
    private $version;

    public function __construct($version) {
        $this->version = $version;
    }

    public function enqueue_scripts() {

        global $wp_query;

        if (!is_admin()) {

            wp_enqueue_script(
                'be-load-more',
                plugin_dir_url(__FILE__) . 'js/load-more-admin.js',
                array('jquery'),
                $this->version
            );

            wp_localize_script(
                'be-load-more',
                'beloadmore',
                [
                    'url' => admin_url('admin-ajax.php'),
                    'query' => $wp_query->query
                ]
            );
        }
    }

    public function be_ajax_load_more()
    {
        $category_name = esc_attr($_POST['category']);
        $posts_per_page = get_option('posts_per_page');

        $args = array(
            'category_name' => $category_name,
            'paged' => esc_attr($_POST['page']),
            'meta_key'   => 'meta-box-date',
            'orderby'    => 'meta_value',
            'meta_query' => array(
                array(
                    'key'     => 'meta-box-date',
                    'orderby' => 'meta_value'
                )
            )
        );

        ob_start();

        $loop = new WP_Query($args);

        if ($loop->have_posts()):
            while ($loop->have_posts()):
                $loop->the_post();
                get_template_part('includes/loop', 'home');
            endwhile;
        endif;

        wp_reset_postdata();
        $data = ob_get_clean();

        $category = get_category(get_cat_ID($category_name));
        $total = $category->category_count;

        $result = array();
        $result["content"] = $data;
        $result["post_per_page"] = $posts_per_page;
        $result["total"] = $total;

        wp_send_json_success($result);
        wp_die();
    }
}