<?php if (have_rows('about_us_module')): ?>
    <?php while (have_rows('about_us_module')): the_row(); ?>
        <?php if (get_row_layout() == 'about_section'): ?>
            <?php 
                $highlight_header = get_sub_field('title_highlight');
                $header = get_sub_field('about_title');
                $about_blurb = get_sub_field('about_description');
                $family_image = get_sub_field('about_image');
                $peace_image = get_sub_field('about_image2');
                $about_blurb2 = get_sub_field('about_description2');
            ?>
            <section>
                <div class="boiller-wrapper">
                    <div class="bolller-column">
                        <h1 class="quote-title boiler-title"><span class="highlight quote-title"><?php echo esc_html($highlight_header); ?></span> <?php echo esc_html($header); ?></h1>
                        <p><?php echo wp_kses_post( nl2br( get_sub_field('about_description') ) ); ?></p>   <!-- check how can we get the space on the text area  -->
                        <img src="<?php echo esc_url($family_image['url']); ?>" alt="family">
                    </div>
                    <div class="bolller-column">
                        <img src="<?php echo esc_url($peace_image['url']); ?>" alt="family">
                        <p><?php echo wp_kses_post( nl2br( get_sub_field('about_description2') ) ); ?></p> <!-- check how can we get the space on the text area  -->
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
