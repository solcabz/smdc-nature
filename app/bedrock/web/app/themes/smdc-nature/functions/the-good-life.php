<?php
// ---------------------------
// Register Custom Post Type
// ---------------------------
function register_good_life_post_type() {
    $labels = array(
        'name'               => 'The Good Life',
        'singular_name'      => 'The Good Life Item',
        'menu_name'          => 'The Good Life',
        'name_admin_bar'     => 'The Good Life Item',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Good Life Item',
        'new_item'           => 'New Good Life Item',
        'edit_item'          => 'Edit Good Life Item',
        'view_item'          => 'View Good Life Item',
        'all_items'          => 'All Good Life Items',
        'search_items'       => 'Search Good Life Items',
        'not_found'          => 'No Good Life items found.',
        'not_found_in_trash' => 'No Good Life items found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'the-good-life'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-smiley',
        'supports'           => array('title','thumbnail', 'custom-fields', 'excerpt'),
        'taxonomies'         => array('good_life_category'),
    );

    register_post_type('good_life', $args);
}
add_action('init', 'register_good_life_post_type');

add_theme_support('post-thumbnails');

// ---------------------------
// Register Taxonomy
// ---------------------------
function register_good_life_category_taxonomy() {
    $labels = array(
        'name'              => 'Categories',
        'singular_name'     => 'Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Categories',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'good-life-category'),
    );

    register_taxonomy('good_life_category', array('good_life'), $args);
}
add_action('init', 'register_good_life_category_taxonomy');

// ---------------------------
// Add Default Terms
// ---------------------------
function add_default_good_life_categories() {
    $terms = array(
        'Events and Promotions',
        'In the News'
    );

    foreach ($terms as $term) {
        if (!term_exists($term, 'good_life_category')) {
            wp_insert_term($term, 'good_life_category');
        }
    }
}
add_action('init', 'add_default_good_life_categories');

// ---------------------------
// Settings → Reading field
// ---------------------------
// Add custom setting field in Settings → Reading
function good_life_posts_setting_init() {
    add_settings_field(
        'good_life_posts_per_page',
        'Good Life Posts Per Page',
        'good_life_posts_setting_field',
        'reading',
        'default'
    );

    register_setting('reading', 'good_life_posts_per_page', array(
        'type'              => 'integer',
        'sanitize_callback' => 'good_life_sanitize_posts_per_page',
        'default'           => 10, // ✅ default = 10
    ));
}
add_action('admin_init', 'good_life_posts_setting_init');

// Field input box
function good_life_posts_setting_field() {
    $value = get_option('good_life_posts_per_page', 10);
    echo '<input type="number" name="good_life_posts_per_page" value="' . esc_attr($value) . '" min="1" max="100" />';
    echo '<p class="description">Set how many "Good Life" items show per page (1–100).</p>';
}

// ✅ Sanitize: enforce limits (min 1, max 100)
function good_life_sanitize_posts_per_page($value) {
    $value = absint($value);
    if ($value < 1) {
        $value = 10; // fallback default
    } elseif ($value > 100) {
        $value = 100; // max cap
    }
    return $value;
}

// Apply setting to archive & taxonomy
function good_life_posts_per_page_dynamic($query) {
    if (!is_admin() && $query->is_main_query() && (is_post_type_archive('good_life') || is_tax('good_life_category'))) {
        $ppp = get_option('good_life_posts_per_page', 10);
        $query->set('posts_per_page', $ppp);
    }
}
add_action('pre_get_posts', 'good_life_posts_per_page_dynamic');
