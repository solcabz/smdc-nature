document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.featured-swiper', {
        slidesPerView: 4,
        centeredSlides: false,  // fixed left
        loop: true,
        spaceBetween: 24,
        speed: 600,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        on: {
            init(sw) {
                sw.slides.forEach(slide => slide.classList.add('inactive-slide'));
                sw.slides[sw.activeIndex].classList.remove('inactive-slide');
                sw.slides[sw.activeIndex].classList.add('active-slide');
                sw.update();
            },
            slideChangeTransitionEnd(sw) {
                // Reset all
                sw.slides.forEach(slide => {
                    slide.classList.remove('active-slide');
                    slide.classList.add('inactive-slide');
                });

                // Mark new active slide
                const active = sw.slides[sw.activeIndex];
                active.classList.remove('inactive-slide');
                active.classList.add('active-slide');

                // Force realign active to left
                sw.slideTo(sw.activeIndex, 0, false);
                sw.update();
            }
        }
    });
});
