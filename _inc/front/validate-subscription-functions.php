<?php

/**
 * Validate the email address.
 *
 * @param string $email
 */
function nl_validate_email(string $email)
{
    if (empty($email)) {
        wp_send_json(['error'=>true,'message' => 'لطفا ایمیل خود را وارد نمایید.'], 400);
    }

    if (!is_email($email)) {
        wp_send_json(['error'=>true,'message' => 'لطفا ایمیل معتبر وارد نمایید.'], 400);
    }
}

/**
 * Check if the user is already subscribed.
 *
 * @param string $email
 */
function nl_is_user_subscribed(string $email)
{
    global $wpdb;
    $table = $wpdb->prefix . 'nl_user_emails';
    $stmt = $wpdb->get_row($wpdb->prepare("SELECT email FROM {$table} WHERE email=%s", $email));

    if (!$stmt) {
        return false;
    }
    return true;
}

