<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Search';
include __DIR__ . '/includes/header.php';

$categoryId = $_GET['category'] ?? '';
?>

<div class="px-4 sm:px-6 lg:px-8 py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Search Bar -->
        <div class="sticky top-16 md:top-20 bg-gray-50 pt-2 pb-6 z-40 animate-fade-in">
            <div class="relative max-w-2xl mx-auto">
                <input type="text" id="searchInput" placeholder="Search for products, machines, tools..." class="w-full bg-white text-gray-900 px-12 py-4 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-sm font-medium text-lg">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                <button id="clearSearch" class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hidden hover:text-red-500 transition">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                </button>
            </div>
            <input type="hidden" id="searchCategory" value="<?php echo htmlspecialchars($categoryId); ?>">
        </div>
        
        <!-- Search Results Container -->
        <div id="searchResults" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 mt-2 animate-slide-up">
            <!-- Results will be injected here via JS -->
        </div>
        
        <!-- Loading State -->
        <div id="searchLoading" class="hidden text-center py-20">
            <!-- Skeleton cards will be injected by JS, or we show a spinner -->
            <i class="fa-solid fa-circle-notch fa-spin text-primary text-4xl"></i>
        </div>
        
        <!-- Empty State -->
        <div id="searchEmpty" class="hidden text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100 mt-4">
            <i class="fa-solid fa-box-open text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
            <p class="text-gray-500">Try adjusting your search criteria or browse categories.</p>
        </div>
    </div>
</div>

<!-- Search Logic is handled in app.js and search.js -->

<?php include __DIR__ . '/includes/footer.php'; ?>
