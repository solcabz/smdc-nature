<?php
// =============================
// Register Property Post Type
// =============================
function register_property_post_type() {
    $labels = array(
        'name'               => 'Properties',
        'singular_name'      => 'Property',
        'menu_name'          => 'Properties',
        'name_admin_bar'     => 'Property',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Property',
        'new_item'           => 'New Property',
        'edit_item'          => 'Edit Property',
        'view_item'          => 'View Property',
        'all_items'          => 'All Properties',
        'search_items'       => 'Search Properties',
        'not_found'          => 'No properties found.',
        'not_found_in_trash' => 'No properties found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'properties'), // /properties/{slug}
        'capability_type'    => 'post',
        'has_archive'        => true, // enables /properties/
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-admin-home',
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
    );

    register_post_type('property', $args);
}
add_action('init', 'register_property_post_type');

// =============================
// Register Property Region Taxonomy (admin use only)
// =============================
function register_property_region_taxonomy() {
    $labels = array(
        'name'              => 'Property Regions',
        'singular_name'     => 'Property Region',
        'search_items'      => 'Search Property Regions',
        'all_items'         => 'All Property Regions',
        'edit_item'         => 'Edit Property Region',
        'update_item'       => 'Update Property Region',
        'add_new_item'      => 'Add New Property Region',
        'new_item_name'     => 'New Property Region Name',
        'menu_name'         => 'Property Regions',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        // disable pretty permalinks for taxonomy
        'rewrite'           => false, 
    );

    register_taxonomy('property_region', ['property'], $args);
}
add_action('init', 'register_property_region_taxonomy');

// =============================
// Flush rewrite rules on activation
// =============================
function flush_rewrite_rules_on_activation() {
    register_property_post_type();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_activation');

// =============================
// Property Location Meta Box
// =============================
function add_property_location_meta_box() {
    add_meta_box(
        'property_location_meta_box',
        'Property Location',
        'render_property_location_meta_box',
        'property',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_property_location_meta_box');

function render_property_location_meta_box($post) {
    $value = get_post_meta($post->ID, '_property_location', true);
    echo '<label for="property_location">Location</label>';
    echo '<input type="text" id="property_location" name="property_location" value="' . esc_attr($value) . '" style="width:100%;" />';
}

function save_property_location_meta($post_id) {
    if (array_key_exists('property_location', $_POST)) {
        update_post_meta($post_id, '_property_location', sanitize_text_field($_POST['property_location']));
    }
}
add_action('save_post', 'save_property_location_meta');

// =============================
// Admin List: Limit per page + Dropdown
// =============================
function set_properties_per_page($query) {
    global $pagenow;
    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'property') {
        if (!isset($_GET['property_per_page'])) {
            $query->set('posts_per_page', 10);
        }
    }
}
add_action('pre_get_posts', 'set_properties_per_page');

function property_items_per_page_dropdown() {
    global $typenow;
    if ($typenow == 'property') {
        $per_page = isset($_GET['property_per_page']) ? intval($_GET['property_per_page']) : 10;
        ?>
        <select name="property_per_page">
            <option value="10" <?php selected($per_page, 10); ?>>Show 10</option>
            <option value="20" <?php selected($per_page, 20); ?>>Show 20</option>
            <option value="50" <?php selected($per_page, 50); ?>>Show 50</option>
            <option value="100" <?php selected($per_page, 100); ?>>Show 100</option>
        </select>
        <?php
    }
}
add_action('restrict_manage_posts', 'property_items_per_page_dropdown');

function property_items_per_page_query($query) {
    global $pagenow;
    if (
        is_admin() &&
        $pagenow == 'edit.php' &&
        isset($_GET['post_type']) &&
        $_GET['post_type'] === 'property' &&
        isset($_GET['property_per_page']) &&
        is_numeric($_GET['property_per_page'])
    ) {
        $query->set('posts_per_page', intval($_GET['property_per_page']));
    }
}
add_action('pre_get_posts', 'property_items_per_page_query');

// =============================
// Admin Next/Prev Pagination
// =============================
function property_admin_next_prev_pagination() {
    global $typenow, $wp_query, $pagenow;

    if ($typenow === 'property' && $pagenow === 'edit.php') {
        $current_page = max(1, isset($_GET['paged']) ? intval($_GET['paged']) : 1);
        $per_page = isset($_GET['property_per_page']) ? intval($_GET['property_per_page']) : 10;
        $total_items = $wp_query->found_posts;
        $total_pages = ceil($total_items / $per_page);

        $base_url = remove_query_arg(['paged'], $_SERVER['REQUEST_URI']);

        echo '<div style="margin:10px 0;">';
        if ($current_page > 1) {
            $prev_url = add_query_arg('paged', $current_page - 1, $base_url);
            echo '<a class="button" href="' . esc_url($prev_url) . '">&laquo; Previous</a> ';
        }
        if ($current_page < $total_pages) {
            $next_url = add_query_arg('paged', $current_page + 1, $base_url);
            echo '<a class="button" href="' . esc_url($next_url) . '">Next &raquo;</a>';
        }
        echo '</div>';
    }
}
add_action('manage_posts_extra_tablenav', 'property_admin_next_prev_pagination');
