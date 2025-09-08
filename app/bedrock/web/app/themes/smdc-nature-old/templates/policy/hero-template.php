<?php if (have_rows('privacy_page')): ?>
    <?php while (have_rows('privacy_page')): the_row(); ?>
        <?php if (get_row_layout() == 'privacy_hero'): ?>
            <?php 
                $hero_banner = get_sub_field('hero_banner');
                $privacy_highlight = get_sub_field('privacy_highlight');
                $privacy_title = get_sub_field('privacy_title');
                $privacy_blurb = get_sub_field('privacy_blurb');
            ?>
            <section>
                <div class="policy-wrapper">
                    <div class="policy-banner" style="background-image: url('<?php echo esc_url($hero_banner['url']); ?>');"></div>
                </div>
            </section>
            <section>
                 <div class="policy-header">
                        <h1 class="quote-title"><span class="highlight quote-title"><?php echo esc_html($privacy_highlight); ?></span> <?php echo esc_html($privacy_title); ?></h1>
                        <p><?php echo esc_html($privacy_blurb); ?></p>
                    </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
