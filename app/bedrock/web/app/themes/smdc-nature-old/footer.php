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
                    <form action="" >
                        <input type="email" id="news-subscription" name="news-subscription" placeholder="Enter your email address" autocomplete="email" >
                        <button>SUBMIT</button>
                    </form> 
                    <p><?php echo esc_html(get_option('footer_newsletter')); ?></p>
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

</body>
<?php wp_footer(); ?>
</html>

