<?php
function nl_news_letter_admin_layout()
{

    if (!current_user_can('manage_options')) {
        return;
    }
    if (isset($_GET['setting-update'])) {
        add_settings_error('setting', 'setting-message', 'تنظیمات ذخیره گردید.', 'success');
    }
    settings_errors('setting-message');

    ?>
    <div class="nl-wrap">
        <form action="options.php" method="post" >
            <h1><?php echo esc_html(get_admin_page_title()) ?></h1>
            <?php
            settings_fields('news-letter'); // Output security fields
            do_settings_sections('news-letter-html');// Output setting sections
            // Submit Button
            echo '<div class="submit-wrapper nl-submit-wrapper">';
            submit_button('ذخیره تغییرات', 'primary large');
            echo '</div>';
            ?>
        </form>
    </div>

    <?php
}

// Initialize plugin settings and fields
function nl_setting_init()
{

 register_setting('news-letter', '', 'nl_form_sanitize_input');

    // Add settings section
    add_settings_section('nl_settings_section', '', '', 'news-letter-html');
    // Add settings fields for information that need to customize plugin features
    add_settings_field('nl_settings_field', '', 'nl_render_form', 'news-letter-html', 'nl_settings_section');
}

add_action('admin_init', 'nl_setting_init');

function nl_render_form()
{
    $nl_setting = get_option('_nl_option_name');
    ?>
    <div class="nl-element-wrapper">
        <label for="title">عنوان بخش در قالب</label>
        <input id="title" type="text" name="_nl_option_name[_nl_title]" value="<?php  nl_get_input_value($nl_setting['_nl_title'],'') ?>">

        <label for="number">تعداد مطالب جهت نمایش</label>
        <input id="number" type="text" name=_nl_option_name["_nl_number]" value="<?php  nl_get_input_value($nl_setting['_nl_number'],'') ?>">

        <label for="term">نمایش مطلب بر اساس</label>
        <select name="_nl_option_name[_nl_term]" id="term">
            <option value="category" <?php echo selected(esc_attr($nl_setting['_nl_term']), 'category'); ?>>دسته‌بندی‌های مطلب
            </option>
            <option value="tag" <?php echo selected(esc_attr($nl_setting['_nl_term']), 'tag'); ?>>برچسب‌های مطلب</option>
        </select>

        <label for="order_by">ترتیب نمایش مطالب</label>
        <select name="_nl_option_name[_nl_order_by]" id="order_by">
            <option value="asc" <?php echo selected(esc_attr($nl_setting['_nl_order_by']), 'asc'); ?>>صعودی</option>
            <option value="desc" <?php echo selected(esc_attr($nl_setting['_nl_order_by']), 'desc'); ?>>نزولی</option>
            <option value="rand" <?php echo selected(esc_attr($nl_setting['_nl_order_by']), 'rand'); ?> >تصادفی</option>
        </select>

        <label for="display_type">حالت نمایش</label>
        <div class="nl-display-type" id="display_type">
            <label for="display_type_block">
                <input id="display_type_block" type="radio" name="_nl_option_name[_nl_display_type]"
                       value="block" <?php echo checked(esc_attr($nl_setting['_nl_display_type']), 'block'); ?>>
                <img width="32px" height="32px" src="<?php echo NL_PLUGIN_ASSETS_URL . 'img/gallery.png'; ?> "
                     alt="icon" title="نمایش به صورت اسلایدر">
            </label>
            <label for="display_type_list">
                <input id="display_type_list" type="radio" name="_nl_option_name[_nl_display_type]"
                       value="list" <?php echo checked(esc_attr($nl_setting['_nl_display_type']), 'list'); ?>>
                <img width="32px" height="32px" src="<?php echo NL_PLUGIN_ASSETS_URL . 'img/list.png'; ?>" alt="icon"
                     title="نمایش به صورت لیست">
            </label>
        </div>
        <div class="nl-number-slider-container">
            <div class="nl-number-item-slider-wrapper ">
                <label for="number-item-slider">تعداد مطالب در اسلایدر</label>
                <input id="number-item-slider" type="text" name="_nl_option_name[_nl_number_item_slider]"
                       value="<?php nl_get_input_value($nl_setting['_nl_number_item_slider'],'') ?>">
            </div>
        </div>
    </div>
    <?php
}
//get input value
function nl_get_input_value($value, $default_value = '')
{
    echo isset($value) ? esc_attr($value) : $default_value;
}

// sanitize inputs
function nl_form_sanitize_input($input)
{
    $input['_nl_title'] = sanitize_text_field($input['_nl_title']);
    $input['_nl_number'] = sanitize_text_field($input['_nl_number']);

    $input['_nl_term'] = sanitize_text_field($input['_nl_term']);
    $input['_nl_order_by'] = sanitize_text_field($input['_nl_order_by']);

    $input['_nl_dinllay_type'] = sanitize_text_field($input['_nl_display_type']);
    $input['_nl_number_item_slider'] = sanitize_text_field($input['_nl_number_item_slider']);

    return $input;
}
