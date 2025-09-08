<?php

function handle_quote_form() {
    if (!isset($_POST['submit_quote'])) return;

    // Nonce check
    if (!isset($_POST['quote_form_nonce']) || 
        !wp_verify_nonce($_POST['quote_form_nonce'], 'submit_quote_form')) {
        add_action('wp_footer', function() {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('quote-message').innerText = '❌ Security check failed.';
                    document.getElementById('quote-modal').style.display = 'flex';
                });
            </script>";
        });
        return;
    }

    // Consent check
    if (empty($_POST['consent'])) {
        add_action('wp_footer', function() {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('quote-message').innerText = '⚠️ You must agree to the data collection policy.';
                    document.getElementById('quote-modal').style.display = 'flex';
                });
            </script>";
        });
        return;
    }

    // Save to DB
    global $wpdb;
    $table = $wpdb->prefix . 'quotes';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = "CREATE TABLE $table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        property_of_interest varchar(255),
        first_name varchar(100),
        last_name varchar(100),
        number varchar(50),
        email varchar(100),
        country_of_residence varchar(100),
        quote_form_title varchar(255),
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) " . $wpdb->get_charset_collate() . ";";
    dbDelta($sql);

    $inserted = $wpdb->insert(
        $table,
        array(
            'property_of_interest' => sanitize_text_field($_POST['property_of_interest']),
            'first_name'           => sanitize_text_field($_POST['first_name']),
            'last_name'            => sanitize_text_field($_POST['last_name']),
            'number'               => sanitize_text_field($_POST['number']),
            'email'                => sanitize_email($_POST['email']),
            'country_of_residence' => sanitize_text_field($_POST['country_of_residence']),
            'quote_form_title'     => sanitize_text_field($_POST['quote_form_title']),
        )
    );

    if ($inserted) {
        // -------------------------
        // 1. Email to Admin
        // -------------------------
        $to_admin = get_option('admin_email');
        $subject_admin = 'New Quote Request Submitted';
        $message_admin = "A new quote request has been submitted:\n\n" .
            "Property of Interest: " . sanitize_text_field($_POST['property_of_interest']) . "\n" .
            "Name: " . sanitize_text_field($_POST['first_name']) . " " . sanitize_text_field($_POST['last_name']) . "\n" .
            "Phone: " . sanitize_text_field($_POST['number']) . "\n" .
            "Email: " . sanitize_email($_POST['email']) . "\n" .
            "Country of Residence: " . sanitize_text_field($_POST['country_of_residence']) . "\n" .
            "Form Title: " . sanitize_text_field($_POST['quote_form_title']) . "\n";

        wp_mail($to_admin, $subject_admin, $message_admin);

        // -------------------------
        // 2. Confirmation Email to Customer
        // -------------------------
        $customer_email = sanitize_email($_POST['email']);
        if (!empty($customer_email)) {
            $subject_customer = "Thank you for your quote request";
            $message_customer = "Hello " . sanitize_text_field($_POST['first_name']) . ",\n\n" .
                "Thank you for reaching out! We’ve received your request for a quote regarding:\n\n" .
                "Property of Interest: " . sanitize_text_field($_POST['property_of_interest']) . "\n\n" .
                "Our team will review your details and get back to you soon.\n\n" .
                "Best regards,\n" .
                get_bloginfo('name');

            $headers = ['Content-Type: text/plain; charset=UTF-8'];

            wp_mail($customer_email, $subject_customer, $message_customer, $headers);
        }
    }

    // Success modal
    add_action('wp_footer', function() {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('quote-message').innerText = '✅ Your quote has been submitted successfully!';
                document.getElementById('quote-modal').style.display = 'flex';
            });
        </script>";
    });
}
add_action('template_redirect', 'handle_quote_form');