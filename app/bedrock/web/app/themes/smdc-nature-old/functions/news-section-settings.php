<?php
// Step 1: Add Admin Menu Page
add_action('admin_menu', function () {
    add_menu_page(
        'News Section Settings',     // Page title
        'News Settings',             // Menu title
        'manage_options',            // Capability (admin only)
        'news-settings-page',        // Menu slug (must be unique)
        'news_settings_page_html',   // Callback function
        'dashicons-admin-site-alt3', // Dashicon
        31                           // Menu position
    );
}, 20);

// Step 2: Register Settings
add_action('admin_init', function () {
    register_setting('news_settings_group', 'news_title', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    register_setting('news_settings_group', 'news_title2', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    register_setting('news_settings_group', 'news_highlight', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    register_setting('news_settings_group', 'news_blurb', [
        'sanitize_callback' => 'wp_kses_post'
    ]);

    add_settings_section(
        'news_section',
        'News Section Content',
        null,
        'news-settings-page'
    );

    add_settings_field('news_title', 'News Title', 'news_title_cb', 'news-settings-page', 'news_section');
    add_settings_field('news_title2', 'News Title 2', 'news_title2_cb', 'news-settings-page', 'news_section');
    add_settings_field('news_highlight', 'News Highlight', 'news_highlight_cb', 'news-settings-page', 'news_section');
    add_settings_field('news_blurb', 'News Blurb', 'news_blurb_cb', 'news-settings-page', 'news_section');
});

// Step 3: Field Callbacks
function news_title_cb() {
    $value = get_option('news_title', '');
    echo '<input type="text" name="news_title" value="' . esc_attr($value) . '" class="regular-text">';
}

function news_title2_cb() {
    $value = get_option('news_title2', '');
    echo '<input type="text" name="news_title2" value="' . esc_attr($value) . '" class="regular-text">';
}

function news_highlight_cb() {
    $value = get_option('news_highlight', '');
    echo '<input type="text" name="news_highlight" value="' . esc_attr($value) . '" class="regular-text">';
}

function news_blurb_cb() {
    $value = get_option('news_blurb', '');
    echo '<textarea name="news_blurb" rows="4" class="large-text">' . esc_textarea($value) . '</textarea>';
}

// Step 4: Admin Page HTML
function news_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>News Section Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('news_settings_group');
            do_settings_sections('news-settings-page');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
