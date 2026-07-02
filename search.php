<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Search';
include __DIR__ . '/includes/header.php';

$categoryId = $_GET['category'] ?? '';
?>

<div class="px-4 py-4 bg-white min-h-screen">
    <!-- Search Bar -->
    <div class="sticky top-14 bg-white pt-2 pb-4 z-40">
        <div class="relative">
            <input type="text" id="searchInput" placeholder="Search products..." class="w-full bg-gray-100 text-gray-900 px-10 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition shadow-sm">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <button id="clearSearch" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hidden hover:text-gray-600">
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
        </div>
        <input type="hidden" id="searchCategory" value="<?php echo htmlspecialchars($categoryId); ?>">
    </div>
    
    <!-- Search Results Container -->
    <div id="searchResults" class="grid grid-cols-2 gap-4 mt-2">
        <!-- Results will be injected here via JS -->
    </div>
    
    <!-- Loading State -->
    <div id="searchLoading" class="hidden text-center py-10">
        <i class="fa-solid fa-circle-notch fa-spin text-primary text-3xl"></i>
    </div>
    
    <!-- Empty State -->
    <div id="searchEmpty" class="hidden text-center py-10">
        <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 text-sm">No products found matching your criteria.</p>
    </div>
</div>

<!-- Search Logic is handled in app.js and search.js -->

<?php include __DIR__ . '/includes/footer.php'; ?>
