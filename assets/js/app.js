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
            },
            on: {
                slideChange: function () {
                    // Update active thumbnail
                    const realIndex = this.realIndex;
                    document.querySelectorAll('.thumbnail-item').forEach((thumb, i) => {
                        if (i === realIndex) {
                            thumb.classList.add('border-primary', 'border-2');
                        } else {
                            thumb.classList.remove('border-primary', 'border-2');
                        }
                    });
                }
            }
        });

        // Thumbnail click handler
        document.querySelectorAll('.thumbnail-item').forEach((thumb) => {
            thumb.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                swiper.slideToLoop(index);
            });
        });
    }

    // Wishlist Functionality
    initWishlist();

    // WhatsApp Auto-Popup
    initWaAutoPopup();
}

function initWaAutoPopup() {
    const popup = document.getElementById('waAutoPopup');
    const content = document.getElementById('waPopupContent');
    const closeBtn = document.getElementById('closeWaPopup');
    const waBtn = document.getElementById('waPopupBtn');

    if (!popup) return;

    let shouldShow = false;
    let delay = 7000;

    if (window.PRODUCT_DATA) {
        // Product page logic
        let shownProducts = JSON.parse(localStorage.getItem('wa_popup_products') || '[]');
        if (!shownProducts.includes(window.PRODUCT_DATA.id)) {
            shouldShow = true;
            
            // Customize popup text and button link for this product
            const titleEl = popup.querySelector('h3');
            
            if (titleEl) {
                // Keep the text concise if the name is too long
                const shortName = window.PRODUCT_DATA.name.length > 40 ? window.PRODUCT_DATA.name.substring(0, 40) + '...' : window.PRODUCT_DATA.name;
                titleEl.innerHTML = `Need Help with <br><span class="text-primary text-base font-bold">${shortName}</span>?`;
            }
            
            // Update WA URL
            const currentUrl = new URL(waBtn.href);
            currentUrl.searchParams.set('text', `Hi, I am interested in ${window.PRODUCT_DATA.name}. Can you help me with the best price and details?`);
            waBtn.href = currentUrl.toString();
            
            // Mark this product as shown
            shownProducts.push(window.PRODUCT_DATA.id);
            localStorage.setItem('wa_popup_products', JSON.stringify(shownProducts));
        }
    } else {
        // Generic page logic (show once per session)
        if (!sessionStorage.getItem('wa_popup_shown')) {
            shouldShow = true;
            sessionStorage.setItem('wa_popup_shown', 'true');
        }
    }

    if (shouldShow) {
        setTimeout(() => {
            popup.classList.remove('hidden');
            // Trigger reflow
            void popup.offsetWidth;
            popup.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, delay);
    }

    const closePopup = () => {
        popup.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            popup.classList.add('hidden');
        }, 300);
    };

    if (closeBtn) {
        closeBtn.onclick = (e) => {
            e.preventDefault();
            closePopup();
        };
    }

    if (waBtn) {
        waBtn.onclick = () => {
            closePopup();
        };
    }
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

