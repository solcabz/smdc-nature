<?php if (have_rows('privacy_page')) : ?>
    <section class="policy-template-wrapper">
        <div class="content-wrapper">
            <!-- Navigation -->
            <aside class="privacy-nav"> <!-- ✅ Semantic tag -->
                <ul>
                    <?php
                    $nav_index = 0;
                    while (have_rows('privacy_page')) : the_row();
                        if (get_row_layout() === 'privacy_content') :
                            if (have_rows('policy_list')) :
                                while (have_rows('policy_list')) : the_row();
                                    $title = get_sub_field('policy_title');
                    ?>
                                    <li>
                                        <a href="javascript:void(0);"
                                           class="nav-link <?php echo $nav_index === 0 ? 'active' : ''; ?>"
                                           data-tab="tab-<?php echo $nav_index; ?>">
                                            <?php echo esc_html($title); ?>
                                        </a>
                                    </li>
                    <?php
                                    $nav_index++;
                                endwhile;
                            endif;
                        endif;
                    endwhile;
                    ?>
                </ul>
            </aside>

            <!-- Tabbed Content -->
            <div class="privacy-content"> <!-- ✅ Semantic tag -->
                <?php
                $section_index = 0;
                while (have_rows('privacy_page')) : the_row();
                    if (get_row_layout() === 'privacy_content') :
                        if (have_rows('policy_list')) :
                            while (have_rows('policy_list')) : the_row();
                                $title = get_sub_field('policy_title');
                                $article = get_sub_field('policy_article');
                ?>
                                <div class="privacy-section <?php echo $section_index == 0 ? 'active' : ''; ?>"
                                     data-content="tab-<?php echo $section_index; ?>">
                                    <h2><?php echo esc_html($title); ?></h2>
                                    <div class="section-body">
                                        <?php echo wp_kses_post($article); ?> <!-- ✅ Proper WYSIWYG output -->
                                    </div>
                                </div>
                <?php
                                $section_index++;
                            endwhile;
                        endif;
                    endif;
                endwhile;
                ?>
            </div>
        </div>
    </section>
<?php else : ?>
    <p>No rows found in privacy_page</p>
<?php endif; ?>

