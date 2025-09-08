<?php
/**
 * Plugin Name: Quotes Email Export
 * Description: Automatically exports quotes to CSV and emails them to configured recipients.
 * Version: 1.2
 * Author: Sol Cabreza
 */

if (!defined('ABSPATH')) exit;

// === 1. Register Settings Page ===
add_action('admin_menu', function() {
    add_options_page(
        'Quotes Email Settings',
        'Quotes Email Settings',
        'manage_options',
        'quotes-email-settings',
        'quotes_email_settings_page'
    );
});

add_action('admin_init', function() {
    register_setting('quotes_email_settings', 'quotes_email_recipients');
    register_setting('quotes_email_settings', 'quotes_email_subject');
    register_setting('quotes_email_settings', 'quotes_email_message');
    register_setting('quotes_email_settings', 'quotes_email_frequency'); 
    register_setting('quotes_email_settings', 'quotes_email_range');
});

// Settings page UI
function quotes_email_settings_page() { ?>
    <div class="wrap">
        <h1>Quotes Email Export Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('quotes_email_settings'); ?>
            <?php do_settings_sections('quotes_email_settings'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Recipients</th>
                    <td>
                        <input type="text" name="quotes_email_recipients" value="<?php echo esc_attr(get_option('quotes_email_recipients', '')); ?>" size="50" />
                        <p class="description">Enter multiple emails separated by commas.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Subject</th>
                    <td><input type="text" name="quotes_email_subject" value="<?php echo esc_attr(get_option('quotes_email_subject', 'Daily Quotes Export')); ?>" size="50" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Message</th>
                    <td>
                        <textarea name="quotes_email_message" rows="5" cols="50"><?php echo esc_textarea(get_option('quotes_email_message', 'Here is the latest quotes export attached.')); ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Frequency</th>
                    <td>
                        <select name="quotes_email_frequency">
                            <?php 
                            $current = get_option('quotes_email_frequency', 'daily');
                            foreach (['hourly' => 'Hourly', 'daily' => 'Daily', 'weekly' => 'Weekly'] as $value => $label) {
                                echo "<option value='$value' " . selected($current, $value, false) . ">$label</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Export Range</th>
                    <td>
                        <select name="quotes_email_range">
                            <?php 
                            $current_range = get_option('quotes_email_range', '24h');
                            $ranges = [
                                '24h' => 'Last 24 Hours',
                                '7d'  => 'Last 7 Days',
                                '30d' => 'Last 30 Days',
                                'all' => 'All Time'
                            ];
                            foreach ($ranges as $value => $label) {
                                echo "<option value='$value' " . selected($current_range, $value, false) . ">$label</option>";
                            }
                            ?>
                        </select>
                        <p class="description">Select which entries to include in the export.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>

        <hr>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <?php wp_nonce_field('quotes_email_send_now'); ?>
            <input type="hidden" name="action" value="quotes_email_send_now">
            <?php submit_button('📧 Send Now', 'secondary'); ?>
        </form>
    </div>
<?php }

// === 2. Core Worker Function ===
function quotes_send_export_email() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'quotes';

    // Determine date range
    $range = get_option('quotes_email_range', '24h');
    $where = "";
    if ($range === '24h') {
        $where = "WHERE created_at >= NOW() - INTERVAL 1 DAY";
    } elseif ($range === '7d') {
        $where = "WHERE created_at >= NOW() - INTERVAL 7 DAY";
    } elseif ($range === '30d') {
        $where = "WHERE created_at >= NOW() - INTERVAL 30 DAY";
    }

    $rows = $wpdb->get_results("SELECT * FROM $table_name $where ORDER BY created_at DESC", ARRAY_A);
    if (!$rows) return;

    // Create CSV
    $upload_dir = wp_upload_dir();
    $tmp_file = $upload_dir['basedir'] . "/quotes-export.csv";
    $output = fopen($tmp_file, 'w');
    fputcsv($output, ['Name','Email','Number','Property of Interest','Country','Quote Form Title','Created At']);
    foreach ($rows as $row) {
        fputcsv($output, [
            $row['first_name'] . ' ' . $row['last_name'],
            $row['email'],
            $row['number'],
            $row['property_of_interest'],
            $row['country_of_residence'],
            $row['quote_form_title'],
            $row['created_at']
        ]);
    }
    fclose($output);

    // Send email
    $recipients = explode(',', get_option('quotes_email_recipients', 'you@example.com'));
    $subject    = get_option('quotes_email_subject', 'Quotes Export');
    $message    = get_option('quotes_email_message', 'Here is the latest quotes export attached.');
    $headers    = ['Content-Type: text/html; charset=UTF-8'];

    foreach ($recipients as $recipient) {
        $recipient = trim($recipient);
        if (!empty($recipient)) {
            wp_mail($recipient, $subject, nl2br($message), $headers, [$tmp_file]);
        }
    }
}

// === 3. Cron Job ===
add_action('send_quotes_export_email', 'quotes_send_export_email');

register_activation_hook(__FILE__, function() {
    $freq = get_option('quotes_email_frequency', 'daily');
    if (!wp_next_scheduled('send_quotes_export_email')) {
        wp_schedule_event(time(), $freq, 'send_quotes_export_email');
    }
});

register_deactivation_hook(__FILE__, function() {
    wp_clear_scheduled_hook('send_quotes_export_email');
});

// Reschedule if frequency changed
add_action('update_option_quotes_email_frequency', function($old, $new) {
    wp_clear_scheduled_hook('send_quotes_export_email');
    wp_schedule_event(time(), $new, 'send_quotes_export_email');
}, 10, 2);

// === 4. Manual Send Now Button ===
add_action('admin_post_quotes_email_send_now', function() {
    if (!current_user_can('manage_options') || !check_admin_referer('quotes_email_send_now')) {
        wp_die('Unauthorized');
    }
    quotes_send_export_email(); // ✅ direct call instead of do_action
    wp_redirect(admin_url('options-general.php?page=quotes-email-settings&sent=1'));
    exit;
});

// === 5. Admin Notice for Email Sent ===
add_action('admin_notices', function() {
    if (isset($_GET['sent']) && $_GET['sent'] == 1) {
        echo '<div class="notice notice-success is-dismissible"><p>✅ Quotes export email has been sent successfully.</p></div>';
    }
});

// === 6. Test Email Utility ===
add_action('admin_init', function() {
    if (isset($_GET['test_mail'])) {
        wp_mail('your@email.com', 'Test WP Mail', 'If you see this, wp_mail() works!');
        wp_die('Test email sent. Check your inbox/spam.');
    }
});
