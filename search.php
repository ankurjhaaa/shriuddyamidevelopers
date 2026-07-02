<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Shop';
include __DIR__ . '/includes/header.php';

$categoryId = $_GET['category'] ?? '';

// Fetch all categories for filter pills
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="px-4 sm:px-6 lg:px-8 py-8 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">All Products</h1>
                <p class="text-gray-500 text-sm mt-1">Search and filter to find the perfect machine</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="sticky top-16 md:top-20 bg-white pt-2 pb-4 z-40 animate-fade-in border-b border-gray-100 mb-6">
            <div class="relative max-w-3xl">
                <input type="text" id="searchInput" placeholder="Search for products, machines, tools..." class="w-full bg-gray-50 text-gray-900 px-12 py-4 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition shadow-sm font-medium text-lg">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                <button id="clearSearch" class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hidden hover:text-red-500 transition">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                </button>
            </div>
            
            <!-- Category Filter Pills -->
            <div class="flex overflow-x-auto no-scrollbar gap-3 mt-6 pb-2">
                <button class="category-filter-btn whitespace-nowrap px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 <?php echo $categoryId === '' ? 'bg-primary text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>" data-id="">
                    All Categories
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button class="category-filter-btn whitespace-nowrap px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 <?php echo $categoryId == $cat['id'] ? 'bg-primary text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>" data-id="<?php echo $cat['id']; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <input type="hidden" id="searchCategory" value="<?php echo htmlspecialchars($categoryId); ?>">
        </div>
        
        <!-- Search Results Container -->
        <div id="searchResults" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 mt-2 animate-slide-up">
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
                    b.classList.remove('bg-primary', 'text-white', 'shadow-md');
                    b.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                });
                btn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                btn.classList.add('bg-primary', 'text-white', 'shadow-md');

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
