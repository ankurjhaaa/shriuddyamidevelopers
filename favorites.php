<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Favorites';
include __DIR__ . '/includes/header.php';
?>

<div class="px-4 sm:px-6 lg:px-8 py-8 bg-white min-h-[80vh]">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">My Favorites</h1>
        
        <div id="favorites-loading" class="flex flex-col items-center justify-center py-12">
            <i class="fa-solid fa-spinner fa-spin text-4xl text-primary mb-4"></i>
            <p class="text-gray-500">Loading your favorites...</p>
        </div>

        <div id="favorites-empty" class="hidden flex-col items-center justify-center text-center py-12">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-4xl mb-4">
                <i class="fa-regular fa-heart"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">No Favorites Yet</h2>
            <p class="text-gray-500 max-w-[250px] mb-6 text-sm">Tap the heart icon on any product to save it here for later.</p>
            <a href="/search.php" class="bg-primary text-white font-semibold py-3 px-8 rounded-lg shadow-sm hover:bg-blue-800 transition">Browse Products</a>
        </div>

        <div id="favorites-grid" class="hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Products will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
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
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
