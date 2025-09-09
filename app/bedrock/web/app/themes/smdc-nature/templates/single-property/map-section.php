<?php if (have_rows('hero_content')): ?>
    <?php while (have_rows('hero_content')): the_row(); ?>
        <?php if (get_row_layout() == 'proximity_secttion'): ?>
            <section class="proximity-section">
                <div class="proximity-wrap">

                    <!-- Left side: Highlight, Title, Proximity Groups -->
                    <div class="prox-left">
                            <div class="prox-head">
                                <?php $map_highlight = get_sub_field('map_highlight'); ?>
                                <?php if ($map_highlight): ?>
                                    <h1 class="quote-title"><span class="highlight quote-title"><?php echo esc_html($map_highlight); ?></span></h1>
                                <?php endif; ?>

                                <?php $map_title = get_sub_field('map_title'); ?>
                                <?php if ($map_title): ?>
                                    <h1 class="quote-title"><?php echo esc_html($map_title); ?></h1>
                                <?php endif; ?>
                            </div>
                        
                            <div class="prox-list">
                                <?php if (have_rows('proximity_group')): ?>
                                    <?php while (have_rows('proximity_group')): the_row(); ?>
                                        <?php 
                                            $group_title = get_sub_field('proximity_title'); 
                                            $proximity_list = get_sub_field('proximity_list');
                                        ?>
                                        <div class="prx-detail">
                                            <?php if ($group_title): ?>
                                                <h4><?php echo esc_html($group_title); ?></h4>
                                            <?php endif; ?>

                                            <div class="prox-list-wrap">
                                                <?php if ($proximity_list): ?>
                                                    <ul>
                                                        <?php foreach ($proximity_list as $item): ?>
                                                            <li><?php echo esc_html($item['proximity_item']); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                    </div>

                    <!-- Right side: Map -->
                    <div class="">
                        <?php 
                            $property_map = get_sub_field('property_map'); 
                            $fallback_image = get_sub_field('proximity_image_map');
                        ?>
                        <?php if ($property_map): ?>
                            <iframe src="<?php echo esc_url($property_map); ?>" frameborder="0" allowfullscreen></iframe>
                        <?php elseif ($fallback_image): ?>
                            <img src="<?php echo esc_url($fallback_image['url']); ?>" alt="<?php echo esc_attr($fallback_image['alt']); ?>" />
                        <?php endif; ?>
                    </div>

                </div>
            </section>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
