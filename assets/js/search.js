(function () {
    const searchInput = document.getElementById('searchInput');
    const searchCategory = document.getElementById('searchCategory');
    const searchResults = document.getElementById('searchResults');
    const searchLoading = document.getElementById('searchLoading');
    const searchEmpty = document.getElementById('searchEmpty');
    if (!searchInput) return;

    let debounceTimer;

    const performSearch = async () => {
        const query = searchInput.value.trim();
        const categoryId = searchCategory ? searchCategory.value : '';
        const sortSelect = document.getElementById('sortSelectDesktop');
        const sortValue = sortSelect ? sortSelect.value : '';

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
                if (resultsCountEl) {
                    resultsCountEl.textContent = `(${result.data.length} products)`;
                }

                const isUnlocked = localStorage.getItem('price_unlocked') === 'true';

                // Group products by category
                const categories = {};
                result.data.forEach(product => {
                    const catName = product.category_name || 'Uncategorized';
                    const catSlug = product.category_slug || '';
                    if (!categories[catName]) {
                        categories[catName] = { slug: catSlug, products: [] };
                    }
                    categories[catName].products.push(product);
                });

                const escapeHtml = (unsafe) => {
                    return (unsafe || '').toString()
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                };

                let finalHtml = '';

                for (const [catName, catData] of Object.entries(categories)) {
                    let productsHtml = '';
                    
                    catData.products.forEach(product => {
                        let priceHtml = '';
                        if (product.price_visibility === 'public') {
                            priceHtml = `<span class="font-bold text-lg text-gray-900">${product.formatted_price}</span>`;
                        } else if (product.price_visibility === 'locked') {
                            if (isUnlocked) {
                                priceHtml = `<span class="font-bold text-lg text-gray-900">₹ ${parseFloat(product.price).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>`;
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

                        productsHtml += `
                            <div class="w-[240px] md:w-[260px] flex-shrink-0 snap-start bg-white border border-slate-200 hover:border-primary transition-all h-full group flex flex-col rounded-xl overflow-hidden wishlist-card relative" data-product-id="${product.id}">
                                
                                <!-- Image -->
                                <a href="/products/${encodeURIComponent(product.slug)}" class="block relative w-full h-[200px] md:h-[220px] bg-slate-50 border-b border-slate-100 p-4 flex items-center justify-center">
                                    ${product.primary_image
                                        ? `<img src="/${product.primary_image}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-300" loading="lazy">`
                                        : `<div class="w-full h-full flex items-center justify-center text-slate-200"><i class="fa-solid fa-image text-4xl group-hover:scale-105 transition-transform duration-300"></i></div>`
                                    }
                                </a>
                                
                                <!-- Content -->
                                <div class="flex-grow flex flex-col p-4 md:p-5">
                                    <a href="/products/${encodeURIComponent(product.slug)}" class="block mb-3 w-full">
                                        <h4 class="text-sm md:text-base font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug truncate" title="${escapeHtml(product.name)}">${escapeHtml(product.name)}</h4>
                                    </a>
                                    
                                    <div class="price-container mb-4" data-product-id="${product.id}" data-price="${product.price}" data-visibility="${product.price_visibility}">
                                        ${priceHtml}
                                    </div>

                                    <div class="mt-auto space-y-3">
                                        <p class="text-[11px] md:text-xs text-gray-500 truncate flex items-center gap-1.5 font-medium"><i class="fa-solid fa-location-dot text-primary"></i> Purnea, Bihar</p>
                                        
                                        <a href="${product.whatsapp_link}" target="_blank" data-turbo="false"
                                            class="w-full flex items-center justify-center gap-2 bg-green-50 text-green-600 border border-green-200 font-bold text-xs md:text-sm py-2.5 rounded-lg hover:bg-green-600 hover:text-white hover:border-green-600 transition-colors">
                                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                        </a>
                                    </div>
                                </div>
                                <button
                                    class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-red-500 hover:text-gray-400 z-30 wishlist-btn shadow-sm transition-colors border border-slate-100"
                                    data-id="${product.id}">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                            </div>
                        `;
                    });

                    finalHtml += `
                        <div class="mb-10">
                            <div class="flex justify-between items-center mb-6 px-1">
                                <h3 class="text-xl md:text-2xl font-black text-gray-900 truncate pr-4 flex items-center gap-2">
                                    <i class="fa-solid fa-caret-right text-primary"></i> ${escapeHtml(catName)}
                                </h3>
                                ${catData.slug ? `<button class="category-filter-btn text-primary text-sm font-bold hover:bg-primary/10 px-4 py-1.5 rounded-full transition-colors flex-shrink-0" data-id="${escapeHtml(catData.slug)}">View All</button>` : ''}
                            </div>
                            <div class="flex overflow-x-auto gap-4 md:gap-6 hide-scrollbar pb-6 snap-x custom-scrollbar">
                                ${productsHtml}
                            </div>
                        </div>
                    `;
                }

                searchResults.innerHTML = finalHtml;

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
                if (resultsCountEl) {
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

    // Initial load
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('q')) {
        searchInput.value = urlParams.get('q');
    }
    performSearch();
})();
