<?php
add_action('wp_ajax_nl_news_letter_unsubscribe', 'nl_news_letter_unsubscribe');
add_action('wp_ajax_nopriv_nl_news_letter_unsubscribe', 'nl_news_letter_unsubscribe');

/**
 * Handle newsletter unsubscription.
 */
function nl_news_letter_unsubscribe()
{
    // Security: Verify nonce token
    if (!isset($_POST['_nonce']) || !wp_verify_nonce($_POST['_nonce'])) {
        wp_send_json(['error'=>true,'message' => 'Access denied'], 403);
    }

    $email = sanitize_text_field($_POST['email']);
    nl_validate_email($email);
    if(!nl_is_user_subscribed($email)){
        wp_send_json(['error'=>true,'message' => 'این ایمیل قبلا عضو خبرنامه نشده است.'], 400);
    }
    nl_delete_user_email($email);
}

/**
 * delete the user's email from the database.
 *
 * @param string $email
 */
function nl_delete_user_email($email)
{
    global $wpdb;
    $table = $wpdb->prefix . 'nl_user_emails';
    $data = ['email' => $email];
    $format = ['%s'];
    $stmt = $wpdb->delete($table, $data, $format);

    if (!$stmt) {
        wp_send_json(['error'=>true,'message' => 'خطایی در لغو خبرنامه رخ داده است. دوباره تلاش کنید.'], 500);
    }

    wp_send_json(['success'=>true,'message' => 'عضویت در خبرنامه با موفقیت لغو شد.'], 200);
}

