<?php get_header(); ?>

<section>
    <div class="property-archive">
        <div id="region-banner" class="region-banner" style="background-image:url('<?php echo esc_url(get_stylesheet_directory_uri() . "/images/default-banner.jpg"); ?>')">
        </div>
    </div>

    <div class="property-archive-list">
        <!-- Default fallback banner -->
        <div class="overlay">
            <h1 class="quote-title">Communities that Grow with You</h1>
        </div>

        <div id="property-regions" class="region-tabs">
            <button class="region-button" data-region="metro-manila">Metro Manila</button>
            <button class="region-button" data-region="provincial-luzon">Provincial Luzon</button>
            <button class="region-button" data-region="visayas">Visayas</button>
            <button class="region-button" data-region="mindanao">Mindanao</button>
        </div>

        <div id="property-listings" class="property-grid">
            <!-- Properties will be loaded here by AJAX -->
        </div>
    </div>
</section>

<?php include get_template_directory() . '/templates/home-module/quote-section.php'; ?>

<?php get_footer(); ?>
