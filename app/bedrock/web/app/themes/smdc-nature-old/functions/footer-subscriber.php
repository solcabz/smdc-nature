<?php

/**
 * =============================
 *  Newsletter Subscribers
 * =============================
 */

/**
 * Ensure subscribers table exists
 */
function create_subscribers_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'subscribers';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL UNIQUE,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
add_action('after_switch_theme', 'create_subscribers_table');

/**
 * Handle newsletter subscription
 */
function handle_news_subscription() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_newsletter'])) {

        // ✅ Security nonce check
        if (!isset($_POST['newsletter_nonce']) || 
            !wp_verify_nonce($_POST['newsletter_nonce'], 'subscribe_newsletter')) {
            add_action('wp_footer', function() {
                echo "<script>showNewsletterModal('❌ Security check failed.');</script>";
            });
            return;
        }

        global $wpdb;
        $table = $wpdb->prefix . 'subscribers';
        $email = sanitize_email($_POST['news_subscription']);

        // ✅ Check table exists
        $check_table = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table));
        if ($check_table != $table) {
            create_subscribers_table(); // recreate if missing
        }

        // ✅ Prevent duplicates
        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE email = %s", $email));

        if ($exists) {
            add_action('wp_footer', function() {
                echo "<script>showNewsletterModal('⚠️ This email is already subscribed.');</script>";
            });
        } else {
            // ✅ Insert email
            $inserted = $wpdb->insert($table, ['email' => $email]);

            if ($inserted) {
                // Send confirmation email once
                wp_mail(
                    $email,
                    'Thank you for subscribing',
                    "Hello,\n\nThank you for subscribing to our newsletter! You'll now receive updates on new articles.\n\nRegards,\n" . get_bloginfo('name')
                );

                add_action('wp_footer', function() {
                    echo "<script>showNewsletterModal('✅ Subscription successful! Please check your email.');</script>";
                });
            } else {
                add_action('wp_footer', function() {
                    echo "<script>showNewsletterModal('❌ Failed to save subscription.');</script>";
                });
            }
        }
    }
}
add_action('init', 'handle_news_subscription'); // ✅ use init instead of template_redirect

// -------------------
// Step 3: Admin Page (Subscribers List)
// -------------------
add_action('admin_menu', function() {
    add_menu_page(
        'Subscribers',
        'Subscribers',
        'manage_options',
        'subscribers-list',
        'render_subscribers_page',
        'dashicons-email-alt',
        25
    );
});

function render_subscribers_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'subscribers';

    $items_per_page = 10;
    $current_page   = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset         = ($current_page - 1) * $items_per_page;

    $subscribers = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table ORDER BY created_at DESC LIMIT %d OFFSET %d", $items_per_page, $offset)
    );

    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table");
    $total_pages = ceil($total_items / $items_per_page);

    echo '<div class="wrap"><h1>Subscribers</h1>';
    echo '<table class="widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Email</th><th>Subscribed At</th></tr></thead><tbody>';

    if ($subscribers) {
        foreach ($subscribers as $subscriber) {
            echo '<tr>';
            echo '<td>' . esc_html($subscriber->id) . '</td>';
            echo '<td>' . esc_html($subscriber->email) . '</td>';
            echo '<td>' . esc_html($subscriber->created_at) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="3">No subscribers yet.</td></tr>';
    }

    echo '</tbody></table>';

    // Pagination
    if ($total_pages > 1) {
        echo '<div class="tablenav"><div class="tablenav-pages">';
        if ($current_page > 1) {
            echo '<a class="button" href="?page=subscribers-list&paged=' . ($current_page - 1) . '">&laquo; Prev</a> ';
        }
        if ($current_page < $total_pages) {
            echo '<a class="button" href="?page=subscribers-list&paged=' . ($current_page + 1) . '">Next &raquo;</a>';
        }
        echo '</div></div>';
    }

    echo '</div>';
}
