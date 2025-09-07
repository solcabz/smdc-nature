<?php
$args = [
    'post_type'      => 'property',
    'posts_per_page' => -1,
    'meta_query'     => [
        [
            'key'     => 'is_featured',
            'value'   => '1',
            'compare' => '=',
        ],
    ],
];

$query = new WP_Query($args);

if ($query->have_posts()): ?>
    <div class="swiper featured-swiper">

        <div class="swiper-wrapper">
            <?php while ($query->have_posts()): $query->the_post();
                $hero_content = get_field('hero_content');
                $featured_banner = null;
                $featured_logo = null;

                if ($hero_content) {
                    foreach ($hero_content as $block) {
                        if ($block['acf_fc_layout'] === 'property_hero' && !empty($block['property_banner'])) {
                            $featured_banner = $block['property_banner'];
                            $featured_logo = $block['property_logo'];
                            break;
                        }
                    }
                }
            ?>
                <div class="swiper-slide">
                    <?php if ($featured_banner): ?>
                        <img class="property-image" src="<?php echo esc_url($featured_banner['url']); ?>" alt="<?php echo esc_attr($featured_banner['alt']); ?>" />
                    <?php endif; ?>
                    <div class="floating-arrow-right-u">
                        <svg width="24" height="24" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.16675 13.8333L13.8334 2.16663M13.8334 2.16663H2.16675M13.8334 2.16663V13.8333" 
                                stroke="#F9FAF3" 
                                stroke-width="2.5" 
                                stroke-linecap="round" 
                                stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="bottom-float-left-d">
                        <?php if ($featured_logo): ?>
                            <img class="property-image" src="<?php echo esc_url($featured_logo['url']); ?>" alt="<?php echo esc_attr($featured_logo['alt']); ?>" style="width: 170px; height: 60;"/>
                        <?php endif; ?>
                    </div>

                    <?php
                        // --- Property Status (ACF field) ---
                        $status = get_field('property_status');
                        if ($status) :
                    ?>
                        <div class="bottom-float-right-d">
                            <p><?php echo esc_html($status); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="swiper-footer">
            <a href="/properties" class="view-all">
                View All Properties <span class="arrow">→</span>
            </a>
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
    <?php wp_reset_postdata(); ?>
<?php else: ?>
    <p class="no-properties">No featured properties found.</p>
<?php endif; ?>

