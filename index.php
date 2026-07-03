<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Home - Agriculture & Industrial Machines in Purnea';
$pageDescription = 'Purnea Machine Baazar is the leading provider of agriculture, farming, and industrial machines in Purnea, Bihar. Get the best price on tractors, cultivators, and more.';
$pageKeywords = 'purnea machine baazar, agriculture machines purnea, industrial machines purnea, tractors purnea, farming equipment bihar';

include __DIR__ . '/includes/header.php';

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC LIMIT 5")->fetchAll();

// Fetch featured products (Now acting as All Products on Home)
$featuredProducts = $pdo->query("
    SELECT p.*, c.name as category_name, 
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
    ORDER BY p.id DESC LIMIT 12
")->fetchAll();

// Fetch latest products
$latestProducts = $pdo->query("
    SELECT p.*, c.name as category_name, 
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
    ORDER BY p.id DESC LIMIT 6
")->fetchAll();
?>

<div class="bg-white min-h-screen pb-10">
    <!-- Modern Hero Banner -->
    <div class="relative w-full overflow-hidden flex flex-col items-center justify-center z-10">
        <!-- Desktop Banner (hidden on small screens) -->
        <img src="/assets/images/desktop_banner.png" alt="Purnea Machine Baazar Banner" class="w-full h-auto object-cover hidden md:block">
        <!-- Mobile Banner (hidden on medium/large screens) -->
        <img src="/assets/images/mobile_banner.png" alt="Purnea Machine Baazar Banner" class="w-full h-auto object-cover md:hidden">
    </div>

    <!-- Main Content Overlapping Banner -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6 relative z-20 max-w-7xl mx-auto space-y-6">
        
        <!-- Categories Slider -->
        <?php if (!empty($categories)): ?>
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-end mb-4">
        <div>
            <h3 class="text-xl font-bold text-gray-900 tracking-tight">Browse Categories</h3>
            <p class="text-gray-500 text-xs mt-1">Find exactly what you need</p>
        </div>
        <a href="/search.php" class="text-primary font-medium flex items-center gap-1 text-xs">
            See All <i class="fa-solid fa-angle-right"></i>
        </a>
    </div>
    <div class="flex overflow-x-auto no-scrollbar gap-3 md:gap-4 pb-2">
        <?php foreach ($categories as $cat): ?>
            <a href="/search.php?category=<?php echo urlencode($cat['id']); ?>" class="flex-shrink-0 w-20 md:w-24 flex flex-col items-center">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-lg flex items-center justify-center mb-2 border border-gray-200 overflow-hidden">
                    <?php if($cat['image']): ?>
                        <img src="/<?php echo htmlspecialchars($cat['image']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fa-solid fa-layer-group text-primary text-xl md:text-2xl"></i>
                    <?php endif; ?>
                </div>
                <span class="text-xs font-medium text-gray-700 text-center leading-tight"><?php echo htmlspecialchars($cat['name']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
        </div>
        <?php endif; ?>

        <!-- Featured Products -->
        <?php if (!empty($featuredProducts)): ?>
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-4">
        <h3 class="text-xl font-bold text-gray-900 tracking-tight">All Products</h3>
        <p class="text-gray-500 text-xs mt-1">Explore our complete range of machines</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="bg-white border border-gray-200 rounded-lg flex flex-row sm:flex-col relative shadow-sm h-full" data-product-id="<?php echo $product['id']; ?>">
                <button class="absolute top-2 right-2 w-7 h-7 bg-gray-50 sm:bg-white/80 sm:backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 z-10 wishlist-btn shadow-sm text-xs" data-id="<?php echo $product['id']; ?>">
                    <i class="fa-regular fa-heart"></i>
                </button>
                
                <!-- Image -->
                <a href="/product.php?slug=<?php echo urlencode($product['slug']); ?>" class="block relative w-2/5 sm:w-full aspect-square bg-white rounded-l-lg sm:rounded-t-lg sm:rounded-bl-none overflow-hidden border-r sm:border-r-0 sm:border-b border-gray-200 shrink-0">
                    <?php if($product['primary_image']): ?>
                        <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-cover" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="fa-solid fa-image text-3xl"></i>
                        </div>
                    <?php endif; ?>
                </a>
                
                <!-- Content -->
                <div class="p-3 flex-grow flex flex-col justify-between w-3/5 sm:w-full">
                    <div>
                        <p class="text-[9px] sm:text-[10px] text-gray-500 font-medium mb-0.5 uppercase tracking-wider truncate pr-6"><?php echo htmlspecialchars($product['category_name']); ?></p>
                        <a href="/product.php?slug=<?php echo urlencode($product['slug']); ?>" class="block pr-6 sm:pr-0">
                            <h4 class="text-xs sm:text-sm font-semibold text-gray-900 leading-snug mb-1 line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                        </a>
                    </div>
                    
                    <div class="mt-2 pt-2 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5 sm:gap-0">
                        <!-- Price Logic -->
                        <div class="price-container" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-sm sm:text-base text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price flex items-center gap-1 text-primary font-medium text-[10px] sm:text-xs bg-blue-50 px-2 py-1 rounded w-fit">
                                    <span>₹ *****</span>
                                    <i class="fa-solid fa-lock text-[9px]"></i>
                                </button>
                                <span class="real-price hidden font-bold text-sm sm:text-base text-gray-900"></span>
                            <?php else: ?>
                                <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="text-[10px] sm:text-xs text-primary font-medium">Ask Price</a>
                            <?php endif; ?>
                        </div>
                        
                        <a href="/product.php?slug=<?php echo urlencode($product['slug']); ?>" class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-primary font-bold hover:underline w-fit">
                            View Details <i class="fa-solid fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Floating WhatsApp Button -->
<a href="<?php echo getWhatsappLink(); ?>" target="_blank" class="fixed bottom-24 md:bottom-8 right-6 z-40 bg-green-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg">
    <i class="fa-brands fa-whatsapp text-2xl"></i>
</a>

<?php include __DIR__ . '/includes/footer.php'; ?>
