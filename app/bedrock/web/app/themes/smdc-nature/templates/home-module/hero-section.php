<?php if (have_rows('hero_module')): ?>
    <?php while (have_rows('hero_module')): the_row(); ?>
        <?php if (get_row_layout() == 'hero_section'): ?>
            <?php 
                $hero_banner = get_sub_field('hero_banner');
                $hero_title  = get_sub_field('hero_title');
            ?>
            <section class="hero-section" >
                <div class="kv-wrapper" style="background-image: url('<?php echo esc_url($hero_banner['url']); ?>');">
                    <h1 class="quote-title hero-title">
                        <?php echo esc_html($hero_title); ?>
                    </h1>
                    <a href="#">Discover More</a>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
