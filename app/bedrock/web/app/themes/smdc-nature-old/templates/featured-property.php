<section class="featured-section">
    <?php if (have_rows('hero_module')): ?>
        <?php while (have_rows('hero_module')): the_row(); ?>
            <?php if (get_row_layout() == 'featured_property_section'): ?>
            <?php 
                $featured_title_highlight = get_sub_field('featured_property_title_highlight');
                $featured_property_title  = get_sub_field('featured_property_title');
                $featured_property_subheader = get_sub_field('featured_property_subheader');
            ?>
        <div class="featured-container">
            <div class="featured-header">
                <h1 class="quote-title">
                    <span class="quote-title highlight">
                        <?php echo esc_html($featured_title_highlight); ?>
                    </span> 
                    <?php echo esc_html($featured_property_title); ?>
                </h1>
                <p><?php echo esc_html($featured_property_subheader); ?></p>
            </div>
            <div class="property-wrapper">
                  <?php include get_template_directory() . '/templates/reusable-module/featured.php'; ?>
            </div>
        </div>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</section>