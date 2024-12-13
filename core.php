<?php
/*Plugin Name: خبرنامه
Plugin URI: http://siteyar.net/plugins/
Description: پلاگین خبرنامه
Author: sadeq yaqobi
Version: 1.0.0
License: GPLv2 or later
Author URI: http://siteyar.net/sadeq-yaqobi/ */

// if the session hasn't started yet, start it
/*if (!session_id()) {
    session_start();
}*/

#for security
defined('ABSPATH') || exit();

//defined required const
define('NL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NL_PLUGIN_URL', plugin_dir_url(__FILE__));
const NL_PLUGIN_INC = NL_PLUGIN_DIR . '_inc/';
const NL_PLUGIN_VIEW = NL_PLUGIN_DIR . 'view/';
const NL_PLUGIN_ASSETS_DIR = NL_PLUGIN_DIR . 'assets/';
const NL_PLUGIN_ASSETS_URL = NL_PLUGIN_URL . 'assets/';

/**
 * Register and enqueue frontend assets
 */
function nl_register_assets_front() {
    // Register and enqueue CSS
    wp_register_style('nl-style',NL_PLUGIN_ASSETS_URL . 'css/front/front-style.css',[],'1.0.0');
    wp_enqueue_style('nl-style');
    // bootstrap-4.0.0
    wp_register_style('bootstrap-4', NL_PLUGIN_ASSETS_URL . 'css/front/bootstrap.min.css', '', '4.0.0');
    wp_enqueue_style('bootstrap-4');

    // Register and enqueue JavaScript
    wp_register_script('jquery-toast', NL_PLUGIN_ASSETS_URL . 'js/jquery.toast.min.js', ['jquery'], '1.0.0', ['strategy' => 'async', 'in_footer' => true]);
    wp_enqueue_script('jquery-toast');
    wp_register_script('nl-main-js',NL_PLUGIN_ASSETS_URL . 'js/front/front-js.js', ['jquery'], '1.0.0', ['strategy' => 'async', 'in_footer' => true]);
    wp_enqueue_script('nl-main-js');
    wp_register_script('nl-front-ajax',NL_PLUGIN_ASSETS_URL . 'js/front/front-ajax.js', ['jquery'], '1.0.0', ['strategy' => 'async', 'in_footer' => true]);
    wp_enqueue_script('nl-front-ajax');

    // localize script
    wp_localize_script('nl-front-ajax', 'nl_ajax', [
        'nl_ajaxurl' => admin_url('admin-ajax.php'),
        '_nl_nonce' => wp_create_nonce()
    ]);
}

function nl_register_assets_admin() {
    // Register and enqueue CSS
    wp_register_style('nl-admin-style',NL_PLUGIN_ASSETS_URL . 'css/admin/admin-style.css',[],'1.0.0');
    wp_enqueue_style('nl-admin-style');

    // Register and enqueue JavaScript
    wp_register_script('nl-admin-js',NL_PLUGIN_ASSETS_URL . 'js/admin/admin-js.js', ['jquery'], '1.0.0', ['strategy' => 'async', 'in_footer' => true]);
    wp_enqueue_script('nl-admin-js');
    wp_register_script('nl-admin-ajax',NL_PLUGIN_ASSETS_URL . 'js/admin/admin-ajax.js', ['jquery'], '1.0.0', ['strategy' => 'async', 'in_footer' => true]);
    wp_enqueue_script('nl-admin-ajax');
}
add_action('wp_enqueue_scripts', 'nl_register_assets_front');
add_action('admin_enqueue_scripts', 'nl_register_assets_admin');

//including

// it's necessary to include pluggable.php file if you want to use something like wp_mail() function in plugins because this function will include just when all plugins were included
include_once (ABSPATH.'wp-includes/pluggable.php');

if (is_admin()) {
    include NL_PLUGIN_INC . 'admin/menus.php';
}

    include NL_PLUGIN_INC . 'database/table-functions.php';
    include NL_PLUGIN_INC . 'front/news-letter-subscribe.php';
    include NL_PLUGIN_INC . 'front/sendMail.php';
    include NL_PLUGIN_VIEW . 'front/news-letter.php';


//activation and deactivation plugin hooks
function nl_activation_functions()
{
    nl_create_user_emails_table();
}

function nl_deactivation_functions()
{
    //
}
register_activation_hook(__FILE__,'nl_activation_functions');
register_deactivation_hook(__FILE__,'nl_deactivation_functions');

