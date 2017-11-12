<?php

/*
  Plugin Name: Save group members
  Description: Questo plugin permette di aggiungere, eliminare e modificare i membri di un gruppo civile o di una magistratura
  Version: 1.0
  Author: Giovanni Battista Garzella
*/

if (!defined('WPINC')) die;

require_once plugin_dir_path(__FILE__) . 'includes/save-group-loader.php';

function run_save_group_meta_manager() {
    $cdm = new Save_Group_Loader();
    $cdm->run();
}

run_save_group_meta_manager();