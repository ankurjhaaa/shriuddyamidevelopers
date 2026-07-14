<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Favorites';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-white min-h-screen pb-16 pt-4">
    <div class="max-w-[1440px] mx-auto px-2 md:px-4">
        
        <!-- Breadcrumbs -->
        <div class="text-[11px] text-gray-500 mb-4 hidden md:block">
            <a href="/" class="hover:text-primary">Home</a> &rsaquo; 
            <span class="text-gray-800 font-semibold">Favorites</span>
        </div>
        
        <!-- Results Header -->
        <div class="bg-white p-3 md:p-4 rounded-md border border-gray-200 mb-4 flex justify-between items-center">
            <h1 class="text-lg font-bold text-gray-800">My Favorites</h1>
        </div>

        <div id="favorites-loading" class="flex flex-col items-center justify-center py-12 bg-white rounded-md border border-gray-200 p-8">
            <i class="fa-solid fa-circle-notch fa-spin text-4xl text-primary mb-4"></i>
            <p class="text-gray-500 font-medium">Loading your favorites...</p>
        </div>

        <div id="favorites-empty" class="hidden flex flex-col items-center justify-center py-20 bg-white rounded-md border border-gray-200 p-8 text-center">
            <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-sm flex items-center justify-center text-3xl mb-4 border border-gray-200">
                <i class="fa-regular fa-heart"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mb-2">No Favorites Yet</h2>
            <p class="text-gray-500 max-w-[300px] mb-6 text-sm">Tap the heart icon on any product to save it here for later.</p>
            <a href="/search.php" class="bg-primary text-white font-semibold py-2 px-6 rounded-sm shadow-sm hover:bg-secondary transition text-sm">Browse Products</a>
        </div>

        <div id="favorites-grid" class="hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4 mt-4">
            <!-- Products will be loaded here -->
        </div>
    </div>
</div>

<script>
(function() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const loadingEl = document.getElementById('favorites-loading');
    const emptyEl = document.getElementById('favorites-empty');
    const gridEl = document.getElementById('favorites-grid');

    if (wishlist.length === 0) {
        loadingEl.classList.add('hidden');
        emptyEl.classList.remove('hidden');
        emptyEl.classList.add('flex');
    } else {
        fetch('/ajax/get_favorites.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: wishlist })
        })
        .then(res => res.json())
        .then(data => {
            loadingEl.classList.add('hidden');
            if (data.html.trim() === '') {
                emptyEl.classList.remove('hidden');
                emptyEl.classList.add('flex');
            } else {
                gridEl.innerHTML = data.html;
                gridEl.classList.remove('hidden');
                
                // Re-initialize wishlist buttons in the fetched HTML
                gridEl.querySelectorAll('.wishlist-btn').forEach(btn => {
                    const productId = btn.getAttribute('data-id');
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        // Remove the item from view
                        const card = btn.closest('.wishlist-card');
                        if (card) card.remove();
                        // Remove from localstorage
                        let currentWishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
                        currentWishlist = currentWishlist.filter(id => id !== productId);
                        localStorage.setItem('wishlist', JSON.stringify(currentWishlist));
                        
                        if (currentWishlist.length === 0) {
                            gridEl.classList.add('hidden');
                            emptyEl.classList.remove('hidden');
                            emptyEl.classList.add('flex');
                        }
                    });
                });
            }
        })
        .catch(err => {
            console.error('Error fetching favorites:', err);
            loadingEl.innerHTML = '<p class="text-red-500">Error loading favorites.</p>';
        });
    }
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
