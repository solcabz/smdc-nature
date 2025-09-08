<?php if (have_rows('about_us_module')): ?>
    <?php while (have_rows('about_us_module')): the_row(); ?>
        <?php if (get_row_layout() == 'about_avp'): ?>
            <?php 
                $avp_blurb = get_sub_field('avp_description');
                $avp_blurb_highlight = get_sub_field('avp_description_highlight');
                $avp_video = get_sub_field('avp_file');

                 $avp_url = '';
                    if (is_array($avp_video) && isset($avp_video['url'])) {
                        $avp_url = $avp_video['url'];
                    } elseif (is_numeric($avp_video)) {
                        $avp_url = wp_get_attachment_url($avp_video);
                    } else {
                        $avp_url = $avp_video;
                    }
            ?>
            <section>
                <div class="avp-wrapper">
                    <div class="avp-header">
                        <h4 class="quote-title"><?php echo esc_html($avp_blurb); ?> <span class="highlight quote-title"><?php echo esc_html($avp_blurb_highlight); ?> </span></h4>
                    </div>
                    <div class="avp-vid">
                        <?php if ($avp_url): ?>
                            <video autoplay muted loop playsinline preload="auto" class="background-video">
                                <source src="<?php echo esc_url($avp_url); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
 