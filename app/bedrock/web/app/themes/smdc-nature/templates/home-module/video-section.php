<section>
    <?php if (have_rows('hero_module')): ?>
        <?php while (have_rows('hero_module')): the_row(); ?>
            <?php if (get_row_layout() === 'avp_section'): ?>
                <?php 
                    $video_avp = get_sub_field('video_avp');
                    $header_avp  = get_sub_field('header_avp');
                    $second_header_avp  = get_sub_field('second_header_avp');
                    $sub_header  = get_sub_field('subheader');

                    $video_url = '';
                    if (is_array($video_avp) && isset($video_avp['url'])) {
                        $video_url = $video_avp['url'];
                    } elseif (is_numeric($video_avp)) {
                        $video_url = wp_get_attachment_url($video_avp);
                    } else {
                        $video_url = $video_avp;
                    }
                ?>
                <div class="video-wrapper">
                    <div class="video-overlay">
                        <h1 class="avp-title quote-title"><?php echo esc_html($header_avp); ?> <br><span class="highlight quote-title"> <?php echo esc_html($second_header_avp); ?></span></h1>
                        <p class="avp-sub-header"><?php echo esc_html($sub_header); ?></p>
                    </div>
                    <?php if ($video_url): ?>
                        <video autoplay muted loop playsinline preload="auto" class="background-video">
                            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</section>
