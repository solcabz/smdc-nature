<?php if (have_rows('hero_content')): ?>
    <?php while (have_rows('hero_content')): the_row(); ?>
        <?php if (get_row_layout() == 'ameneties_section'): ?>
            <?php 
                $unit_title = get_sub_field('ameneties_title');
                $unit_highlight = get_sub_field('ameneties_highlight');
                $unit_description = get_sub_field('ameneties_description');
                $images = get_sub_field('image_gallery'); 
            ?>
            <section class="unit-section">
                <div class="unit-wrapper">
                    <div class="unit-header">
                        <h1 class="quote-title"><?php echo esc_html($unit_title); ?><span class="quote-title highlight"><?php echo esc_html($unit_highlight); ?></span></h1>
                        <p><?php echo esc_html($unit_description); ?></p>
                    </div>

                    <?php if ($images): ?>
                        <div class="swiper unit-swiper">
                            <div class="swiper-wrapper">
                                <?php foreach ($images as $image): ?>

                                    <?php
                                    // If $image is an integer, treat it as an attachment ID
                                    if (is_int($image)) {
                                        $full_img = wp_get_attachment_image_src($image, 'large');
                                        $thumb_img = wp_get_attachment_image_src($image, 'thumbnail');
                                        $alt = get_post_meta($image, '_wp_attachment_image_alt', true);
                                    } elseif (is_array($image)) {
                                        // If $image is already an array from ACF
                                        $full_img = [$image['url'], 0, 0];
                                        $thumb_img = [isset($image['sizes']['thumbnail']) ? $image['sizes']['thumbnail'] : $image['url'], 0, 0];
                                        $alt = $image['alt'];
                                    } else {
                                        continue; // skip invalid
                                    }
                                    ?>

                                    <div class="swiper-slide">
                                        <img class="property-image" src="<?php echo esc_url($full_img[0]); ?>" alt="<?php echo esc_attr($alt); ?>" />
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="swiper-footer-property">
                        <div class="swiper-nav">
                            <div class="swiper-button-prev">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M168.49,199.51a12,12,0,0,1-17,17l-80-80a12,12,0,0,1,0-17l80-80a12,12,0,0,1,17,17L97,128Z"></path></svg>
                            </div>
                            <div class="swiper-button-next">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>



