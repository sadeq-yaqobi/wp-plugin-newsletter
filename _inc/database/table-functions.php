<?php
function nl_create_user_emails_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'nl_user_emails';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        is_subscribe tinyint(1) DEFAULT 1 COMMENT '0:unsubscribe 1:subscribe',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        update_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY email (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
