(function() {
    const searchInput = document.getElementById('searchInput');
    const searchCategory = document.getElementById('searchCategory');
    const searchResults = document.getElementById('searchResults');
    const searchLoading = document.getElementById('searchLoading');
    const searchEmpty = document.getElementById('searchEmpty');
    const clearSearch = document.getElementById('clearSearch');

    if (!searchInput) return;

    let debounceTimer;

    const performSearch = async () => {
        const query = searchInput.value.trim();
        const categoryId = searchCategory ? searchCategory.value : '';
        
        if (query.length > 0) {
            clearSearch.classList.remove('hidden');
        } else {
            clearSearch.classList.add('hidden');
        }

        // Always show all products if query is empty on search page, handled by backend
        
        // Update URL state
        let newPath = categoryId ? `/category/${categoryId}` : '/search.php';
        let newUrl = new URL(newPath, window.location.origin);
        if (query) {
            newUrl.searchParams.set('q', query);
        }
        window.history.replaceState({}, '', newUrl);
        
        searchResults.innerHTML = '';
        searchResults.classList.add('hidden');
        searchEmpty.classList.add('hidden');
        searchLoading.classList.remove('hidden');

        try {
            const response = await fetch(`/ajax/search.php?q=${encodeURIComponent(query)}&category=${categoryId}`);
            const result = await response.json();

            searchLoading.classList.add('hidden');

            if (result.success && result.data.length > 0) {
                searchResults.classList.remove('hidden');
                
                const isUnlocked = localStorage.getItem('price_unlocked') === 'true';

                result.data.forEach(product => {
                    
                    let priceHtml = '';
                    if (product.price_visibility === 'public') {
                        priceHtml = `<span class="font-bold text-sm text-gray-900">${product.formatted_price}</span>`;
                    } else if (product.price_visibility === 'locked') {
                        if (isUnlocked) {
                            priceHtml = `<span class="font-bold text-sm text-gray-900">₹ ${parseFloat(product.price).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
                        } else {
                            priceHtml = `
                                <button class="btn-unlock-price text-accent font-semibold text-[11px] hover:underline flex items-center gap-1">
                                    Unlock Price <i class="fa-solid fa-lock text-[9px]"></i>
                                </button>
                                <span class="real-price hidden font-bold text-sm text-gray-900"></span>
                            `;
                        }
                    } else {
                        priceHtml = `<span class="text-gray-500 text-[11px]">Price on Request</span>`;
                    }

                    const escapeHtml = (unsafe) => {
                        return (unsafe || '').toString()
                             .replace(/&/g, "&amp;")
                             .replace(/</g, "&lt;")
                             .replace(/>/g, "&gt;")
                             .replace(/"/g, "&quot;")
                             .replace(/'/g, "&#039;");
                    };

                    const card = `
                        <div class="bg-white border border-gray-200 hover:border-gray-300 rounded-sm flex flex-col relative hover:shadow-md transition-all h-full group p-2 pb-3 wishlist-card" data-product-id="${product.id}">
                            <button class="absolute top-2 right-2 w-6 h-6 bg-white/90 rounded-full flex items-center justify-center text-gray-300 hover:text-accent z-10 wishlist-btn shadow-sm" data-id="${product.id}">
                                <i class="fa-regular fa-heart text-xs"></i>
                            </button>
                            
                            <a href="/products/${encodeURIComponent(product.slug)}" class="block relative w-full aspect-square bg-white mb-2">
                                ${product.primary_image 
                                    ? `<img src="/${product.primary_image}" class="w-full h-full object-contain" loading="lazy">`
                                    : `<div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50 border border-gray-100"><i class="fa-solid fa-image text-2xl"></i></div>`
                                }
                            </a>
                            
                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <a href="/products/${encodeURIComponent(product.slug)}" class="block">
                                        <h4 class="text-xs font-medium text-blue-600 hover:underline leading-snug mb-1 line-clamp-2">${escapeHtml(product.name)}</h4>
                                    </a>
                                    <p class="text-[10px] text-gray-500 mb-1 truncate">${escapeHtml(product.category_name || '')}</p>
                                </div>
                                
                                <div class="mt-1 flex flex-col gap-1.5">
                                    <div class="price-container" data-product-id="${product.id}" data-price="${product.price}" data-visibility="${product.price_visibility}">
                                        ${priceHtml}
                                    </div>
                                    
                                    <a href="${product.whatsapp_link}" target="_blank" class="w-full text-center bg-primary/10 text-primary border border-primary/20 hover:bg-primary hover:text-white transition px-2 py-1.5 rounded-sm text-[11px] font-medium mt-1">
                                        Contact Supplier
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    searchResults.insertAdjacentHTML('beforeend', card);
                });

                // Re-initialize logic for newly injected DOM
                if (window.processPriceContainers) {
                    window.processPriceContainers();
                }
                
                if (typeof initWishlist === 'function') {
                    initWishlist();
                }

            } else {
                searchEmpty.classList.remove('hidden');
            }
        } catch (err) {
            console.error(err);
            searchLoading.classList.add('hidden');
        }
    };

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(performSearch, 300);
    });

    clearSearch.addEventListener('click', () => {
        searchInput.value = '';
        performSearch();
    });

    // Initial load
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('q')) {
        searchInput.value = urlParams.get('q');
    }
    performSearch();
})();
