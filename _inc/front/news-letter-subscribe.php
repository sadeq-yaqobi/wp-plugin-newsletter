<?php
add_action('wp_ajax_nl_news_letter_subscribe', 'nl_news_letter_subscribe');
add_action('wp_ajax_nopriv_nl_news_letter_subscribe', 'nl_news_letter_subscribe');

/**
 * Handle newsletter subscription.
 */
function nl_news_letter_subscribe()
{
    // Security: Verify nonce token
    if (!isset($_POST['_nonce']) || !wp_verify_nonce($_POST['_nonce'])) {
        wp_send_json(['error'=>true,'message' => 'Access denied'], 403);
    }

    $email = sanitize_text_field($_POST['email']);
    nl_validate_email($email);
    if(nl_is_user_subscribed($email)){
        wp_send_json(['error'=>true,'message' => 'این ایمیل قبلا عضو خبرنامه شده است.'], 400);
    }

    nl_insert_user_email($email);
}



/**
 * Insert the user's email into the database.
 *
 * @param string $email
 */
function nl_insert_user_email($email)
{
    global $wpdb;
    $table = $wpdb->prefix . 'nl_user_emails';

    $data = ['email' => $email];
    $format = ['%s'];
    $stmt = $wpdb->insert($table, $data, $format);

    if (!$stmt) {
        wp_send_json(['error'=>true,'message' => 'خطایی در ثبت ایمیل رخ داده است. دوباره تلاش کنید.'], 500);
    }

    wp_send_json(['success'=>true,'message' => 'عضویت در خبرنامه با موفقیت انجام شد.'], 200);
}

/**
 * Get all subscribed user emails.
 *
 * @return string
 */
function nl_get_subscribe_user(): string
{
    global $wpdb;
    $table = $wpdb->prefix . 'nl_user_emails';
    $emails = $wpdb->get_results("SELECT email FROM {$table}", ARRAY_A);

    return implode(',', array_column($emails, 'email'));
}

/**
 * Get the latest post.
 *
 * @return WP_Post|string
 */
function nl_get_latest_post()
{
    $args = [
        'posts_per_page' => 1,
        'orderby' => 'modified',
        'order' => 'DESC',
        'post_type' => ['post', 'tech']
    ];

    $latest_post = get_posts($args);

    if (!empty($latest_post)) {
        $post = $latest_post[0];
        setup_postdata($post);
        wp_reset_postdata();
        return $post;
    }

    return 'پستی پیدا نشد.';
}

/**
 * Send email to subscribers when a new post is published.
 */
function nl_send_email_to_subscriber()
{
    include NL_PLUGIN_INC . 'front/news-letter-email-layout.php';
    $to = nl_get_subscribe_user();
    $post = nl_get_latest_post();
    $message = nl_news_letter_email_layout($post);
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    wp_mail($to, 'خبرنامه', $message, $headers);
}

add_action('publish_post', 'nl_send_email_to_subscriber');
add_action('publish_tech', 'nl_send_email_to_subscriber');