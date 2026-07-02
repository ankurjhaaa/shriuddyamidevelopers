document.addEventListener('DOMContentLoaded', () => {
    // Initialize Product Gallery Swiper if it exists
    if (document.querySelector('.product-gallery')) {
        const swiper = new Swiper('.product-gallery', {
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            }
        });
    }
});
