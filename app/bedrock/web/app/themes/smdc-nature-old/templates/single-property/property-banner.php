<?php if (have_rows('hero_content')): ?>
    <?php while (have_rows('hero_content')): the_row(); ?>
        <?php if (get_row_layout() == 'property_hero'): ?>
            <?php 
                $hero_banner = get_sub_field('property_banner');
                $property_logo  = get_sub_field('property_logo');
            ?>
            <section class="single-hero">
                <div class="single-kv" style="background-image: url('<?php echo esc_url($hero_banner['url']); ?>');">
                  
                    <div class="icon-wrapper">
                        <img class="property-logo" 
                             src="<?php echo esc_url($property_logo['url']); ?>" 
                             alt="property-logo">

                        <?php
                            $status = get_field('property_status');
                            if ($status) :  
                        ?>
                        <div class="single-status">
                            <p><?php echo esc_html($status); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
