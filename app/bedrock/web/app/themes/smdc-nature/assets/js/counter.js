document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".counter");

  const animateCounter = (counter, duration = 3000) => { // 3000ms = 3s
    const target = +counter.getAttribute("data-target");
    const startTime = performance.now();

    const update = (currentTime) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1); // 0 → 1
      const value = Math.floor(progress * target);

      counter.innerText = value;

      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        counter.innerText = target; // ensure final value
      }
    };

    requestAnimationFrame(update);
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target); 
        observer.unobserve(entry.target); // run only once
      }
    });
  }, { threshold: 0.5 });

  counters.forEach(counter => observer.observe(counter));
});