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
    
    // Product Image Lightbox
    initProductLightbox();
}

function initProductLightbox() {
    const lightbox = document.getElementById('productLightbox');
    if (!lightbox) return;

    const playlist = window.STORY_PLAYLIST;
    if (!playlist || playlist.length === 0) return;

    const mainImage = document.getElementById('lbMainImage');
    const progressContainer = document.getElementById('lbProgressContainer');
    const sidebar = document.getElementById('lbSidebar');
    const closeBtn = document.getElementById('closeLightbox');
    const prevBtn = document.getElementById('lbPrevBtn');
    const nextBtn = document.getElementById('lbNextBtn');
    
    // Sidebar elements
    const productNameEl = document.getElementById('lbProductName');
    const productPriceEl = document.getElementById('lbProductPrice');
    const enquireBtn = document.getElementById('lbEnquireBtn');

    // Story State
    let currentProductIndex = 0;
    let currentImageIndex = 0;
    
    // Timer State
    let timerId = null;
    let startTime = 0;
    let timeElapsed = 0;
    let userPaused = false; 
    let isLoading = false; 
    const DURATION = 5000; 

    const formatPrice = (price) => {
        return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumFractionDigits: 0 }).format(price);
    };

    const updateSidebar = () => {
        const product = playlist[currentProductIndex];
        if (productNameEl) productNameEl.textContent = product.name;
        
        if (productPriceEl) {
            if (product.price_visibility === 'public') {
                productPriceEl.textContent = formatPrice(product.price);
            } else {
                productPriceEl.innerHTML = '<span class="text-sm font-semibold text-primary">Price on Request</span>';
            }
        }

        if (enquireBtn) {
            const waUrl = new URL(enquireBtn.href);
            waUrl.searchParams.set('text', `Hi, I am interested in ${product.name}. Can you send me a quote?`);
            enquireBtn.href = waUrl.toString();
        }
    };

    const buildProgressBars = () => {
        const product = playlist[currentProductIndex];
        const numImages = product.images.length;
        
        if (progressContainer) {
            progressContainer.innerHTML = '';
            for (let i = 0; i < numImages; i++) {
                const barWrapper = document.createElement('div');
                barWrapper.className = 'flex-grow h-full bg-white/30 rounded-sm overflow-hidden relative cursor-pointer';
                
                const fill = document.createElement('div');
                fill.className = 'absolute top-0 left-0 h-full bg-white transition-none';
                fill.style.width = i < currentImageIndex ? '100%' : '0%';
                fill.id = `lb-progress-${i}`;
                
                barWrapper.appendChild(fill);
                
                barWrapper.addEventListener('click', (e) => {
                    e.stopPropagation();
                    jumpToImage(i);
                });
                
                progressContainer.appendChild(barWrapper);
            }
        }
    };
    
    const preloadNextImage = () => {
        const product = playlist[currentProductIndex];
        let nextProdIdx = currentProductIndex;
        let nextImgIdx = currentImageIndex + 1;
        
        if (nextImgIdx >= product.images.length) {
            nextProdIdx++;
            nextImgIdx = 0;
        }
        
        if (nextProdIdx < playlist.length) {
            const img = new Image();
            img.src = '/' + playlist[nextProdIdx].images[nextImgIdx];
        }
    };

    const updateLightboxView = () => {
        const product = playlist[currentProductIndex];
        
        if (currentImageIndex >= product.images.length) {
            currentImageIndex = 0;
        }

        if (progressContainer && progressContainer.children.length !== product.images.length) {
            buildProgressBars();
        } else if (progressContainer) {
            for (let i = 0; i < product.images.length; i++) {
                const fill = document.getElementById(`lb-progress-${i}`);
                if (fill) {
                    fill.style.width = i < currentImageIndex ? '100%' : '0%';
                }
            }
        }
        
        if (prevBtn) {
            prevBtn.style.opacity = (currentProductIndex === 0 && currentImageIndex === 0) ? '0.3' : '1';
        }
        if (nextBtn) {
            const isLast = (currentProductIndex === playlist.length - 1 && currentImageIndex === product.images.length - 1);
            nextBtn.style.opacity = isLast ? '0.3' : '1';
        }
        
        updateSidebar();
        
        userPaused = false;
        isLoading = true;
        
        cancelAnimationFrame(timerId);

        if (mainImage) {
            const newSrc = '/' + product.images[currentImageIndex];
            
            // Remove previous onload handler to prevent race conditions
            mainImage.onload = null;
            mainImage.onerror = null;
            
            const handleLoad = () => {
                isLoading = false;
                startTimer();
                preloadNextImage();
            };
            
            mainImage.onload = handleLoad;
            
            // In case the image fails to load, don't freeze forever
            mainImage.onerror = () => {
                isLoading = false;
                startTimer(); 
            };
            
            mainImage.src = newSrc;
            
            if (mainImage.complete && mainImage.naturalHeight !== 0) {
                handleLoad();
            }
        }
    };

    const startTimer = () => {
        cancelAnimationFrame(timerId);
        if (isLoading) return; 

        startTime = performance.now() - timeElapsed;
        
        const tick = (now) => {
            if (!userPaused && !isLoading) {
                timeElapsed = now - startTime;
                const percentage = Math.min((timeElapsed / DURATION) * 100, 100);
                
                const activeFill = document.getElementById(`lb-progress-${currentImageIndex}`);
                if (activeFill) {
                    activeFill.style.width = `${percentage}%`;
                }

                if (timeElapsed >= DURATION) {
                    advanceStory();
                    return;
                }
            } else {
                startTime = now - timeElapsed;
            }
            timerId = requestAnimationFrame(tick);
        };
        
        timerId = requestAnimationFrame(tick);
    };

    const advanceStory = () => {
        const product = playlist[currentProductIndex];
        timeElapsed = 0;
        
        if (currentImageIndex < product.images.length - 1) {
            currentImageIndex++;
            updateLightboxView();
        } else {
            if (currentProductIndex < playlist.length - 1) {
                currentProductIndex++;
                currentImageIndex = 0;
                buildProgressBars();
                updateLightboxView();
            } else {
                closeLightboxModal();
            }
        }
    };

    const goBackStory = () => {
        timeElapsed = 0;
        
        if (currentImageIndex > 0) {
            currentImageIndex--;
            updateLightboxView();
        } else {
            if (currentProductIndex > 0) {
                currentProductIndex--;
                const product = playlist[currentProductIndex];
                currentImageIndex = product.images.length - 1;
                buildProgressBars();
                updateLightboxView();
            } else {
                updateLightboxView(); 
            }
        }
    };

    const jumpToImage = (index) => {
        timeElapsed = 0;
        currentImageIndex = index;
        updateLightboxView();
    };

    const openLightbox = (startingProductIndex = 0, startingImageIndex = 0) => {
        currentProductIndex = startingProductIndex;
        currentImageIndex = startingImageIndex;
        timeElapsed = 0;
        userPaused = false;
        isLoading = false; // Reset to false before updateLightboxView
        
        buildProgressBars();
        updateLightboxView();
        
        lightbox.style.display = 'flex';
        void lightbox.offsetWidth; // Reflow
        
        lightbox.classList.remove('hidden', 'opacity-0');
        if (sidebar) {
            sidebar.classList.remove('md:translate-x-full');
            sidebar.classList.add('md:translate-x-0');
        }
        
        document.body.style.overflow = 'hidden';
    };

    const closeLightboxModal = () => {
        cancelAnimationFrame(timerId);
        
        lightbox.classList.add('opacity-0');
        if (sidebar) {
            sidebar.classList.remove('md:translate-x-0');
            sidebar.classList.add('md:translate-x-full');
        }
        
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightbox.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    };

    const triggers = document.querySelectorAll('.lb-trigger');
    triggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            const index = parseInt(trigger.getAttribute('data-index') || '0');
            openLightbox(0, index);
        });
    });

    if (prevBtn) prevBtn.addEventListener('click', (e) => { e.stopPropagation(); goBackStory(); });
    if (nextBtn) nextBtn.addEventListener('click', (e) => { e.stopPropagation(); advanceStory(); });
    if (closeBtn) closeBtn.addEventListener('click', (e) => { e.stopPropagation(); closeLightboxModal(); });

    const imgContainer = mainImage ? mainImage.parentElement : null;
    if (imgContainer) {
        imgContainer.addEventListener('click', (e) => {
            if (e.target === closeBtn || e.target.closest('button') || e.target.closest('#lbProgressContainer')) return;
            
            const rect = imgContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            
            if (x < rect.width * 0.3) {
                // Left 30% -> Go back
                goBackStory();
            } else if (x > rect.width * 0.7) {
                // Right 30% -> Go forward
                advanceStory();
            } else {
                // Middle 40% -> Toggle Pause
                userPaused = !userPaused;
                
                // Show visual feedback for pause/play
                const iconContainer = document.createElement('div');
                iconContainer.className = 'absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/50 text-white rounded-sm w-16 h-16 flex items-center justify-center text-2xl z-50 animate-ping opacity-0 transition-opacity duration-300 pointer-events-none';
                iconContainer.innerHTML = userPaused ? '<i class="fa-solid fa-pause"></i>' : '<i class="fa-solid fa-play"></i>';
                imgContainer.appendChild(iconContainer);
                
                requestAnimationFrame(() => {
                    iconContainer.style.opacity = '1';
                });
                
                setTimeout(() => {
                    iconContainer.style.opacity = '0';
                    setTimeout(() => iconContainer.remove(), 300);
                }, 600);
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (lightbox.classList.contains('hidden')) return;
        
        if (e.key === 'Escape') closeLightboxModal();
        if (e.key === 'ArrowRight') advanceStory();
        if (e.key === 'ArrowLeft') goBackStory();
        if (e.key === ' ') {
            e.preventDefault();
            userPaused = !userPaused;
        }
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

