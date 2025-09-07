<?php get_header(); ?>

<main id="primary" class="site-main">
  <section>
        <div class="property-archive">
            <div id="region-banner" class="region-banner" style="background-image:url('<?php echo esc_url(get_stylesheet_directory_uri() . "/images/default-banner.jpg"); ?>')">
            </div>
        </div>
    </section>

    <section>
        <?php if (have_posts()) : ?>
            <div class="news-archive-list">
                <div class="good-life-archive">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('good-life-item'); ?>>
                            <?php
                            // Normalized container for hero data
                            $hero = array(
                                'subtitle'    => '',
                                'description' => '',
                                'image_url'   => '',
                                'image_alt'   => '',
                                'socials'     => array(), // each: ['icon_url' => '', 'link' => '']
                            );

                            // Helper: normalize an ACF image field (array | id | url) to [url, alt]
                            $normalize_image = function ($img, $size = 'large') {
                                $out = array('url' => '', 'alt' => '');
                                if (is_array($img)) {
                                $out['url'] = isset($img['sizes'][$size]) ? $img['sizes'][$size] : ($img['url'] ?? '');
                                $out['alt'] = $img['alt'] ?? '';
                                } elseif (is_numeric($img)) {
                                $out['url'] = wp_get_attachment_image_url((int)$img, $size) ?: '';
                                $out['alt'] = get_post_meta((int)$img, '_wp_attachment_image_alt', true) ?: '';
                                } elseif (is_string($img)) {
                                $out['url'] = $img;
                                }
                                return $out;
                            };

                            // Pull from ACF flexible content (if ACF is active)
                            if (function_exists('have_rows') && have_rows('page_content', get_the_ID())) :
                                while (have_rows('page_content', get_the_ID())) : the_row();
                                if (get_row_layout() === 'hero_section') {
                                    $hero['subtitle']    = (string) get_sub_field('hero_subtitle');
                                    $hero['description'] = (string) get_sub_field('hero_description');

                                    $imgNorm             = $normalize_image(get_sub_field('hero_image'), 'large');
                                    $hero['image_url']   = $imgNorm['url'];
                                    $hero['image_alt']   = $imgNorm['alt'];

                                    // Read the repeater *inside this hero_section row*
                                    if (have_rows('hero_socials')) {
                                    while (have_rows('hero_socials')) : the_row();
                                        $iconNorm = $normalize_image(get_sub_field('icon'), 'thumbnail');
                                        $link     = get_sub_field('link');
                                        if (!empty($link)) {
                                        $hero['socials'][] = array(
                                            'icon_url' => $iconNorm['url'],
                                            'link'     => $link,
                                        );
                                        }
                                    endwhile;
                                    }
                                    break; // use the first hero_section only
                                }
                                endwhile;
                            endif;
                            ?>
                                
                                    <div class="archive-card">
                                        <?php if (!empty($hero['image_url'])) : ?>
                                            <div class="good-life-banner">
                                                <a href="<?php the_permalink(); ?>">
                                                    <img src="<?php echo esc_url($hero['image_url']); ?>" alt="<?php echo esc_attr($hero['image_alt']); ?>" />
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-content">
                                            <div class="good-life-meta">
                                                <span class="date"><?php echo esc_html(get_the_date('F j, Y')); ?></span> |
                                                <span class="day date"><?php echo esc_html(get_the_date('l')); ?></span>
                                            </div>

                                            <a href="<?php the_permalink(); ?>">
                                                <h3>
                                                    <?php the_title(); ?> 
                                                </h3>
                                            </a>

                                            <?php if (!empty($hero['description'])) : ?>
                                                <div class="good-life-description">
                                                    <p>
                                                        <?php
                                                            echo wp_kses_post(
                                                                wp_trim_words($hero['description'], 30, '... <a href="' . esc_url(get_permalink()) . '">Read More</a>')
                                                            );
                                                        ?>
                                                    </p>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($hero['socials'])) : ?>
                                                <div class="good-life-social">
                                                <ul class="social-icons">
                                                    <?php foreach ($hero['socials'] as $s) :
                                                    if (empty($s['link'])) continue; ?>
                                                    <li>
                                                        <a href="<?php echo esc_url($s['link']); ?>" target="_blank" rel="noopener">
                                                        <?php if (!empty($s['icon_url'])) : ?>
                                                            <img src="<?php echo esc_url($s['icon_url']); ?>" alt="">
                                                        <?php endif; ?>
                                                        </a>
                                                    </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>         
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="pagination">
                <?php the_posts_pagination(array(
                    'prev_text' => '« Previous',
                    'next_text' => 'Next »',
                )); ?>
                </div>
            </div>
        <?php else : ?>
            <p>No Good Life items found.</p>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
