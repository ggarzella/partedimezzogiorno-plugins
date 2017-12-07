<?php

/*
  Plugin Name: Loads the header image on frontend pages
  Description: Questo plugin permette di aggiungere una o più immagini copertina alle relative pagine del frontend
  Version: 1.0
  Author: Giovanni Battista Garzella
*/

if (!defined('WPINC')) die;

require_once plugin_dir_path(__FILE__) . 'includes/load-image-header-loader.php';

function run_load_image_header_meta_manager() {
    $cdm = new Load_Image_Header_Loader();
    $cdm->run();
}

run_load_image_header_meta_manager();