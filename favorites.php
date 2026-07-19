<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Favorites';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Section -->
    <div class="bg-slate-900 py-10 relative overflow-hidden z-10 mb-8">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-20 max-w-[1440px] mx-auto px-4 md:px-8">
            <!-- Breadcrumbs -->
            <div class="text-xs text-gray-400 mb-2 font-bold tracking-wider uppercase">
                <a href="/" class="hover:text-primary transition-colors">Home</a> <span class="mx-2">&gt;</span>
                <span class="text-white">Favorites</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white">My Favorites</h1>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 md:px-8">
        <div id="favorites-loading" class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <i class="fa-solid fa-circle-notch fa-spin text-4xl text-primary mb-4"></i>
            <p class="text-gray-500 font-medium">Loading your favorites...</p>
        </div>

        <div id="favorites-empty" class="hidden flex-col items-center justify-center py-24 bg-white rounded-xl border border-slate-200 shadow-sm p-8 text-center">
            <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center text-4xl mb-6 border border-slate-200">
                <i class="fa-regular fa-heart"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">No Favorites Yet</h2>
            <p class="text-gray-500 max-w-[300px] mb-8 font-medium">Tap the heart icon on any product to save it here for later.</p>
            <a href="/search.php" class="bg-primary text-white font-bold py-3 px-8 rounded-xl hover:bg-secondary transition-colors">Browse Products</a>
        </div>

        <div id="favorites-grid" class="hidden w-full space-y-8">
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
