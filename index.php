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
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    
    <!-- IndiaMART Style Compact Banner -->
    <div class="relative w-full z-10 bg-gray-100 border-b border-gray-200 hidden md:block">
        <div class="swiper heroSwiper w-full h-[200px] md:h-[250px] max-w-7xl mx-auto">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide relative">
                    <img src="/assets/images/carousel/1.png" class="w-full h-full object-cover" alt="Industrial Tractor">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 to-transparent z-10"></div>
                    <div class="absolute inset-0 z-20 flex flex-col justify-center px-12">
                        <h1 class="text-3xl font-bold text-white mb-2 shadow-sm">Heavy Machinery Deals</h1>
                        <p class="text-white/90 text-sm max-w-sm mb-4">Discover premium tractors and agriculture equipment.</p>
                        <a href="/search.php" class="bg-accent text-white px-5 py-2 rounded-sm font-medium w-fit shadow-sm text-sm hover:bg-orange-600 transition">Shop Now</a>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide relative">
                    <img src="/assets/images/carousel/2.png" class="w-full h-full object-cover" alt="Industrial Harvester">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 to-transparent z-10"></div>
                    <div class="absolute inset-0 z-20 flex flex-col justify-center px-12">
                        <h1 class="text-3xl font-bold text-white mb-2 shadow-sm">Advanced Harvesters</h1>
                        <p class="text-white/90 text-sm max-w-sm mb-4">Increase yield with state-of-the-art harvesters.</p>
                        <a href="/search.php" class="bg-accent text-white px-5 py-2 rounded-sm font-medium w-fit shadow-sm text-sm hover:bg-orange-600 transition">Shop Now</a>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('.heroSwiper', {
                loop: true,
                effect: 'fade',
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>

    <!-- Main Content Overlapping Banner -->
    <div class="px-4 sm:px-6 lg:px-8 mt-6 relative z-20 max-w-7xl mx-auto space-y-6">
        
        <!-- Categories Slider -->
        <?php if (!empty($categories)): ?>
        <div class="bg-white p-4 md:p-5 rounded-sm shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Browse Categories</h3>
        <a href="/search.php" class="text-primary text-sm font-medium hover:underline">View All</a>
    </div>
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
        <?php foreach ($categories as $cat): ?>
            <a href="/category/<?php echo urlencode($cat['slug']); ?>" class="flex flex-col items-center group p-2 border border-transparent hover:border-gray-200 rounded-sm hover:shadow-sm transition bg-white">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 flex items-center justify-center mb-2 overflow-hidden">
                    <?php if($cat['image']): ?>
                        <img src="/<?php echo htmlspecialchars($cat['image']); ?>" class="w-full h-full object-contain p-2 group-hover:scale-105 transition">
                    <?php else: ?>
                        <i class="fa-solid fa-layer-group text-gray-400 text-xl group-hover:text-primary transition"></i>
                    <?php endif; ?>
                </div>
                <span class="text-[11px] md:text-xs font-medium text-gray-700 text-center leading-tight line-clamp-2"><?php echo htmlspecialchars($cat['name']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
        </div>
        <?php endif; ?>

        <!-- Featured Products -->
        <?php if (!empty($featuredProducts)): ?>
        <div class="bg-white p-4 md:p-5 rounded-sm shadow-sm border border-gray-200 mt-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Featured Machinery</h3>
        <a href="/search.php" class="text-primary text-sm font-medium hover:underline">View All</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="bg-white border border-gray-200 hover:border-gray-300 rounded-sm flex flex-col relative hover:shadow-md transition-all h-full group p-2 pb-3 wishlist-card" data-product-id="<?php echo $product['id']; ?>">
                <button class="absolute top-2 right-2 w-6 h-6 bg-white/90 rounded-full flex items-center justify-center text-gray-300 hover:text-accent z-10 wishlist-btn shadow-sm" data-id="<?php echo $product['id']; ?>">
                    <i class="fa-regular fa-heart text-xs"></i>
                </button>
                
                <!-- Image -->
                <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block relative w-full aspect-square bg-white mb-2">
                    <?php if($product['primary_image']): ?>
                        <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-contain" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50 border border-gray-100">
                            <i class="fa-solid fa-image text-2xl"></i>
                        </div>
                    <?php endif; ?>
                </a>
                
                <!-- Content -->
                <div class="flex-grow flex flex-col justify-between">
                    <div>
                        <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block">
                            <h4 class="text-xs font-medium text-blue-600 hover:underline leading-snug mb-1 line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                        </a>
                        <p class="text-[10px] text-gray-500 mb-1 truncate"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    </div>
                    
                    <div class="mt-1 flex flex-col gap-1.5">
                        <div class="price-container" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-sm text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price text-accent font-semibold text-[11px] hover:underline flex items-center gap-1">
                                    Unlock Price <i class="fa-solid fa-lock text-[9px]"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-gray-500 text-[11px]">Price on Request</span>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="w-full text-center bg-primary/10 text-primary border border-primary/20 hover:bg-primary hover:text-white transition px-2 py-1.5 rounded-sm text-[11px] font-medium mt-1">
                            Contact Supplier
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
