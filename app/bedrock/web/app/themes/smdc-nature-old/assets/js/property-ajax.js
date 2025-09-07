document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".region-button");
    const listingsContainer = document.getElementById("property-listings");
    if (!listingsContainer) return; // stop if not found

    function loadProperties(region) {
        listingsContainer.innerHTML = "<p>Loading...</p>";

        const formData = new FormData();
        formData.append("action", "get_properties_by_region");
        formData.append("region", region);

        fetch(propertyAjax.ajaxurl, {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            // === Update banner with fade ===
            const bannerEl = document.getElementById("region-banner");
            if (bannerEl) {
                bannerEl.classList.add("fade"); // fade out

                setTimeout(() => {
                    bannerEl.style.backgroundImage = data.banner
                        ? `url(${data.banner})`
                        : "url('/wp-content/themes/smdc-nature/images/default-banner.jpg')";
                    bannerEl.classList.remove("fade"); // fade back in
                }, 600);
            }

            // === Render PHP-generated HTML ===
            listingsContainer.innerHTML = data.html;
        });
    }

    // Event listeners
    buttons.forEach(button => {
        button.addEventListener("click", () => {
            buttons.forEach(b => b.classList.remove("active"));
            button.classList.add("active");

            const region = button.dataset.region;
            loadProperties(region);

            history.pushState({region}, "", `?region=${region}`);
        });
    });

    // Load initial region ("all" by default)
    const initialRegion = new URLSearchParams(window.location.search).get("region") || "metro-manila";
    document.querySelector(`[data-region="${initialRegion}"]`)?.classList.add("active");
    loadProperties(initialRegion);
});
