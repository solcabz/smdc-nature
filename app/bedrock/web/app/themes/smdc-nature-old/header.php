<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>

<body>
<header>
  <input type="checkbox" id="menu-toggle" />
  <nav>
    <div class="logo">
      <?php
      $header_img = get_theme_mod('header_image');
      if ($header_img) : ?>
          <a href="<?php echo home_url(); ?>">
            <img src="<?php echo esc_url($header_img); ?>" alt="Header Image">
          </a>
      <?php endif; ?>
    </div>

    <div class="menu-wrapper">
      <div class="hidden-mobile">
        <div class="search-container">
          <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="search-wrapper">
              <input type="search" class="search-field" placeholder="What are you looking for?" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
            </div>
          </form>
          <div class="search-results-dropdown" id="search-suggestions" style="display: none;">
            <div class="search-results-content"></div>
          </div>
        </div>
        <a class="btn-qoute" href="#">Get a Quote</a>
      </div>
      <label class="hamburger" for="menu-toggle">
        <span></span>
        <span></span>
        <span></span>
      </label>
    </div>
  </nav>

  <div class="menu-backdrop">
    <div class="nav-links">
      <!-- ✅ Close button -->
      <button class="close-menu" aria-label="Close Menu">&times;</button>

        <div class="menu-links">
          <?php
            $menu = wp_nav_menu([
              'theme_location' => 'primary',
              'container'      => false,
              'menu_class'     => 'link-lists',
              'fallback_cb'    => false,
              'echo'           => false
            ]);

            if ($menu) {
              // Force ul class
              $menu = preg_replace('/<ul(.*?)>/', '<ul class="link-lists"$1>', $menu, 1);

              // Decode HTML entities so &amp; or &#038; becomes &
              $menu = html_entity_decode($menu, ENT_QUOTES, 'UTF-8');

              // Now replace plain "&" with a span
              $menu = preg_replace('/\s&\s/', ' <span class="amp">&</span> ', $menu);
            } else {
              $menu = '<ul class="link-lists"><li><a href="' . esc_url(home_url('/')) . '">Home</a></li></ul>';
            }

            echo $menu;
          ?>
        </div>
        
      <div class="show-mobile">
        <div class="search-container">
          <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="search-wrapper">
              <input type="search" class="search-field" placeholder="What are you looking for?" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
            </div>
          </form>
          <div class="search-results-dropdown" id="search-suggestions" style="display: none;">
            <div class="search-results-content"></div>
          </div>
        </div>
        <a href="#">Get a Quote</a>
      </div>
    </div>
  </div>
</header>