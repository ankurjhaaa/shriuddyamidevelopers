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
                            thumb.classList.add('border-primary', 'shadow-sm', 'scale-110');
                            thumb.classList.remove('border-gray-200', 'opacity-70', 'hover:opacity-100');
                        } else {
                            thumb.classList.remove('border-primary', 'shadow-sm', 'scale-110');
                            thumb.classList.add('border-gray-200', 'opacity-70', 'hover:opacity-100');
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
    
    // Product Image Lightbox
    initProductLightbox();
}

function initProductLightbox() {
    const lightbox = document.getElementById('productLightbox');
    if (!lightbox) return;

    const playlist = window.STORY_PLAYLIST;
    if (!playlist || playlist.length === 0) return;

    const mainImage = document.getElementById('lbMainImage');
    const closeBtn = document.getElementById('closeLightbox');
    const prevBtn = document.getElementById('lbPrevBtn');
    const nextBtn = document.getElementById('lbNextBtn');
    const imageCounter = document.getElementById('lbImageCounter');
    
    // Bottom elements
    const productNameEl = document.getElementById('lbProductName');
    const enquireBtn = document.getElementById('lbEnquireBtn');

    let currentProductIndex = 0;
    let currentImageIndex = 0;
    
    // Zoom state
    let currentScale = 1;
    let isDragging = false;
    let startX = 0, startY = 0;
    let translateX = 0, translateY = 0;

    const resetZoom = () => {
        currentScale = 1;
        translateX = 0;
        translateY = 0;
        isDragging = false;
        if (mainImage) {
            mainImage.style.transform = `translate(0px, 0px) scale(1)`;
            mainImage.style.cursor = 'zoom-in';
            mainImage.style.transition = 'transform 0.3s ease-out';
        }
    };
    
    const applyZoom = () => {
        if (mainImage) {
            mainImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentScale})`;
            if (currentScale > 1) {
                mainImage.style.cursor = 'grab';
            } else {
                mainImage.style.cursor = 'zoom-in';
            }
        }
    };

    const formatPrice = (price) => {
        return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(price);
    };

    const updateSidebar = () => {
        const product = playlist[currentProductIndex];
        if (productNameEl) productNameEl.textContent = product.name;
        
        if (imageCounter) {
            imageCounter.textContent = `${currentImageIndex + 1} / ${product.images.length}`;
        }

        if (enquireBtn) {
            const waUrl = new URL(enquireBtn.href);
            waUrl.searchParams.set('text', `Hi, I am interested in ${product.name}. Can you send me a quote?`);
            enquireBtn.href = waUrl.toString();
        }
    };
    
    const preloadNextImage = () => {
        const product = playlist[currentProductIndex];
        let nextProdIdx = currentProductIndex;
        let nextImgIdx = currentImageIndex + 1;
        
        if (nextImgIdx >= product.images.length) {
            return; // Preload stops at last image
        }
        
        const img = new Image();
        img.src = '/' + product.images[nextImgIdx];
    };

    const updateLightboxView = () => {
        const product = playlist[currentProductIndex];
        
        if (currentImageIndex < 0) {
            currentImageIndex = product.images.length - 1;
        }
        if (currentImageIndex >= product.images.length) {
            currentImageIndex = 0;
        }
        
        if (prevBtn) {
            prevBtn.style.opacity = (currentImageIndex === 0) ? '0.3' : '1';
            prevBtn.style.pointerEvents = (currentImageIndex === 0) ? 'none' : 'auto';
        }
        if (nextBtn) {
            const isLast = (currentImageIndex === product.images.length - 1);
            nextBtn.style.opacity = isLast ? '0.3' : '1';
            nextBtn.style.pointerEvents = isLast ? 'none' : 'auto';
        }
        
        updateSidebar();

        if (mainImage) {
            const newSrc = '/' + product.images[currentImageIndex];
            
            // Remove previous onload handler to prevent race conditions
            mainImage.onload = null;
            mainImage.onerror = null;
            
            const handleLoad = () => {
                resetZoom();
                preloadNextImage();
            };
            
            mainImage.onload = handleLoad;
            
            // In case the image fails to load
            mainImage.onerror = () => {
                resetZoom();
            };
            
            mainImage.src = newSrc;
            
            if (mainImage.complete && mainImage.naturalHeight !== 0) {
                handleLoad();
            }
        }
    };

    const advanceStory = (direction = 1) => {
        const product = playlist[currentProductIndex];
        
        if (direction === 1) {
            if (currentImageIndex < product.images.length - 1) {
                currentImageIndex++;
            }
        } else if (direction === -1) {
            if (currentImageIndex > 0) {
                currentImageIndex--;
            }
        }
        updateLightboxView();
    };

    const openLightbox = (productIndex, imgIndex) => {
        currentProductIndex = productIndex;
        currentImageIndex = imgIndex;
        
        lightbox.style.display = 'flex';
        // Trigger reflow
        void lightbox.offsetWidth;
        
        lightbox.classList.remove('opacity-0', 'hidden');
        lightbox.classList.add('opacity-100');
        
        document.body.style.overflow = 'hidden';
        
        resetZoom();
        updateLightboxView();
    };

    const close = () => {
        lightbox.classList.remove('opacity-100');
        lightbox.classList.add('opacity-0');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightbox.style.display = 'none';
        }, 300);
        document.body.style.overflow = 'auto';
    };

    const triggers = document.querySelectorAll('.lb-trigger');
    triggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            const index = parseInt(trigger.getAttribute('data-index') || '0');
            openLightbox(0, index);
        });
    });

    // Event Listeners
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (prevBtn) prevBtn.addEventListener('click', () => { advanceStory(-1); });
    if (nextBtn) nextBtn.addEventListener('click', () => { advanceStory(1); });

    const imgContainer = mainImage ? mainImage.parentElement : null;
    if (imgContainer) {
        // Zoom on Wheel
        imgContainer.addEventListener('wheel', (e) => {
            if (e.target === closeBtn || e.target.closest('button')) return;
            e.preventDefault();
            
            const zoomAmount = e.deltaY * -0.005;
            currentScale += zoomAmount;
            
            // Min max bounds
            if (currentScale < 1) {
                resetZoom();
                return;
            }
            if (currentScale > 4) currentScale = 4;
            
            mainImage.style.transition = 'none';
            applyZoom();
        }, { passive: false });

        // Double click to zoom in/out
        imgContainer.addEventListener('dblclick', (e) => {
            if (e.target === closeBtn || e.target.closest('button')) return;
            
            if (currentScale > 1) {
                resetZoom();
            } else {
                currentScale = 2;
                mainImage.style.transition = 'transform 0.3s ease-out';
                applyZoom();
            }
        });

        // Click to navigate (only when not zoomed)
        imgContainer.addEventListener('click', (e) => {
            if (e.target === closeBtn || e.target.closest('button')) return;
            if (currentScale > 1) return; // Disable click navigation when zoomed
            
            const rect = imgContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            
            if (x < rect.width * 0.3) {
                advanceStory(-1);
            } else if (x > rect.width * 0.7) {
                advanceStory(1);
            }
        });

        // Drag to pan
        imgContainer.addEventListener('mousedown', (e) => {
            if (currentScale <= 1 || e.target.closest('button')) return;
            isDragging = true;
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
            mainImage.style.transition = 'none';
            mainImage.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            applyZoom();
        });

        window.addEventListener('mouseup', () => {
            if (!isDragging) return;
            isDragging = false;
            applyZoom();
        });
        
        // Touch events for mobile dragging
        let lastTouchX = 0, lastTouchY = 0;
        let initialPinchDistance = null;
        let initialScale = 1;
        
        imgContainer.addEventListener('touchstart', (e) => {
            if (e.target.closest('button')) return;
            
            if (e.touches.length === 2) {
                // Pinch to zoom start
                initialPinchDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                initialScale = currentScale;
            } else if (currentScale > 1 && e.touches.length === 1) {
                // Drag start
                isDragging = true;
                lastTouchX = e.touches[0].clientX;
                lastTouchY = e.touches[0].clientY;
                mainImage.style.transition = 'none';
            }
        }, { passive: false });
        
        imgContainer.addEventListener('touchmove', (e) => {
            if (e.target.closest('button')) return;
            
            if (e.touches.length === 2 && initialPinchDistance) {
                e.preventDefault(); // prevent native scroll
                const currentDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                
                const ratio = currentDistance / initialPinchDistance;
                currentScale = initialScale * ratio;
                
                if (currentScale < 1) currentScale = 1;
                if (currentScale > 4) currentScale = 4;
                
                if (currentScale === 1) resetZoom();
                else applyZoom();
                
            } else if (isDragging && e.touches.length === 1) {
                e.preventDefault(); // prevent native scroll while panning
                const dx = e.touches[0].clientX - lastTouchX;
                const dy = e.touches[0].clientY - lastTouchY;
                
                translateX += dx;
                translateY += dy;
                
                lastTouchX = e.touches[0].clientX;
                lastTouchY = e.touches[0].clientY;
                applyZoom();
            }
        }, { passive: false });
        
        imgContainer.addEventListener('touchend', (e) => {
            isDragging = false;
            initialPinchDistance = null;
        });
    }

    document.addEventListener('keydown', (e) => {
        if (lightbox.classList.contains('hidden')) return;
        
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowRight') advanceStory(1);
        if (e.key === 'ArrowLeft') advanceStory(-1);
    });
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

