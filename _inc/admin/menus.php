<?php
add_action('admin_menu','nl_register_options');


// if you want to set plugin setting page under general setting menu, not by a specific menu
function nl_register_options()
{
    add_options_page(
        'تنظیمات خبرنامه',
        'خبرنامه',
        'manage_options',
        'news_letter_setting',
        'nl_news_letter_admin_layout' //it was implemented in view/admin/setting.php
    );
}

include_once NL_PLUGIN_VIEW . 'admin/setting.php';
