document.addEventListener('DOMContentLoaded', () => {
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
                        priceHtml = `<span class="font-bold text-gray-900">${product.formatted_price}</span>`;
                    } else if (product.price_visibility === 'locked') {
                        if (isUnlocked) {
                            priceHtml = `<span class="font-bold text-gray-900">₹ ${parseFloat(product.price).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
                        } else {
                            priceHtml = `
                                <button class="btn-unlock-price flex items-center gap-1 text-primary font-semibold text-sm bg-blue-50 px-2 py-1 rounded">
                                    <span>₹ *****</span>
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            `;
                        }
                    } else {
                        priceHtml = `<a href="${product.whatsapp_link}" target="_blank" class="text-xs text-primary font-medium">Ask Price</a>`;
                    }

                    const card = `
                        <div class="bg-white border border-gray-100 rounded-xl p-3 shadow-sm hover:shadow-md transition group relative flex flex-col h-full">
                            <a href="/product.php?slug=${encodeURIComponent(product.slug)}" class="block relative aspect-square bg-gray-50 rounded-lg overflow-hidden mb-3">
                                ${product.primary_image 
                                    ? `<img src="/${product.primary_image}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">`
                                    : `<div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-image text-3xl"></i></div>`
                                }
                            </a>
                            <div class="flex-grow flex flex-col">
                                <p class="text-xs text-primary font-medium mb-1 truncate">${product.category_name || 'Uncategorized'}</p>
                                <a href="/product.php?slug=${encodeURIComponent(product.slug)}">
                                    <h4 class="text-sm font-semibold text-gray-800 leading-tight mb-2 line-clamp-2">${product.name}</h4>
                                </a>
                                <div class="mt-auto flex items-center justify-between">
                                    <div class="price-container" data-product-id="${product.id}" data-price="${product.price}" data-visibility="${product.price_visibility}">
                                        ${priceHtml}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    searchResults.insertAdjacentHTML('beforeend', card);
                });

                // Re-initialize price logic for newly injected DOM
                if (window.processPriceContainers) {
                    window.processPriceContainers();
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
    performSearch();
});
