function initApp() {
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

    // Wishlist Functionality
    initWishlist();
}

document.addEventListener('turbo:load', initApp);
if (document.readyState !== 'loading') {
    initApp();
} else {
    document.addEventListener('DOMContentLoaded', initApp);
}

function initWishlist() {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    
    // Update all wishlist buttons on page load
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        const productId = btn.getAttribute('data-id');
        const icon = btn.querySelector('i');
        
        if (wishlist.includes(productId)) {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid', 'text-red-500');
            btn.classList.add('text-red-500');
        }
        
        btn.onclick = (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleWishlist(productId, btn);
        };
    });
}

function toggleWishlist(productId, btnElement) {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const icon = btnElement.querySelector('i');
    
    if (wishlist.includes(productId)) {
        // Remove from wishlist
        wishlist = wishlist.filter(id => id !== productId);
        icon.classList.remove('fa-solid', 'text-red-500');
        icon.classList.add('fa-regular');
        btnElement.classList.remove('text-red-500');
    } else {
        // Add to wishlist
        wishlist.push(productId);
        icon.classList.remove('fa-regular');
        icon.classList.add('fa-solid', 'text-red-500');
        btnElement.classList.add('text-red-500');
    }
    
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
}

