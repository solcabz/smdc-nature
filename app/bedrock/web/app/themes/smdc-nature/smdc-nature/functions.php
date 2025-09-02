<?php

//Add # Munus Selection
function menu_option() {
    register_nav_menu('primary', 'Primary Menu');
    register_nav_menu('secondary', 'Secondary Menu');
    register_nav_menu('tertiary', 'Tertiary Menu');
}
add_action('after_setup_theme', 'menu_option');

// style.css
function my_theme_enqueue_styles() {
    wp_enqueue_style('theme-style', get_template_directory_uri() . "/style.css", array(), '2.0', 'all');
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

// all css on the assets
function enqueue_all_styles() {
    $theme_dir = get_template_directory_uri();
    $style_dir = get_template_directory() . '/assets/css/';

    // Check if directory exists
    if (is_dir($style_dir)) {
        foreach (glob($style_dir . '*.css') as $file) {
            $filename = basename($file);
            wp_enqueue_style("custom-$filename", $theme_dir . "/assets/css/$filename", array(), filemtime($file));
        }
    }
}
add_action('wp_enqueue_scripts', 'enqueue_all_styles');

// Enqueue all JS files from assets/js/
function enqueue_all_scripts() {
    $theme_dir = get_template_directory_uri();
    $script_dir = get_template_directory() . '/assets/js/';

    if (is_dir($script_dir)) {
        foreach (glob($script_dir . '*.js') as $file) {
            $filename = basename($file);
            wp_enqueue_script("custom-$filename", $theme_dir . "/assets/js/$filename", array('jquery'), filemtime($file), true);
        }
    }
}
add_action('wp_enqueue_scripts', 'enqueue_all_scripts');

// Enqueue Live search
function enqueue_live_search_assets() {
    wp_enqueue_script(
        'live-search',
        get_template_directory_uri() . '/assets/js/live-search.js',
        [],
        null,
        true
    );

    wp_localize_script('live-search', 'liveSearch', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_live_search_assets');


require_once get_template_directory() . '/functions/search-function.php';
require_once get_template_directory() . '/functions/country-settings.php';
require_once get_template_directory() . '/functions/form-settings.php';

require_once get_template_directory() . '/functions/admin-quote.php';
