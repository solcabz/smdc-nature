<?php
// ---------------------------
// Settings → Enable Subscriptions
// ---------------------------
function good_life_subscription_setting_init() {
    add_settings_field(
        'good_life_enable_subscriptions',
        'Enable Good Life Subscriptions',
        'good_life_subscription_setting_field',
        'reading',
        'default'
    );

    register_setting('reading', 'good_life_enable_subscriptions', array(
        'type'              => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
        'default'           => true,
    ));
}
add_action('admin_init', 'good_life_subscription_setting_init');

function good_life_subscription_setting_field() {
    $value = get_option('good_life_enable_subscriptions', true);
    echo '<label><input type="checkbox" name="good_life_enable_subscriptions" value="1" ' . checked(1, $value, false) . '> Allow users to subscribe to "Good Life" updates.</label>';
}

// ---------------------------
// Settings → Notify Subscribers
// ---------------------------
function good_life_notification_setting_init() {
    add_settings_field(
        'good_life_notify_subscribers',
        'Notify Subscribers on New Good Life Post',
        'good_life_notify_setting_field',
        'reading',
        'default'
    );

    register_setting('reading', 'good_life_notify_subscribers', array(
        'type'              => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
        'default'           => false,
    ));
}
add_action('admin_init', 'good_life_notification_setting_init');

function good_life_notify_setting_field() {
    $value = get_option('good_life_notify_subscribers', false);
    echo '<label><input type="checkbox" name="good_life_notify_subscribers" value="1" ' . checked(1, $value, false) . '> Enable email notifications to subscribers when a new "Good Life" article is published.</label>';
}

// ---------------------------
// Send email notifications
// ---------------------------
function good_life_send_notifications($ID) {
    $post = get_post($ID);
    if (!$post || $post->post_type !== 'good_life') return;
    if ($post->post_status !== 'publish') return;

    // ✅ Optional: Only on first publish
    if ($post->post_date !== $post->post_modified) {
        error_log("⏭ Skipping notification for post $ID (not first publish)");
        return;
    }

    // Check both toggles
    $subscriptions_enabled = get_option('good_life_enable_subscriptions', true);
    $notify                = get_option('good_life_notify_subscribers', false);

    if (!$subscriptions_enabled) {
        error_log("⚠ Subscriptions are disabled. No emails will be sent.");
        return;
    }
    if (!$notify) {
        error_log("⚠ Notifications are disabled in settings.");
        return;
    }

    global $wpdb;
    $table = $wpdb->prefix . 'subscribers';
    $subscribers = $wpdb->get_col("SELECT email FROM $table");

    if (empty($subscribers)) {
        error_log("⚠ No subscribers found in $table");
        return;
    }

    $subject = '📰 New Good Life Article: ' . get_the_title($ID);
    $link    = get_permalink($ID);
    $message = "Hello,\n\nA new Good Life article has been published:\n\n" .
               get_the_title($ID) . "\n" .
               $link . "\n\n" .
               "Thank you for subscribing!\n\n" . get_bloginfo('name');

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: SMDC Nature <no-reply@smdevelopment.com>'
    ];

    foreach ($subscribers as $email) {
        $sent = wp_mail($email, $subject, $message, $headers);
        error_log("📧 Sending Good Life email to: $email | Status: " . ($sent ? 'OK' : 'FAILED'));
    }
}
add_action('publish_good_life', 'good_life_send_notifications', 10, 1);