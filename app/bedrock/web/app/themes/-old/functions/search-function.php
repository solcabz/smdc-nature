<?php
// search function query
function smart_property_search_redirect() {
    if (is_search() && !is_admin() && isset($_GET['s']) && !empty($_GET['s'])) {
        $search_term = sanitize_text_field($_GET['s']);

        // Filter for partial title match
        $title_filter = function ($where, $query) use ($search_term) {
            global $wpdb;
            if ($query->get('smart_title_search')) {
                $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like($search_term) . '%');
            }
            return $where;
        };

        // Match Property (custom post type)
        add_filter('posts_where', $title_filter, 10, 2);
        $property_query = new WP_Query([
            'post_type' => 'property',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'smart_title_search' => true,
        ]);
        remove_filter('posts_where', $title_filter, 10);

        if ($property_query->have_posts()) {
            $property = $property_query->posts[0];
            wp_redirect(get_permalink($property->ID));
            exit;
        }

        // Match Property Segment (taxonomy)
        $segment = get_term_by('name', $search_term, 'property_segment');
        if ($segment && !is_wp_error($segment)) {
            wp_redirect(get_term_link($segment));
            exit;
        }

        // Match Page (default post type)
        add_filter('posts_where', $title_filter, 10, 2);
        $page_query = new WP_Query([
            'post_type' => 'page',
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'smart_title_search' => true,
        ]);
        remove_filter('posts_where', $title_filter, 10);

        if ($page_query->have_posts()) {
            $page = $page_query->posts[0];
            wp_redirect(get_permalink($page->ID));
            exit;
        }

        // Redirect to 404 if nothing matched
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        include get_404_template();
        exit;
    }
}
add_action('template_redirect', 'smart_property_search_redirect');

// search enter as the submit
function auto_submit_search_form() {
    ?>
    <script type="text/javascript">
        document.querySelector('.search-field').addEventListener('keypress', function(event) {  
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent default form submit
                this.form.submit(); // Submit the form manually
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'auto_submit_search_form'); // Hook to output JS before closing </body> tag

// <!-- auto suggestion for search  -->
add_action('wp_ajax_live_search_suggestions', 'handle_live_search_suggestions');
add_action('wp_ajax_nopriv_live_search_suggestions', 'handle_live_search_suggestions');

function handle_live_search_suggestions() {
    $term = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : '';
    if (empty($term)) wp_send_json([]);

    $query = new WP_Query([
        'post_type' => ['post', 'page', 'property', 'good_life'], // Add more CPTs if needed
        's' => $term,
        'posts_per_page' => 5,
        'post_status' => 'publish',
    ]);

    $results = [];

    foreach ($query->posts as $post) {
        $results[] = [
            'title' => get_the_title($post),
            'url'   => get_permalink($post),
            'thumbnail' => get_the_post_thumbnail_url($post, 'thumbnail') ?: '',
        ];
    }

    wp_send_json($results);
}
