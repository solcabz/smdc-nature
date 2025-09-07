<?php

// Create the social_links table if it doesn't exist
function create_social_links_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'social_links';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        link varchar(255) NOT NULL,
        name varchar(100) NOT NULL,
        img varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'create_social_links_table');

// Add the settings menu for Social Links
function custom_theme_add_social_menu() {
    add_menu_page(
        'Social Media Links', 
        'Social Links', 
        'manage_options', 
        'custom-social-links', 
        'custom_theme_social_links_page', 
        'dashicons-welcome-write-blog',
        30
    );
}
add_action('admin_menu', 'custom_theme_add_social_menu');

// Handle file upload and return URL
function handle_social_icon_upload_v2($file) {
    if (!empty($file['name'])) {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        $upload = wp_handle_upload($file, ['test_form' => false]);
        if (!isset($upload['error']) && isset($upload['url'])) {
            return esc_url_raw($upload['url']);
        }
    }
    return '';
}

// Handle form submission
if (isset($_POST['add_social']) && check_admin_referer('add_social_action', 'add_social_nonce')) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'social_links';
    $link = esc_url_raw($_POST['social_link']);
    $name = sanitize_text_field($_POST['social_name']);
    $img = handle_social_icon_upload_v2($_FILES['social_image']);
    if ($link && $name) {
        $wpdb->insert(
            $table_name,
            [
                'link' => $link,
                'name' => $name,
                'img'  => $img,
            ]
        );
    }
}

// Handle delete
if (isset($_GET['delete_social']) && isset($_GET['social_id']) && check_admin_referer('delete_social_action_' . intval($_GET['social_id']))) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'social_links';
    $id = intval($_GET['social_id']);
    $wpdb->delete($table_name, ['id' => $id]);
}

// Settings page
function custom_theme_social_links_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'social_links';
    $socials = $wpdb->get_results("SELECT * FROM $table_name");
    ?>
    <style>
        .widefat.fixed th, .widefat.fixed td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
    <div class="wrap">
        <h1>Social Media Links</h1>
        <form method="post" enctype="multipart/form-data" style="display:flex;gap:10px;align-items:center;">
            <?php wp_nonce_field('add_social_action', 'add_social_nonce'); ?>
            <input type="url" name="social_link" placeholder="Input Social link" required style="min-width:180px;">
            <input type="text" name="social_name" placeholder="Social name" required style="min-width:120px;">
            <input type="file" name="social_image" accept="image/*" style="min-width:120px;">
            <input type="submit" name="add_social" value="Add Social" class="button">
        </form>
        <br>
        <table class="widefat fixed" style="width:100%;max-width:900px;">
            <thead>
                <tr>
                    <th style="background:#c88;">Social Links</th>
                    <th style="background:#c88;">Social Name</th>
                    <th style="background:#c88;">Social Image</th>
                    <th style="background:#c88;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($socials)) : foreach ($socials as $social) : ?>
                    <tr>
                        <td><a href="<?php echo esc_url($social->link); ?>" target="_blank"><?php echo esc_html($social->link); ?></a></td>
                        <td><?php echo esc_html($social->name); ?></td>
                        <td>
                            <?php if (!empty($social->img)) : ?>
                                <img src="<?php echo esc_url($social->img); ?>" style="max-width:50px;height:auto;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=custom-social-links&delete_social=1&social_id=' . $social->id), 'delete_social_action_' . $social->id); ?>" class="button button-small" onclick="return confirm('Delete this social?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4">No socials added yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}