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

    private function ea_first_term($taxonomy, $field) {

        $terms = get_the_terms(get_the_ID(), $taxonomy);

        if( empty( $terms ) || is_wp_error( $terms ) )
            return false;

        // If there's only one term, use that
        if( 1 == count( $terms ) ) {
            $term = array_shift( $terms );
        } else {
            $term = array_shift( $list );
        }

        // Output
        if( $field && isset( $term->$field ) )
            return $term->$field;

        else
            return $term;

    }

    public function be_ajax_load_more() {

        $args = array(
            'category_name' => 'eventi',
            'posts_per_page' => 4,
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

        $count = 0;

        if ($loop->have_posts()):
            while ($loop->have_posts()):
                $loop->the_post();
                get_template_part('includes/loop', 'home');
                $count++;
            endwhile;
        endif;

        wp_reset_postdata();
        $data = ob_get_clean();

        $continue = true;

        if ($count < 4) $continue = false;

        $result = array();
        $result["content"] = $data;
        $result["continue"] = $continue;

        wp_send_json_success($result);
        wp_die();
    }
}