<?php
function nl_news_letter_email_layout($post, $logo_url = '')
{
// Ensure $post is a valid WP_Post object
    if (empty($post) || !($post instanceof WP_Post)) {
        return "Invalid post data.";
    }

// Sanitize and retrieve necessary data
    $site_title = esc_html(get_bloginfo('name'));
    $permalink = esc_url(get_permalink($post->ID));
    $post_title = esc_html($post->post_title);

// Handle logo display
    if (!empty($logo_url)) {
        $logo = '<img src="' . esc_url($logo_url) . '" alt="Logo">';
    } else {
        $logo = '<strong class="alternative_logo">' . $site_title . '</strong>';
    }

// Handle thumbnail
    if (has_post_thumbnail($post->ID)) {
        $thumbnail = get_the_post_thumbnail($post->ID, [640, 427], ['class' => 'img-fluid', 'loading' => '']);
    } else {
        $thumbnail = ''; // Fallback if no thumbnail exists
    }
//handle unsubscribe query string
    $unsubscribe_url = site_url('?unsubscribe=1');
// Define font URLs
    $font_IRANSans_url = esc_url(plugin_dir_url(__DIR__) . 'assets/fonts/IRANSansWeb/IRANSansWeb.ttf');
    $font_vazirmatn_url = esc_url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Vazirmatn:wght@100..900&display=swap');

// Return the HTML layout
    return '
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جدیدترین مطلب</title>
    <style>
        /* Import Fonts */
        @import url("' . $font_vazirmatn_url . '");
        @import url("' . $font_IRANSans_url . '");

        body {
            margin: 0;
            padding: 0;
            font-family: IRANSansWeb, Vazirmatn, Tahoma, Arial, sans-serif !important;
            background-color: #ffffff;
            color: #292929!important;
            direction: rtl;
        }

        a {
            text-decoration: none;
            background-color: transparent;
            -webkit-text-decoration-skip: objects;
            color: #292929!important;
        }

        a:hover, a:focus {
            text-decoration: none;
        }

        .wrapper {
            max-width: 600px;
            margin: auto;

            border-radius: 10px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #f6f8fc;
            padding: 40px;
            text-align: center;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 5px 0px, rgba(0, 0, 0, 0.1) 0px 0px 1px 0px;   
        }

        .header {
            margin-bottom: 20px;
            background-color: transparent !important;
        }

        .header img {
            max-width: 100px;
        }
        
        .title{
            text-align: right;
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.7;
        }
        .message {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.7;
        }

        .message h2 {
            text-align: center;
        }

        .alternative_logo {
            font-size: 24px;
            font-weight: 900;
            color: #d35400 !important;
        }

        .footer {
            font-size: 12px;
            color: #666;
            margin-top: 40px;
        }

        .image {
            margin: 45px 0 20px 0;
        }

        .image img {
            max-width: 100%;
            border-radius: 8px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
                border: 5px solid #8d95ff;
            }
        }
    </style>
</head>
<body>
<section class="wrapper" style="font-family: IRANSansWeb,Vazirmatn, Tahoma, Arial, sans-serif ">
    <div class="container">
        <div class="header">
            ' . $logo . '
        </div>
        <div class="title"><p>جدیدترین مطلب منتشر شده</p></div>
        <div class="image">
            <a href="' . $permalink . '">' . $thumbnail . '</a>
        </div>
        <div class="message">
            <h2><a href="' . $permalink . '">' . $post_title . '</a></h2>
        </div>
        <div class="footer">
            <a href="'.$unsubscribe_url.'">لغو اشتراک</a>
        </div>
    </div>
</section>
</body>
</html>';
}