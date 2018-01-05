<?php

class Save_Group_Admin
{
    private $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style(
            'save-group-admin-css',
            plugin_dir_url(__FILE__) . 'css/save-group-admin.css',
            array(),
            $this->version,
            FALSE
        );

        wp_enqueue_script(
            'save-group-admin-js',
            plugin_dir_url(__FILE__) . 'js/save-group-admin.js',
            array(),
            $this->version,
            FALSE
        );

        wp_localize_script(
            'save-group-admin-js',
            'save_member_meta_box_obj',
            [
                'url' => admin_url('admin-ajax.php'),
                'upload_url' => admin_url('async-upload.php'),
                'nonce' => wp_create_nonce('media-form')
            ]
        );
    }

    public function add_meta_box()
    {
        global $post;

        add_meta_box(
            'save_group_members_box',
            'Membri',
            array($this, 'render_meta_box'),
            'gruppi',
            'normal',
            'high'
        );
    }

    public function render_meta_box()
    {
        if (count($group = $this->buildGroup()) > 0)
            require_once plugin_dir_path(__FILE__) . 'partials/save-group-view.php';
        else
            wp_die();
    }

    public function add_edit_form_multipart_encoding()
    {
        echo ' enctype="multipart/form-data"';
    }

    public function save_member_meta_box_ajax_handler()
    {
        $data = [];

        if (isset($_POST['action'])) {

            if ($_POST['action'] == 'save_member_group') {

                $index = (int)$_POST['formIndex'];
                $post_id = (int)$_POST['postId'];
                $role = filter_var($_POST['role']);
                $name = $_POST['name'];
                $lastname = $_POST['lastname'];
                $description = $_POST['description'];
                $image_id = (int)$_POST['imageId'];

                $image_url = wp_get_attachment_image_src($image_id, 'thumbnail');
                $image_url = $image_url[0];

                if (!add_post_meta($post_id, 'name' . $index, $name, true))
                    update_post_meta($post_id, 'name' . $index, $name);

                if (!add_post_meta($post_id, 'lastname' . $index, $lastname, true))
                    update_post_meta($post_id, 'lastname' . $index, $lastname);

                if (!add_post_meta($post_id, 'role' . $index, $role, true))
                    update_post_meta($post_id, 'role' . $index, $role);

                if (!add_post_meta($post_id, 'description' . $index, $description, true))
                    update_post_meta($post_id, 'description' . $index, $description);

                if (!add_post_meta($post_id, 'imageId' . $index, $image_id, true))
                    update_post_meta($post_id, 'imageId' . $index, $image_id);

                $data["result"] = "success";
                $data["name"] = $name;
                $data["lastname"] = $lastname;
                $data["role"] = $role;
                $data["description"] = $description;
                $data["index"] = $index;
                $data["imageId"] = $image_id;
                $data["imageUrl"] = $image_url;

            }

        } else
            $data["result"] = "failure";

        echo json_encode($data);

        wp_die();
    }

    private function buildGroup()
    {
        global $post;

        $groupName = $post->post_name;

        try
        {
            $category = get_the_category();
            $ancestors = get_ancestors($category[0]->term_id, 'category');
            $direct_parent_id = $ancestors[0];
            $categoryParent = get_the_category_by_ID($direct_parent_id);

            if (is_wp_error($categoryParent)) wp_die();

            $config = file_get_contents(get_template_directory_uri() . '/js/config-' . $categoryParent . '.json');
        }
        catch(Exception $e)
        {
            wp_die();
        }

        $schema = json_decode($config, true);

        if ($group = $schema[$category[0]->name][$groupName])
            $objGroup = $group;

        $result = array();

        if ($objGroup)
            foreach ($objGroup as $name => $number)
                for ($y = 0; $y < $number; $y++)
                    array_push($result, $name);

        return $result;
    }
}