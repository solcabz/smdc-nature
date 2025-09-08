<?php if (have_rows('hero_content')): ?>
    <?php while (have_rows('hero_content')): the_row(); ?>

        <?php if (get_row_layout() == 'property_detail'): ?>
            <?php $detail = get_sub_field('detail'); ?>

            <section class="property-details">
                <div class="container">

                    <!-- Info Grid -->
                    <div class="info-grid">
                        <?php if (!empty($detail['location_detail'])): ?>
                            <div class="info-item">
                                <i class="icon">📍</i>
                                <p><?php echo esc_html($detail['location_detail']); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($detail['price_range'])): ?>
                            <div class="info-item">
                                <i class="icon">₱</i>
                                <p><?php echo esc_html($detail['price_range']); ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Units -->
                        <?php if (!empty($detail['units'])): ?>
                            <div class="features">
                                <i class="icon">🛏️</i>
                                <ul>
                                    <?php foreach ($detail['units'] as $unit): ?>
                                        <?php if (!empty($unit['unit_list'])): ?>
                                            <?php foreach ($unit['unit_list'] as $list): ?>
                                                <li><?php echo esc_html($list['list_units']); ?></li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Amenities -->
                        <?php if (!empty($detail['units'])): ?>
                            <div class="features">
                                <h4>Amenities:</h4>
                                <ul>
                                    <?php foreach ($detail['units'] as $unit): ?>
                                        <?php if (!empty($unit['ameneties'])): ?>
                                            <?php foreach ($unit['ameneties'] as $amenity): ?>
                                                <li><?php echo esc_html($amenity['list_ameneties']); ?></li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </section>
        <?php endif; ?>

    <?php endwhile; ?>
<?php endif; ?>
