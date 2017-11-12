<?php

/*
  Plugin Name: Load more post
  Description: Questo plugin permette di caricare contenuto in modo asincrono la dove richiesto
  Version: 1.0
  Author: Giovanni Battista Garzella
*/

if (!defined('WPINC')) die;

require_once plugin_dir_path(__FILE__) . 'includes/load-more-loader.php';

function run_load_more_meta_manager() {
    $cdm = new Load_More_Loader();
    $cdm->run();
}

run_load_more_meta_manager();