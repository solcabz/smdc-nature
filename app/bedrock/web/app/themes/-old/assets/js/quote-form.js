document.addEventListener("DOMContentLoaded", function () {
  let modal = document.getElementById("quote-modal");
  let closeBtn = document.getElementById("close-quote-modal");
  let closeX = document.getElementById("quote-close");

  // Show modal (you can trigger this when needed)
  function openModal() {
    modal.classList.add("show");
  }

  // Close modal
  function closeModal() {
    modal.classList.remove("show");
    modal.style.display = "none";
  }

  closeBtn.addEventListener("click", closeModal);

  window.addEventListener("click", function (e) {
    if (e.target === modal) closeModal();
  });

  // Example trigger (for testing only, remove later)
  // openModal();
});