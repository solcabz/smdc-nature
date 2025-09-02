<section>
    <?php if (have_rows('hero_module')): ?>
    <?php while (have_rows('hero_module')): the_row(); ?>
        <?php if (get_row_layout() == 'map_section'): ?>
            <?php 
                $map_header = get_sub_field('map_header');
                $map_subheader  = get_sub_field('map_subheader');
                $counter_title = get_sub_field('counter_title');
                $map_counter = get_sub_field('map_counter');
                $detail1 = get_sub_field('detail1');
                $detail2 = get_sub_field('detail2');
            ?>
        <div class="map-wrapper">
            <div class="map-detail">
                <div class="map-text-top">
                    <h1 class="map-header quote-title"><?php echo esc_html($map_header); ?></h1>
                    <p class="map-subheader"><?php echo esc_html($map_subheader); ?></p>
                </div>
                <div class="map-text-bottom">
                    <p class="map-detail1"><?php echo esc_html($detail1); ?></p>
                    <h1 class="map-counter quote-title highlight">
                        <span class="counter quote-title" data-target="<?php echo esc_attr($map_counter); ?>">0</span> 
                        <?php echo esc_html($counter_title); ?>
                    </h1>
                    <p class="map-detail2"><?php echo esc_html($detail2); ?></p>
                </div>
            </div>
            <div class="map-image">
                
            </div>
        </div>
        <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</section>