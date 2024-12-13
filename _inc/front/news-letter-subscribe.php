<?php
add_action('wp_ajax_nl_new_letter_subscribe', 'nl_new_letter_subscribe');
add_action('wp_ajax_nopriv_nl_new_letter_subscribe', 'nl_new_letter_subscribe');

function nl_new_letter_subscribe()
{
    // Security: Verify nonce token
    if (!isset($_POST['_nonce']) || !wp_verify_nonce($_POST['_nonce'])) {
        wp_send_json([
            'error' => true,
            'message' => 'access denied',
        ], 403);
    }
    $email = sanitize_text_field($_POST['email']);
    nl_validate_email($email);
    nl_is_user_subscribed($email);
    nl_insert_user_email($email);
}


function nl_validate_email($email)
{
    // Check for empty fields
    if (empty($email)) {
        wp_send_json([
            'error' => true,
            'message' => 'لطفا ایمیل خود را وارد نمایید.', // Empty fields message in Persian
        ], 400);
    }
    // Validate email format
    if (!is_email($email)) {
        wp_send_json([
            'error' => true,
            'message' => 'لطفا ایمیل معتبر وارد نمایید.', // Invalid email message in Persian
        ], 400);
    }
}

function nl_is_user_subscribed($email)
{
    global $wpdb;
    $table = $wpdb->prefix . 'nl_user_emails';
    $stmt = $wpdb->get_row($wpdb->prepare("SELECT email FROM {$table} WHERE email=%s", $email));
    if ($stmt) {
        wp_send_json([
            'error' => true,
            'message' => 'این ایمیل قبلا ثبت شده.',
        ], 400);
    }
}

function nl_insert_user_email($email)
{
    global $wpdb;
    $table = $wpdb->prefix . 'nl_user_emails';

    $data = ['email' => $email];
    $format = ['%s'];
    $stmt = $wpdb->insert($table, $data, $format);

    if (!$stmt) {
        wp_send_json([
            'error' => true,
            'message' => 'خطایی در ثبت ایمیل رخ داده است. دوباره تلاش کنید.',
        ], 500);
    }
    wp_send_json([
        'success' => true,
        'message' => 'عضویت در خبرنامه با موفقیت انجام شد.',
    ], 200);
}

function nl_get_subscribe_user(): string
{
    global $wpdb;
    $table = $wpdb->prefix . 'nl_user_emails';
    $emails = $wpdb->get_results("SELECT email FROM {$table}", ARRAY_A);
    $users_emails = '';
    foreach ($emails as $email) {
        $users_emails .= $email['email'] . ',';
    }
    return $users_emails;
}


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

function nl_send_email_to_subscriber()
{
    // Include layout
    include NL_PLUGIN_INC . 'front/news-letter-email-layout.php';
    $to = nl_get_subscribe_user();
    $post = nl_get_latest_post();
    $message = nl_news_letter_email_layout($post);
    $header = ['Content-Type:text/html;charset=UTF-8'];
    wp_mail($to, 'خبرنامه', $message, $header);
}

add_action('publish_post', 'nl_send_email_to_subscriber');
add_action('publish_tech', 'nl_send_email_to_subscriber');
