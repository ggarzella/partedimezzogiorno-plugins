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

        /*if (!empty($post))
        {*/
        $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);

        if ($pageTemplate == 'page-comando.php' || $pageTemplate == 'page-gruppo-armato.php' || $pageTemplate == 'page-gruppo-militare.php' || $pageTemplate == 'page-cavalieri.php' || $pageTemplate == 'page-paggi.php' || $pageTemplate == 'page-specialisti.php')
        {
            add_meta_box(
                'save_group_members_box',
                'Membri',
                array($this, 'render_meta_box'),
                'page',
                'normal',
                'high'
            );
        }
        //}
    }

    public function render_meta_box()
    {
        if ($group = $this->buildGroup()) require_once plugin_dir_path(__FILE__) . 'partials/save-group-view.php';
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

        $config = file_get_contents(get_template_directory_uri() . '/js/config.json');
        $schema = json_decode($config, true);

        if ($template_name = mezzogiorno_get_template_slug(get_page_template_slug($post->ID)))
        {
            $keys = explode("-", $template_name);

            foreach ($schema as $section) {

                if (count($keys) == 1) {

                    if ($schema[$keys[0]] == $section) {
                        $objGroup = $section;
                        break;
                    }

                } else {

                    if ($section[$post->post_name]) {

                        $objGroup = $section[$post->post_name];
                        break;

                    } else {

                        foreach ($section as $group) {

                            if ($group[$post->post_name]) {
                                $objGroup = $group[$post->post_name];
                                break 2;
                            }
                        }
                    }
                }
            }
        } else
            $objGroup = $schema[$post->post_name];

        $result = [];

        if ($objGroup) {
            foreach ($objGroup as $name => $number)
                for ($y = 0; $y < $number; $y++)
                    array_push($result, $name);

            return $result;
        }

        return false;
    }
}