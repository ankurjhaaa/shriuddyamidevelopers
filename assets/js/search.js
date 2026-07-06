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
                        priceHtml = `<span class="font-bold text-sm sm:text-base text-gray-900">${product.formatted_price}</span>`;
                    } else if (product.price_visibility === 'locked') {
                        if (isUnlocked) {
                            priceHtml = `<span class="font-bold text-sm sm:text-base text-gray-900">₹ ${parseFloat(product.price).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
                        } else {
                            priceHtml = `
                                <button class="btn-unlock-price flex items-center gap-1 text-primary font-medium text-[10px] sm:text-xs bg-blue-50 px-2 py-1 rounded w-fit">
                                    <span>₹ *****</span>
                                    <i class="fa-solid fa-lock text-[9px]"></i>
                                </button>
                                <span class="real-price hidden font-bold text-sm sm:text-base text-gray-900"></span>
                            `;
                        }
                    } else {
                        priceHtml = `<a href="${product.whatsapp_link}" target="_blank" class="text-[10px] sm:text-xs text-primary font-medium">Ask Price</a>`;
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
                        <div class="bg-white border border-gray-200 rounded-lg flex flex-row sm:flex-col relative shadow-sm h-full wishlist-card" data-product-id="${product.id}">
                            <button class="absolute top-2 right-2 w-7 h-7 bg-gray-50 sm:bg-white/80 sm:backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 z-10 wishlist-btn shadow-sm text-xs" data-id="${product.id}">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            
                            <a href="/products/${encodeURIComponent(product.slug)}" class="block relative w-2/5 sm:w-full aspect-square bg-white rounded-l-lg sm:rounded-t-lg sm:rounded-bl-none overflow-hidden border-r sm:border-r-0 sm:border-b border-gray-200 shrink-0">
                                ${product.primary_image 
                                    ? `<img src="/${product.primary_image}" class="w-full h-full object-cover" loading="lazy">`
                                    : `<div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-solid fa-image text-3xl"></i></div>`
                                }
                            </a>
                            
                            <div class="p-3 flex-grow flex flex-col justify-between w-3/5 sm:w-full">
                                <div>
                                    <p class="text-[9px] sm:text-[10px] text-gray-500 font-medium mb-0.5 uppercase tracking-wider truncate pr-6">${escapeHtml(product.category_name || '')}</p>
                                    <a href="/products/${encodeURIComponent(product.slug)}" class="block pr-6 sm:pr-0">
                                        <h4 class="text-xs sm:text-sm font-semibold text-gray-900 leading-snug mb-1 line-clamp-2">${escapeHtml(product.name)}</h4>
                                    </a>
                                </div>
                                
                                <div class="mt-2 pt-2 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5 sm:gap-0">
                                    <div class="price-container" data-product-id="${product.id}" data-price="${product.price}" data-visibility="${product.price_visibility}">
                                        ${priceHtml}
                                    </div>
                                    
                                    <a href="/products/${encodeURIComponent(product.slug)}" class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-primary font-bold hover:underline w-fit">
                                        View Details <i class="fa-solid fa-arrow-right text-[9px]"></i>
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
