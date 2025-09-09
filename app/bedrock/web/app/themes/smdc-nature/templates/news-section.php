<section>
    <div class="news-wrapper">
        <div class="news-header">
            <h1 class="quote-title">
                <?php echo esc_html(get_option('news_title')); ?> 
                <span class="quote-title highlight" style="font-style: italic; font-weight: 400;">
                    <?php echo esc_html(get_option('news_highlight')); ?>
                </span> 
                <?php echo esc_html(get_option('news_title2')); ?>
            </h1>
            <p><?php echo wp_kses_post(get_option('news_blurb')); ?></p>
        </div>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">

                <?php
                $goodlife_query = new WP_Query(array(
                    'post_type'      => 'good_life', 
                    'posts_per_page' => 8,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));

                if ($goodlife_query->have_posts()) :
                    while ($goodlife_query->have_posts()) : $goodlife_query->the_post();

                        // ✅ fetch hero_description from ACF flexible field
                        $hero_description = '';
                        if (function_exists('have_rows') && have_rows('page_content', get_the_ID())) {
                            while (have_rows('page_content', get_the_ID())) {
                                the_row();
                                if (get_row_layout() === 'hero_section') {
                                    $hero_description = (string) get_sub_field('hero_description');
                                    break; // only take first hero_section
                                }
                            }
                        }
                        ?>
                        
                        <div class="swiper-slide updates-swiper">
                            <div class="card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium_large', ['alt' => get_the_title()]); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-content">
                                    <p class="date"><?php echo esc_html(get_the_date('F j, Y')); ?></p>
                                    <a href="<?php the_permalink(); ?>">
                                        <h3><?php the_title(); ?></h3>
                                    </a>

                                    <?php if (!empty($hero_description)) : ?>
                                        <p>
                                            <?php echo wp_trim_words($hero_description, 15, '... <a href="' . esc_url(get_permalink()) . '">Read More</a>'); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>No Good Life updates found.</p>
                <?php endif; ?>

            </div>

            <div class="swiper-footer-news">
                <a href="<?php echo esc_url(get_post_type_archive_link('good_life')); ?>" class="view-all">
                    See All Updates <span class="arrow">→</span>
                </a>
                <div class="swiper-nav-news">
                    <div class="swiper-button-prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M168.49,199.51a12,12,0,0,1-17,17l-80-80a12,12,0,0,1,0-17l80-80a12,12,0,0,1,17,17L97,128Z"></path></svg>
                    </div>
                    <div class="swiper-button-next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000" viewBox="0 0 256 256"><path d="M184.49,136.49l-80,80a12,12,0,0,1-17-17L159,128,87.51,56.49a12,12,0,1,1,17-17l80,80A12,12,0,0,1,184.49,136.49Z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
