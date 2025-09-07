<?php
// =============================
// AJAX: Get Properties by Region
// =============================
add_action('wp_ajax_get_properties_by_region', 'get_properties_by_region');
add_action('wp_ajax_nopriv_get_properties_by_region', 'get_properties_by_region');

function get_properties_by_region() {
    $region = sanitize_text_field($_POST['region']);

    $query_args = array(
        'post_type'      => 'property',
        'posts_per_page' => -1,
    );

    if ($region && $region !== 'all') {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'property_region',
                'field'    => 'slug',
                'terms'    => $region,
            )
        );
    }

    $query = new WP_Query($query_args);

    ob_start();
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            include get_template_directory() . '/templates/reusable-module/property-card.php';
        endwhile;
    else :
        echo '<p>No properties found in this region.</p>';
    endif;
    wp_reset_postdata();

    $properties_html = ob_get_clean();

    // === Get region banner from ACF ===
    $banner = '';
    if ($region && $region !== 'all') {
        $term = get_term_by('slug', $region, 'property_region');
        if ($term) {
            $banner = get_field('region_banner', 'property_region_' . $term->term_id);
            if (is_array($banner) && isset($banner['url'])) {
                $banner = $banner['url'];
            }
        }
    }

    if (!$banner) {
        $banner = get_stylesheet_directory_uri() . '/assets/images/default-banner.jpg';
    }

    wp_send_json(array(
        'banner' => $banner,
        'html'   => $properties_html,
    ));
}

// =============================
// Enqueue JS
// =============================
function my_enqueue_property_scripts() {
    // Only load on property archive pages
    if ( is_post_type_archive('property') ) {
        wp_enqueue_script(
            'property-ajax',
            get_template_directory_uri() . '/assets/js/property-ajax.js',
            array(), // dependencies, e.g. ['jquery']
            null,
            true // load in footer
        );

        // Pass AJAX URL to JS
        wp_localize_script('property-ajax', 'propertyAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'my_enqueue_property_scripts');

