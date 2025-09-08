<footer>
    <div class="footer-wrapper">
        <div class="footer-logo">
            <?php
            $footer_image = get_theme_mod('footer_image');
            if ($footer_image) : ?>
                <a href="<?php echo home_url(); ?>">
                    <img src="<?php echo esc_url($footer_image); ?>" alt="Footer Image">
                </a>
            <?php endif; ?>
            <div class="contact-wrapper">
                <p><?php echo esc_html(get_option('footer_phone_1')); ?></p>
                <p><?php echo esc_html(get_option('footer_phone_2')); ?></p>
                <a href="mailto:<?php echo esc_attr(get_option('footer_email')); ?>">
                    <?php echo esc_html(get_option('footer_email')); ?></p>
                </a>    
            </div>
            <div class="address-wrapper">
                <p><?php echo nl2br(esc_html(get_option('footer_address'))); ?></p>
            </div>
        </div>

        <div class="footer-links">
            <div class="news-container">
                <div class="news-form-wrapper">
                   <form method="POST" action="">
                        <?php wp_nonce_field('subscribe_newsletter', 'newsletter_nonce'); ?>
                        <input type="email" id="news-subscription" name="news_subscription" placeholder="Enter your email address" required>
                        <button type="submit" name="submit_newsletter">SUBMIT</button>
                    </form>
                    <p><?php echo esc_html(get_option('footer_newsletter')); ?></p>
                    <!-- Newsletter Modal -->
                    <div id="newsletter-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                        background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:9999;">
                        <div style="background:#fff; padding:20px; border-radius:8px; max-width:400px; text-align:center;">
                            <p id="newsletter-message"></p>
                            <button onclick="document.getElementById('newsletter-modal').style.display='none'">Close</button>
                        </div>
                    </div>
                </div>
                <div class="footer-menu"></div>
            </div>
            <div>
                <div class="social-menu">
                    <ul>
                        <?php
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'social_links';
                        $socials = $wpdb->get_results("SELECT * FROM $table_name");
                        if ($socials) :
                            foreach ($socials as $social) :
                                if (!empty($social->link)) :
                        ?>
                            <li>
                                <a href="<?php echo esc_url($social->link); ?>" target="_blank">
                                    <?php if (!empty($social->img)) : ?>
                                        <img src="<?php echo esc_url($social->img); ?>" alt="<?php echo esc_attr($social->name); ?> Icon" class="social-icon">
                                    <?php else : ?>
                                        <?php echo esc_html($social->name); ?>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
                <div class="copyright">
                    <p>
                        Copyright 
                        <?php echo date('Y'); ?> 
                        <a href="https://smdc.com" target="_blank" rel="noopener noreferrer">SMDC</a>, 
                        <?php echo esc_html(get_option('footer_copyright')); ?>
                    </p>
                </div>
            </div>
        </div>
        
    </div>
</footer>
<script>
    function showNewsletterModal(message) {
        document.getElementById('newsletter-message').innerText = message;
        document.getElementById('newsletter-modal').style.display = 'flex';
    }
</script>
</body>
<?php wp_footer(); ?>
</html>

