<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Shop';
include __DIR__ . '/includes/header.php';

$categoryId = $_GET['category'] ?? '';

// Fetch all categories for filter pills
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="bg-primary text-white pt-4 pb-6 px-6 rounded-b-lg mb-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-1">All Products</h1>
        <p class="text-blue-100 text-xs">Search and filter to find the perfect machine</p>
    </div>
</div>

<div class="px-4 sm:px-6 lg:px-8 pb-8 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Search Bar -->
        <div class="sticky top-14 bg-white pt-2 pb-4 z-40 border-b border-gray-100 mb-6">
            <div class="relative max-w-3xl">
                <input type="text" id="searchInput" placeholder="Search for products, machines, tools..." class="w-full bg-gray-50 text-gray-900 px-10 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary focus:bg-white text-sm font-medium">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                <button id="clearSearch" class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hidden hover:text-red-500 transition">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                </button>
            </div>
            
            <!-- Category Filter Pills -->
            <div class="flex overflow-x-auto no-scrollbar gap-2 mt-4 pb-2">
                <button class="category-filter-btn whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-semibold <?php echo $categoryId === '' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600'; ?>" data-id="">
                    All Categories
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button class="category-filter-btn whitespace-nowrap px-4 py-1.5 rounded-full text-xs font-semibold <?php echo $categoryId == $cat['id'] ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600'; ?>" data-id="<?php echo $cat['id']; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <input type="hidden" id="searchCategory" value="<?php echo htmlspecialchars($categoryId); ?>">
        </div>
        
        <!-- Search Results Container -->
        <div id="searchResults" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-2">
            <!-- Results will be injected here via JS -->
        </div>
        
        <!-- Loading State -->
        <div id="searchLoading" class="hidden text-center py-20">
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

<script>
    // Category pill click handler
    document.addEventListener('DOMContentLoaded', () => {
        const categoryBtns = document.querySelectorAll('.category-filter-btn');
        const searchCategoryInput = document.getElementById('searchCategory');
        const searchInput = document.getElementById('searchInput');

        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state
                categoryBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-white');
                    b.classList.add('bg-gray-100', 'text-gray-600');
                });
                btn.classList.remove('bg-gray-100', 'text-gray-600');
                btn.classList.add('bg-primary', 'text-white');

                // Update hidden input
                searchCategoryInput.value = btn.dataset.id;
                
                // Trigger search
                searchInput.dispatchEvent(new Event('input'));
            });
        });
    });
</script>

<!-- Search Logic is handled in app.js and search.js -->
<script src="/assets/js/search.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>
