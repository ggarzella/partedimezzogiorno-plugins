<?php

/*
  Plugin Name: Customizing datetime post js
  Description: Questo plugin permette di aggiungere la data a cui il relativo articolo si riferisce
  Version: 1.0
  Author: Giovanni Battista Garzella
*/

if (!defined('WPINC')) die;

require_once plugin_dir_path(__FILE__) . 'includes/custom-date-loader.php';

function run_custom_date_meta_manager() {
    $cdm = new Custom_Date_Loader();
    $cdm->run();
}

run_custom_date_meta_manager();