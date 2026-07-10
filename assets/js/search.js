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
        const sortSelect = document.getElementById('sortSelectDesktop');
        const sortValue = sortSelect ? sortSelect.value : '';
        
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
            const response = await fetch(`/ajax/search.php?q=${encodeURIComponent(query)}&category=${categoryId}&sort=${encodeURIComponent(sortValue)}`);
            const result = await response.json();

            searchLoading.classList.add('hidden');

            if (result.success && result.data.length > 0) {
                searchResults.classList.remove('hidden');
                
                const resultsCountEl = document.getElementById('resultsCount');
                if(resultsCountEl) {
                    resultsCountEl.textContent = `(${result.data.length} products)`;
                }
                
                const isUnlocked = localStorage.getItem('price_unlocked') === 'true';

                result.data.forEach(product => {
                    
                    let priceHtml = '';
                    if (product.price_visibility === 'public') {
                        priceHtml = `<span class="font-bold text-lg text-gray-900">${product.formatted_price}</span>`;
                    } else if (product.price_visibility === 'locked') {
                        if (isUnlocked) {
                            priceHtml = `<span class="font-bold text-lg text-gray-900">₹ ${parseFloat(product.price).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
                        } else {
                            priceHtml = `
                                <button class="btn-unlock-price text-accent font-semibold text-xs hover:underline flex items-center gap-1">
                                    Unlock Price <i class="fa-solid fa-lock text-[10px]"></i>
                                </button>
                            `;
                        }
                    } else {
                        priceHtml = `
                            <button class="btn-unlock-price text-gray-500 text-xs font-semibold hover:underline flex items-center gap-1">
                                Get Latest Price
                            </button>
                        `;
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
                        <div class="bg-white border border-gray-200 hover:shadow-lg transition-all h-full group flex flex-col rounded-sm overflow-hidden" data-product-id="${product.id}">
                            
                            <!-- Image -->
                            <a href="/products/${encodeURIComponent(product.slug)}" class="block relative w-full aspect-square bg-white border-b border-gray-100 p-2">
                                ${product.primary_image 
                                    ? `<img src="/${product.primary_image}" class="w-full h-full object-contain mix-blend-multiply" loading="lazy">`
                                    : `<div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50"><i class="fa-solid fa-image text-4xl"></i></div>`
                                }
                            </a>
                            
                            <!-- Content -->
                            <div class="flex-grow flex flex-col p-3">
                                <a href="/products/${encodeURIComponent(product.slug)}" class="block mb-2">
                                    <h4 class="text-sm font-medium text-blue-700 hover:underline leading-snug line-clamp-2">${escapeHtml(product.name)}</h4>
                                </a>
                                
                                <div class="price-container mb-3" data-product-id="${product.id}" data-price="${product.price}" data-visibility="${product.price_visibility}">
                                    ${priceHtml}
                                </div>

                                <div class="mt-auto">
                                    <p class="text-[11px] text-gray-500 mb-3 truncate flex items-center gap-1"><i class="fa-solid fa-location-dot text-gray-400"></i> Purnea, Bihar</p>
                                    
                                    <a href="${product.whatsapp_link}" target="_blank" class="w-full text-center bg-primary text-white hover:bg-secondary transition px-3 py-2 rounded-sm text-sm font-semibold flex justify-center items-center gap-2">
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
                const resultsCountEl = document.getElementById('resultsCount');
                if(resultsCountEl) {
                    resultsCountEl.textContent = `(0 products)`;
                }
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

    const sortSelect = document.getElementById('sortSelectDesktop');
    if (sortSelect) {
        sortSelect.addEventListener('change', performSearch);
    }

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
