<?php
/* Template Name: Contact Page */
get_header(); ?>


  <section>
    <?php if (have_rows('contact__module')): ?>
      <?php while (have_rows('contact__module')): the_row(); ?>
        <?php if (get_row_layout() == 'contact_hero'): ?>
          <?php 
            $hero_banner = get_sub_field('contact_banner'); 
            $banner_url = $hero_banner ? esc_url($hero_banner['url']) : esc_url(get_stylesheet_directory_uri() . "/images/default-banner.jpg");
            $banner_alt = $hero_banner && !empty($hero_banner['alt']) ? esc_attr($hero_banner['alt']) : 'Contact banner';
          ?>
          
          <div class="property-archive">
            <div id="region-banner" class="region-banner" style="background-image:url('<?php echo $banner_url; ?>')" role="img" aria-label="<?php echo $banner_alt; ?>">
            </div>  
          </div>

          <div class="contact-hero">
            <h2 class="quote-title contact-header">
              <span class="highlight quote-title">
                <?php the_sub_field('highlight_text'); ?>
              </span> 
              <?php the_sub_field('form_title'); ?>
            </h2>
            <p><?php the_sub_field('form_blurb'); ?></p>
          </div>
          
        <?php endif; ?>
      <?php endwhile; ?>
    <?php endif; ?>
  </section>

  <section class="contact-wrapper-page">
    <div class="contact-grid">
      
      <!-- Left Column -->
     <?php if (have_rows('contact__module')): ?>
      <?php while (have_rows('contact__module')): the_row(); ?>
        
        <?php if (get_row_layout() === 'form_section'): ?>
          <div class="contact-info">

            <!-- Contact Details -->
            <?php if (have_rows('contact_deatils')): ?>
              <div class="contact-details">
                <?php while (have_rows('contact_deatils')): the_row(); ?>
                  <div class="contact-block">
                    <?php if ($header = get_sub_field('contact_header')): ?>
                      <h3><?php echo esc_html($header); ?></h3>
                    <?php endif; ?>

                    <?php if (have_rows('contact_list')): ?>
                      <ul>
                        <?php while (have_rows('contact_list')): the_row(); ?>
                          <?php 
                            $icon = get_sub_field('icon');
                            $text = get_sub_field('contact');
                          ?>
                          <?php if (!empty($icon) || !empty($text)): ?>
                            <li>
                              <?php if (!empty($icon['url'])): ?>
                                <img 
                                  src="<?php echo esc_url($icon['url']); ?>" 
                                  alt="<?php echo esc_attr($icon['alt'] ?? ''); ?>">
                              <?php endif; ?>

                              <?php if (!empty($text)): ?>
                                <?php echo esc_html($text); ?>
                              <?php endif; ?>
                            </li>
                          <?php endif; ?>
                        <?php endwhile; ?>
                      </ul>
                    <?php endif; ?>
                  </div>
                <?php endwhile; ?>
              </div>
            <?php endif; ?>

            <!-- Social Links -->
            <?php $social_group = get_sub_field('social_link'); ?>
            <?php if (!empty($social_group)): ?>
              <div class="social-links">
                <?php if (!empty($social_group['header_social'])): ?>
                  <h3><?php echo esc_html($social_group['header_social']); ?></h3>
                <?php endif; ?>

                <?php if (!empty($social_group['social_list'])): ?>
                  <ul class="social-list">
                    <?php foreach ($social_group['social_list'] as $social): ?>
                      <?php 
                        $icon = !empty($social['social_icon']) ? $social['social_icon'] : null;
                        $url  = !empty($social['social_link']) ? $social['social_link'] : null;

                        // Normalize: Link field (array) OR URL field (string)
                        $href   = is_array($url) ? ($url['url'] ?? '')    : $url;
                        $target = is_array($url) && !empty($url['target']) ? $url['target'] : '_blank';
                        $title  = is_array($url) && !empty($url['title'])  ? $url['title']  : '';
                      ?>
                      <?php if (!empty($href)): ?>
                        <li>
                          <a href="<?php echo esc_url($href); ?>" target="<?php echo esc_attr($target); ?>" rel="noopener">
                            <?php if (!empty($icon['url'])): ?>
                              <img 
                                src="<?php echo esc_url($icon['url']); ?>" 
                                alt="<?php echo esc_attr($icon['alt'] ?? $title); ?>" 
                                class="social-icons"
                              >
                            <?php else: ?>
                              <?php echo esc_html($title ?: $href); ?>
                            <?php endif; ?>
                          </a>
                        </li>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </div>
            <?php endif; ?>

          </div>
        <?php endif; ?>

      <?php endwhile; ?>
    <?php endif; ?>
    
      <!-- Right Column -->
        <div class="quote-form-content">
            <h1 class="quote-form-title quote-title">
            Let’s Grow <span class="highlight quote-title">Together!</span>
            </h1>
            <p class="quote-form-subtitle">
            Get a custom quote for any of our properties at no cost!
            </p>
            <?php include get_template_directory() . '/templates/reusable-module/quote-form.php'; ?>
        </div>
      </div>
  </section>

  <!-- Map -->
  <section class="contact-map">
    <div id="map" style="width:1120px; height:450px; border-radius:20px;"></div>
  </section>

<?php get_footer(); ?>


<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
<script>
  function initMap() {
    var location = { lat: 14.5396434, lng: 120.9813301 };
    var map = new google.maps.Map(document.getElementById("map"), {
      zoom: 17,  // Adjust zoom level
      center: location,
      styles: [ // Custom map colors
        { elementType: "geometry", stylers: [{ color: "#ebe3cd" }] },
        { elementType: "labels.text.fill", stylers: [{ color: "#523735" }] },
        { elementType: "labels.text.stroke", stylers: [{ color: "#f5f1e6" }] },
        {
          featureType: "water",
          elementType: "geometry.fill",
          stylers: [{ color: "#c9c9c9" }]
        }
      ]
    });

    new google.maps.Marker({
      position: location,
      map: map,
      icon: "https://maps.google.com/mapfiles/ms/icons/orange-dot.png" // Custom marker
    });
  }

  window.onload = initMap;
</script>