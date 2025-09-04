document.addEventListener("DOMContentLoaded", () => {
  const toggle   = document.getElementById("menu-toggle");
  const closeBtn = document.querySelector(".close-menu");
  const backdrop = document.querySelector(".menu-backdrop");

  // Handle checkbox change
  toggle.addEventListener("change", () => {
    if (toggle.checked) {
      document.documentElement.classList.add("menu-open");
      document.body.classList.add("menu-open");
    } else {
      document.documentElement.classList.remove("menu-open");
      document.body.classList.remove("menu-open");
    }
  });

  // Close with the "X" button
  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      toggle.checked = false;
      toggle.dispatchEvent(new Event("change")); // trigger same logic
    });
  }

  // Close when clicking the backdrop (outside sidebar)
  if (backdrop) {
    backdrop.addEventListener("click", (e) => {
      // only if user clicks outside the sidebar itself
      if (e.target === backdrop) {
        toggle.checked = false;
        toggle.dispatchEvent(new Event("change"));
      }
    });
  }
});