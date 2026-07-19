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

<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Search Section -->
    <div class="bg-slate-900 py-12 md:py-16 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4">Explore <span class="text-primary">Machines</span></h1>
            <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto mb-8">Find the best agriculture and industrial equipment in Bihar. Tap a category or search below.</p>
            
            <!-- Mobile Search Bar (Live Search) -->
            <div class="max-w-2xl mx-auto bg-white rounded-lg p-2 flex items-center shadow-sm">
                <i class="fa-solid fa-magnifying-glass text-gray-400 ml-3"></i>
                <input type="text" id="searchInput" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" placeholder="Search for tractors, harvesters..." class="w-full bg-transparent border-none focus:ring-0 px-4 py-2 md:py-3 text-gray-800 font-medium placeholder-gray-400">
            </div>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 md:px-8 -mt-6 relative z-20">
        
        <!-- Category Grid (Replaces old pills) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6 mb-8">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-primary"></i> Categories
            </h3>
            
            <div class="flex overflow-x-auto hide-scrollbar gap-3 md:gap-4 w-full snap-x pb-2 custom-scrollbar">
                
                <!-- "All" Button -->
                <button class="category-filter-btn flex-shrink-0 snap-start flex flex-col items-center justify-center p-3 w-24 md:w-28 rounded-lg border transition-all <?php echo $categoryId === '' ? 'border-primary bg-primary/5 text-primary' : 'border-gray-100 bg-gray-50 hover:border-gray-300 text-gray-600 hover:bg-gray-100'; ?>" data-id="">
                    <div class="w-10 h-10 rounded flex items-center justify-center mb-2 <?php echo $categoryId === '' ? 'bg-primary text-white' : 'bg-white text-gray-400 border border-gray-200'; ?>">
                        <i class="fa-solid fa-table-cells-large text-base"></i>
                    </div>
                    <span class="text-[11px] font-bold text-center leading-tight truncate w-full px-1">All Products</span>
                </button>

                <!-- Category Buttons -->
                <?php foreach ($categories as $cat): 
                    $isActive = ($categoryId == $cat['slug']);
                ?>
                    <button class="category-filter-btn flex-shrink-0 snap-start flex flex-col items-center justify-center p-3 w-24 md:w-28 rounded-lg border transition-all <?php echo $isActive ? 'border-primary bg-primary/5 text-primary' : 'border-gray-100 bg-gray-50 hover:border-gray-300 text-gray-600 hover:bg-gray-100'; ?>" data-id="<?php echo htmlspecialchars($cat['slug']); ?>">
                        <div class="w-10 h-10 rounded flex items-center justify-center mb-2 overflow-hidden <?php echo $isActive ? 'border border-primary' : 'border border-gray-200 bg-white'; ?>">
                            <?php if($cat['image']): ?>
                                <img src="/<?php echo htmlspecialchars($cat['image']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <i class="fa-solid fa-box text-base <?php echo $isActive ? 'text-primary' : 'text-gray-400'; ?>"></i>
                            <?php endif; ?>
                        </div>
                        <span class="text-[11px] font-bold text-center leading-tight truncate w-full px-1"><?php echo htmlspecialchars($cat['name']); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Main Content (Results) -->
        <div class="flex-grow">
            <!-- Hidden inputs for JS logic -->
            <input type="hidden" id="searchCategory" value="<?php echo htmlspecialchars($categoryId); ?>">
            <input type="hidden" id="searchInputDesktop" value="">


                <!-- Search Results Container -->
                <div id="searchResults" class="w-full space-y-8">
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
                    const iconContainer = b.querySelector('div');
                    const icon = b.querySelector('i.fa-box');
                    
                    // Reset all to inactive
                    b.classList.remove('border-primary', 'bg-primary/5', 'text-primary');
                    b.classList.add('border-gray-100', 'bg-gray-50', 'hover:border-gray-300', 'text-gray-600', 'hover:bg-gray-100');
                    
                    if(iconContainer) {
                        iconContainer.classList.remove('border', 'border-primary', 'bg-primary', 'text-white');
                        
                        // If it's the "All" button, it has a default style
                        if(b.dataset.id === "") {
                            iconContainer.classList.add('bg-white', 'text-gray-400', 'border', 'border-gray-200');
                        } else {
                            iconContainer.classList.add('border', 'border-gray-200', 'bg-white');
                        }
                    }
                    if(icon) {
                        icon.classList.remove('text-primary');
                        icon.classList.add('text-gray-400');
                    }

                    // Set clicked to active
                    if(b.dataset.id === clickedId) {
                        b.classList.remove('border-gray-100', 'bg-gray-50', 'hover:border-gray-300', 'text-gray-600', 'hover:bg-gray-100');
                        b.classList.add('border-primary', 'bg-primary/5', 'text-primary');
                        
                        if(iconContainer) {
                            iconContainer.classList.remove('border', 'border-gray-200', 'bg-white', 'text-gray-400');
                            if(b.dataset.id === "") {
                                iconContainer.classList.add('bg-primary', 'text-white');
                            } else {
                                iconContainer.classList.add('border', 'border-primary');
                            }
                        }
                        if(icon) {
                            icon.classList.remove('text-gray-400');
                            icon.classList.add('text-primary');
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
