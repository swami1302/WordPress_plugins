<?php
namespace Dd;
/*
 * Plugin Name: Delete Drafts Daily V01
 * Description: Automatically deletes draft posts daily at 10:00 AM.
 * version: 1.0.0
 * Author: swami
 * licence: GPL2 or latest
 * Text Domain: dd-delete-draft
*/

defined("ABSPATH") or exit();

defined('MY_PLUGIN_PATH') or define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));


if (!file_exists(MY_PLUGIN_PATH . 'vendor/autoload.php')) {
    error_log('Vendor autoload file not found');
    return;
}
require_once MY_PLUGIN_PATH . 'vendor/autoload.php';


if(class_exists('Dd\src\Router')){
    $router=new \Dd\src\Router();
    $router->init();
}