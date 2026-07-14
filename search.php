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

// Smart Location Detection for SEO
$query = $_GET['q'] ?? '';
$locations = require __DIR__ . '/includes/locations.php';
$detectedLocation = '';
$cleanQuery = trim($query);

if ($cleanQuery !== '') {
    $pattern = '/\s+(in|near|at)\s+(' . implode('|', array_map('preg_quote', array_keys($locations))) . ')$/i';
    if (preg_match($pattern, $cleanQuery, $matches)) {
        $detectedLocation = ucwords(strtolower($matches[2]));
    }
}

$displayLoc = $detectedLocation ? $detectedLocation : 'Bihar';

$pageTitle = $categoryId ? "Best {$catName} in {$displayLoc}" : ($detectedLocation ? "Agriculture Machines in {$detectedLocation}" : 'Shop Agriculture Machines in Purnea');
$pageDescription = "Browse and buy {$catName} at the best price near you in {$displayLoc}. Purnea Machine Bazaar offers top quality industrial and farming machinery.";
$pageKeywords = strtolower($catName) . " in " . strtolower($displayLoc) . ", " . strtolower($catName) . " near me, buy " . strtolower($catName) . " bihar, purnea machine bazaar, agriculture equipment near me";

$canonicalUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
if ($categoryId) {
    $canonicalUrl .= '/category/' . urlencode($categoryId);
} else {
    $canonicalUrl .= '/search.php';
}

include __DIR__ . '/includes/header.php';

$categoryId = $_GET['category'] ?? '';

// Fetch all categories for filter pills
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="bg-white min-h-screen pb-16 pt-4">
    <div class="max-w-[1440px] mx-auto px-2 md:px-4">
        
        <!-- Breadcrumbs -->
        <div class="text-[11px] text-gray-500 mb-4 hidden md:block">
            <a href="/" class="hover:text-primary">Home</a> &rsaquo; 
            <span class="text-gray-800 font-semibold"><?php echo $catName; ?></span>
        </div>

        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Left Sidebar Filters (Desktop) -->
            <div class="hidden lg:block w-[240px] flex-shrink-0">
                <div class="bg-white border border-gray-200 rounded-sm p-4 sticky top-[70px]">
                    <h3 class="font-bold text-gray-800 text-sm border-b border-gray-200 pb-2 mb-3">Categories</h3>
                    <ul class="space-y-2 max-h-[300px] overflow-y-auto custom-scrollbar">
                        <li>
                            <button class="category-filter-btn text-left text-xs w-full hover:text-primary transition <?php echo $categoryId === '' ? 'text-primary font-bold' : 'text-gray-600'; ?>" data-id="">
                                All Categories
                            </button>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <button class="category-filter-btn text-left text-xs w-full hover:text-primary transition <?php echo $categoryId == $cat['slug'] ? 'text-primary font-bold' : 'text-gray-600'; ?>" data-id="<?php echo htmlspecialchars($cat['slug']); ?>">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-grow">
                <!-- Mobile / Tablet Top Bar -->
                <div class="bg-white p-3 rounded-sm shadow-sm border border-gray-200 mb-4 sticky top-14 z-40 lg:hidden">
                    <div class="relative w-full flex gap-2">
                        <div class="relative flex-grow">
                            <input type="text" id="searchInput" placeholder="Search products..." class="w-full bg-gray-50 text-gray-900 pl-8 pr-8 py-2 rounded-sm border border-gray-300 focus:outline-none focus:border-primary text-xs transition">
                            <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                            <button id="clearSearch" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hidden hover:text-red-500 transition text-xs">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </div>
                    </div>
                
                    <!-- Mobile Category Filter Pills -->
                    <div class="flex overflow-x-auto hide-scrollbar gap-2 mt-3 w-full snap-x">
                        <button class="category-filter-btn flex-shrink-0 snap-start whitespace-nowrap px-3 py-1 rounded-sm text-[10px] font-semibold border transition <?php echo $categoryId === '' ? 'bg-primary/10 text-primary border-primary' : 'bg-white text-gray-600 border-gray-300'; ?>" data-id="">
                            All
                        </button>
                        <?php foreach ($categories as $cat): ?>
                            <button class="category-filter-btn flex-shrink-0 snap-start whitespace-nowrap px-3 py-1 rounded-sm text-[10px] font-semibold border transition <?php echo $categoryId == $cat['slug'] ? 'bg-primary/10 text-primary border-primary' : 'bg-white text-gray-600 border-gray-300'; ?>" data-id="<?php echo htmlspecialchars($cat['slug']); ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Hidden inputs for JS logic -->
                <input type="hidden" id="searchCategory" value="<?php echo htmlspecialchars($categoryId); ?>">
                <input type="hidden" id="searchInputDesktop" value="">
                
                <!-- Results Header -->
                <div class="bg-white p-3 md:p-4 rounded-md border border-gray-200 mb-6 hidden lg:flex justify-between items-center shadow-sm">
                    <h1 class="text-lg font-bold text-gray-800"><?php echo $catName; ?> <span class="text-xs text-gray-500 font-normal ml-2" id="resultsCount"></span></h1>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-gray-500">Sort by:</span>
                        <select id="sortSelectDesktop" class="border border-gray-300 rounded-sm px-2 py-1 outline-none focus:border-primary">
                            <option value="">Relevance</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Search Results Container -->
                <div id="searchResults" class="flex flex-wrap justify-center sm:justify-start gap-3 md:gap-4">
                    <!-- Results will be injected here via JS -->
                </div>
            
                <!-- Loading State -->
                <div id="searchLoading" class="hidden flex justify-center py-20">
                    <i class="fa-solid fa-circle-notch fa-spin text-primary text-3xl"></i>
                </div>
                
                <!-- Empty State -->
                <div id="searchEmpty" class="hidden flex flex-col items-center justify-center py-16 bg-white rounded-md border border-gray-200 mt-4">
                    <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                    <h3 class="text-base font-semibold text-gray-800 mb-1">No products found</h3>
                    <p class="text-xs text-gray-500">Try adjusting your filters or search term.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #aaa; }
</style>

<script>
    (function() {
        const categoryBtns = document.querySelectorAll('.category-filter-btn');
        const searchCategoryInput = document.getElementById('searchCategory');
        
        // Sync desktop input to mobile input object which JS listens to
        const searchInputDesktop = document.querySelector('header input[name="q"]');
        const searchInputMobile = document.getElementById('searchInput');

        if(searchInputDesktop && searchInputMobile) {
            searchInputDesktop.addEventListener('input', (e) => {
                searchInputMobile.value = e.target.value;
                searchInputMobile.dispatchEvent(new Event('input'));
            });
        }

        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state for all buttons matching this ID (desktop and mobile)
                const clickedId = btn.dataset.id;
                
                categoryBtns.forEach(b => {
                    if(b.classList.contains('rounded-sm')) {
                        // Mobile pill
                        b.classList.remove('bg-primary/10', 'text-primary', 'border-primary');
                        b.classList.add('bg-white', 'text-gray-600', 'border-gray-300');
                        if(b.dataset.id === clickedId) {
                            b.classList.remove('bg-white', 'text-gray-600', 'border-gray-300');
                            b.classList.add('bg-primary/10', 'text-primary', 'border-primary');
                        }
                    } else {
                        // Desktop list item
                        b.classList.remove('text-primary', 'font-bold');
                        b.classList.add('text-gray-600');
                        if(b.dataset.id === clickedId) {
                            b.classList.remove('text-gray-600');
                            b.classList.add('text-primary', 'font-bold');
                        }
                    }
                });

                // Update hidden input
                searchCategoryInput.value = clickedId;
                
                // Trigger search
                if(searchInputMobile) searchInputMobile.dispatchEvent(new Event('input'));
            });
        });
    })();
</script>

<!-- Search Logic is handled in app.js and search.js -->
<script src="/assets/js/search.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>
