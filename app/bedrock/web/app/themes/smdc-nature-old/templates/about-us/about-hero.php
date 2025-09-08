<?php if (have_rows('about_us_module')): ?>
    <?php while (have_rows('about_us_module')): the_row(); ?>
        <?php if (get_row_layout() == 'hero_section'): ?>
            <?php 
                $hero_banner = get_sub_field('hero_banner');
                $hero_title  = get_sub_field('hero_header');
            ?>
            <section class="hero-section">
                <div class="about-us-wrapper" style="background-image: url('<?php echo esc_url($hero_banner['url']); ?>');">
                    <h1 class="quote-title about-header">
                        <?php echo esc_html($hero_title); ?>
                    </h1>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
