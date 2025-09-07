<?php
/* Template Name: Project Page */
get_header(); ?>

<main class="project-page">

  <?php if (have_rows('page_content')): ?>
    <?php while (have_rows('page_content')): the_row(); ?>

      <?php if (get_row_layout() == 'hero_section'): ?>
        <section class="hero">
            <div class="hero-text">
                <div class="article-header">
                    <h1 class="quote-title highlight"><?php the_sub_field('hero_title'); ?></h1>
                    <h1 class="quote-title"><?php the_sub_field('hero_subtitle'); ?></h1>
                </div>
                <div class="desc">
                    <p><?php the_sub_field('hero_description'); ?></p>
                </div>

                <div class="meta-wrapper">
                    <!-- ✅ Meta: Category + Date + Min Read -->
                    <div class="hero-meta">
                        <?php
                        $meta_parts = [];

                        // Category
                        $categories = get_the_terms(get_the_ID(), 'good_life_category');
                        if ($categories && !is_wp_error($categories)) {
                            $first_cat = $categories[0];
                            $meta_parts[] = '<span class="news-category highlight">
                                <a href="' . esc_url(get_term_link($first_cat)) . '">' . esc_html($first_cat->name) . '</a>
                            </span>';
                        }

                        // Date
                        $date = get_the_date();
                        if ($date) {
                            $meta_parts[] = '<span class="news-date">' . esc_html($date) . '</span>';
                        }

                        // Reading time (ACF field)
                        $min_to_read = get_sub_field('min_to_read');
                        if ($min_to_read) {
                            $meta_parts[] = '<span class="reading-time">' . esc_html($min_to_read) . ' min read</span>';
                        }

                        // Join with separator " • "
                        echo implode(' • ', $meta_parts);
                        ?>
                    </div>

                    <?php if (have_rows('hero_socials')): ?>
                        <ul class="social-icons">
                            <?php while (have_rows('hero_socials')): the_row(); ?>
                            <li><a href="<?php the_sub_field('link'); ?>">
                                <img src="<?php the_sub_field('icon'); ?>" alt="social"/>
                            </a></li>
                            <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
          <div class="hero-img">
            <img src="<?php the_sub_field('hero_image'); ?>" alt="">
          </div>
        </section>

      <?php elseif (get_row_layout() == 'image_text_section'): ?>
        <section class="image-text">
          <img src="<?php the_sub_field('section_image'); ?>" alt="">
          <div class="text">
            <h3><?php the_sub_field('section_title'); ?></h3>
            <?php the_sub_field('section_text'); ?>
          </div>
        </section>

      <?php elseif (get_row_layout() == 'two_images_section'): ?>
        <section class="two-images">
            <?php 
                $alignment = get_sub_field('text_alignment'); // left, center, right
                if (!$alignment) { $alignment = 'center'; } // default
            ?>
            <div class="two-text-wrapper <?php echo esc_attr($alignment); ?>">
                <h3><?php the_sub_field('section_title'); ?></h3>
                <?php the_sub_field('section_text'); ?>
            </div>
            <div class="images">
                <img src="<?php the_sub_field('image_left'); ?>" alt="">
                <img src="<?php the_sub_field('image_right'); ?>" alt="">
            </div>
        </section>

        <!-- split images -->
        <?php elseif (get_row_layout() == 'split_section'): ?>
            <section class="split <?php the_sub_field('split_alignment'); ?>">
            <?php if (get_sub_field('split_alignment') == 'left'): ?>
                <?php 
                    $alignment = get_sub_field('text_alignment'); // left, center, right
                    if (!$alignment) { $alignment = 'center'; } // default
                ?>
                <div class="split-img">
                    <img src="<?php the_sub_field('split_image'); ?>" alt="">
                </div>
                <div class="split-text <?php echo esc_attr($alignment); ?>">
                    <h3><?php the_sub_field('split_title'); ?></h3>
                    <?php the_sub_field('split_text'); ?>
                    </div>
            <?php else: ?>
                <?php 
                    $alignment = get_sub_field('text_alignment'); // left, center, right
                    if (!$alignment) { $alignment = 'center'; } // default
                ?>
                <div class="split-text <?php echo esc_attr($alignment); ?>">
                    <h3><?php the_sub_field('split_title'); ?></h3>
                    <?php the_sub_field('split_text'); ?>
                </div>
                <div class="split-img">
                    <img src="<?php the_sub_field('split_image'); ?>" alt="">
                </div>
            <?php endif; ?>
            </section>
                        

        <!-- text only -->
        <?php elseif (get_row_layout() == 'text_only_section'): ?>
            <?php 
                $alignment = get_sub_field('text_alignment'); // left, center, right
                if (!$alignment) { $alignment = 'center'; } // default
            ?>
            <section class="text-only <?php echo esc_attr($alignment); ?>">
                <h3><?php the_sub_field('text_title'); ?></h3>
                <?php the_sub_field('paragraph_text'); ?>
            </section>
        <?php endif; ?>

    <?php endwhile; ?>
  <?php endif; ?>

</main>

<?php get_footer(); ?>

