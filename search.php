<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$categoryId = $_GET['category'] ?? ''; // This is now a slug

// Fetch category info for SEO
$catName = 'All Products';
if ($categoryId) {
    $stmt = $pdo->prepare("SELECT name FROM categories WHERE slug = ?");
    $stmt->execute([$categoryId]);
    $cat = $stmt->fetch();
    if ($cat) {
        $catName = $cat['name'];
    }
}

$pageTitle = $categoryId ? "Best {$catName} in Purnea" : 'Shop Agriculture Machines in Purnea';
$pageDescription = "Browse and buy {$catName} at the best price in Purnea. Purnea Machine Baazar offers top quality industrial and farming machinery.";
$pageKeywords = strtolower($catName) . " in purnea, buy " . strtolower($catName) . ", purnea machine baazar, agriculture equipment purnea";

include __DIR__ . '/includes/header.php';

$categoryId = $_GET['category'] ?? '';

// Fetch all categories for filter pills
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Clean Header Area -->
    <div class="bg-primary pt-6 pb-20 px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Find Equipment & Machinery</h1>
        <p class="text-white/80 text-sm">Search from thousands of industrial and agriculture products.</p>
    </div>

    <div class="px-2 sm:px-4 lg:px-8 -mt-14 relative z-20">
        <div class="max-w-7xl mx-auto">

            <!-- Search Bar & Filters -->
            <div class="bg-white p-4 rounded-sm shadow-md border border-gray-200 mb-6 sticky top-[56px] z-40">
                <div class="relative max-w-4xl mx-auto flex gap-2">
                    <div class="relative flex-grow">
                        <input type="text" id="searchInput" placeholder="Enter product name..." class="w-full bg-gray-50 text-gray-900 pl-10 pr-10 py-2.5 rounded-sm border border-gray-300 focus:outline-none focus:border-primary text-sm transition">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <button id="clearSearch" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hidden hover:text-red-500 transition">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </div>
                </div>
            
                <!-- Category Filter Pills -->
                <div class="flex overflow-x-auto no-scrollbar gap-2 mt-4 max-w-4xl mx-auto">
                    <button class="category-filter-btn whitespace-nowrap px-4 py-1.5 rounded-sm text-[11px] font-semibold transition <?php echo $categoryId === '' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>" data-id="">
                        All Categories
                    </button>
                    <?php foreach ($categories as $cat): ?>
                        <button class="category-filter-btn whitespace-nowrap px-4 py-1.5 rounded-sm text-[11px] font-semibold transition <?php echo $categoryId == $cat['slug'] ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'; ?>" data-id="<?php echo htmlspecialchars($cat['slug']); ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" id="searchCategory" value="<?php echo htmlspecialchars($categoryId); ?>">
            </div>
            
            <!-- Search Results Container -->
            <div id="searchResults" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4 mt-4">
            <!-- Results will be injected here via JS -->
        </div>
        
        <!-- Loading State -->
        <div id="searchLoading" class="hidden text-center py-20">
            <i class="fa-solid fa-circle-notch fa-spin text-primary text-4xl"></i>
        </div>
        
        <!-- Empty State -->
        <div id="searchEmpty" class="hidden text-center py-16 bg-white rounded-sm shadow-sm border border-gray-200 mt-4">
            <i class="fa-solid fa-box-open text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">No products found</h3>
            <p class="text-sm text-gray-500">Try adjusting your search criteria.</p>
        </div>
    </div>
</div>

<script>
    // Category pill click handler
    (function() {
        const categoryBtns = document.querySelectorAll('.category-filter-btn');
        const searchCategoryInput = document.getElementById('searchCategory');
        const searchInput = document.getElementById('searchInput');

        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state
                categoryBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                    b.classList.add('bg-gray-100', 'text-gray-600');
                });
                btn.classList.remove('bg-gray-100', 'text-gray-600');
                btn.classList.add('bg-primary', 'text-white', 'shadow-sm');

                // Update hidden input
                searchCategoryInput.value = btn.dataset.id;
                
                // Trigger search
                searchInput.dispatchEvent(new Event('input'));
            });
        });
    })();
</script>

<!-- Search Logic is handled in app.js and search.js -->
<script src="/assets/js/search.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>
