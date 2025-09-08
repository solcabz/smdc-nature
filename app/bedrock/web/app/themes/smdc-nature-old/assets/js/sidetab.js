document.addEventListener("DOMContentLoaded", function () {
  const wrapper = document.querySelector(".policy-template-wrapper");

  // ✅ Only run if the section exists on the page
  if (!wrapper) return;

  const navLinks = wrapper.querySelectorAll(".nav-link");
  const sections = wrapper.querySelectorAll(".privacy-section");

  // Function to activate a tab by its data-tab value
  function activateTab(tabKey) {
    navLinks.forEach(l => {
      const isActive = l.getAttribute("data-tab") === tabKey;
      l.classList.toggle("active", isActive);
    });

    sections.forEach(sec => {
      const isActive = sec.getAttribute("data-content") === tabKey;
      sec.classList.toggle("active", isActive);
    });
  }

  // Handle click on any nav link
  navLinks.forEach(link => {
    link.addEventListener("click", () => {
      const target = link.getAttribute("data-tab");
      activateTab(target);
    });
  });

  // ✅ On load, check for hash and trigger tab (e.g., #cookies)
  if (window.location.hash === "#cookies") {
    activateTab("tab-5"); // Replace with actual tab id for "Use of Cookies"
  }

  // ✅ Also listen for internal hash changes (in-page link clicks)
  window.addEventListener("hashchange", () => {
    if (window.location.hash === "#cookies") {
      activateTab("tab-5");
    }
  });
});