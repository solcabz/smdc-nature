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

    // reCAPTCHA v3 check
    $recaptcha_secret = getenv('RECAPTCHA_SECRET_KEY');
    $response = wp_remote_post(
        "https://www.google.com/recaptcha/api/siteverify",
        array(
            'body' => array(
                'secret' => $recaptcha_secret,
                'response' => sanitize_text_field($_POST['g-recaptcha-response']),
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        )
    );

    $response_body = json_decode(wp_remote_retrieve_body($response));
    if (
        !$response_body->success ||
        $response_body->score < 0.5 || // adjust threshold as needed
        $response_body->action !== 'submit'
    ) {
        add_action('wp_footer', function() {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('quote-message').innerText = '❌ Captcha verification failed.';
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

    $wpdb->insert(
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
