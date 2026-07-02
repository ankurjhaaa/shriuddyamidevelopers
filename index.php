<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Home';
include __DIR__ . '/includes/header.php';

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC LIMIT 5")->fetchAll();

// Fetch featured products
$featuredProducts = $pdo->query("
    SELECT p.*, c.name as category_name, 
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.featured = 1 AND p.status = 'active'
    ORDER BY p.id DESC LIMIT 4
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

<!-- Hero Banner -->
<div class="px-4 sm:px-6 lg:px-8 py-6 animate-fade-in">
    <div class="bg-primary rounded-2xl md:rounded-3xl p-8 md:p-12 text-white relative overflow-hidden shadow-lg flex flex-col md:flex-row items-center justify-between">
        <div class="relative z-10 w-full md:w-1/2">
            <span class="inline-block px-3 py-1 bg-white/10 rounded-full text-xs font-semibold tracking-wider mb-4 border border-white/20">PREMIUM MACHINES</span>
            <h2 class="text-3xl md:text-5xl font-bold mb-4 leading-tight">Elevate Your Work With Modern Tools</h2>
            <p class="text-blue-100 text-sm md:text-base mb-8 max-w-md">Discover top quality agriculture and industrial tools built for performance and durability.</p>
            <a href="/categories.php" class="inline-flex items-center gap-2 bg-accent text-primary font-bold px-6 py-3 rounded-md shadow-md transition hover:bg-yellow-400 hover:-translate-y-0.5">
                Shop Now <i class="fa-solid fa-arrow-right text-sm"></i>
            </a>
        </div>
        <!-- Abstract Shapes for Desktop -->
        <div class="hidden md:block w-1/2 relative h-64">
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/30 to-transparent rounded-full blur-3xl animate-pulse"></div>
        </div>
        <!-- Decorative abstract shape (Mobile) -->
        <div class="absolute -right-8 -bottom-8 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl md:hidden"></div>
    </div>
</div>

<!-- Categories Slider -->
<?php if (!empty($categories)): ?>
<div class="px-4 sm:px-6 lg:px-8 py-8 animate-slide-up">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Browse Categories</h3>
            <p class="text-gray-500 text-sm mt-1">Find exactly what you need</p>
        </div>
        <a href="/categories.php" class="text-primary font-semibold hover:text-blue-800 transition flex items-center gap-1 text-sm">
            See All <i class="fa-solid fa-angle-right"></i>
        </a>
    </div>
    <div class="flex overflow-x-auto no-scrollbar gap-4 md:gap-6 pb-4">
        <?php foreach ($categories as $cat): ?>
            <a href="/categories.php?slug=<?php echo urlencode($cat['slug']); ?>" class="flex-shrink-0 w-24 md:w-32 flex flex-col items-center group">
                <div class="w-20 h-20 md:w-28 md:h-28 bg-white rounded-2xl flex items-center justify-center mb-3 group-hover:shadow-md transition-all duration-300 shadow-sm border border-gray-100 overflow-hidden group-hover:-translate-y-1">
                    <?php if($cat['image']): ?>
                        <img src="/<?php echo htmlspecialchars($cat['image']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <?php else: ?>
                        <i class="fa-solid fa-layer-group text-primary text-2xl md:text-3xl group-hover:scale-110 transition duration-300"></i>
                    <?php endif; ?>
                </div>
                <span class="text-sm font-semibold text-gray-700 text-center leading-tight group-hover:text-primary transition"><?php echo htmlspecialchars($cat['name']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<div class="px-4 sm:px-6 lg:px-8 py-8 bg-gray-50 animate-slide-up" style="animation-delay: 0.1s;">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Featured Products</h3>
        <p class="text-gray-500 text-sm mt-1">Our most popular machines</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="bg-white border border-gray-100 rounded-md shadow-sm hover:shadow-lg transition-all duration-300 group relative flex flex-col h-full hover:-translate-y-1">
                <!-- Image -->
                <a href="/product.php?slug=<?php echo urlencode($product['slug']); ?>" class="block relative aspect-square bg-gray-50 rounded-t-md overflow-hidden border-b border-gray-50">
                    <?php if($product['primary_image']): ?>
                        <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="fa-solid fa-image text-4xl"></i>
                        </div>
                    <?php endif; ?>
                </a>
                
                <!-- Content -->
                <div class="p-4 flex-grow flex flex-col">
                    <p class="text-xs text-secondary font-semibold mb-1 uppercase tracking-wider truncate"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    <a href="/product.php?slug=<?php echo urlencode($product['slug']); ?>" class="block group-hover:text-primary transition">
                        <h4 class="text-base font-bold text-gray-900 leading-snug mb-3 line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                    </a>
                    
                    <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                        <!-- Price Logic -->
                        <div class="price-container" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-lg text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price flex items-center gap-1.5 text-primary font-semibold text-sm bg-blue-50 px-3 py-1.5 rounded-md hover:bg-blue-100 transition">
                                    <span>₹ *****</span>
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </button>
                                <span class="real-price hidden font-bold text-lg text-gray-900"></span>
                            <?php else: ?>
                                <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="text-sm text-primary font-semibold hover:underline">Ask Price</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Floating WhatsApp Button -->
<a href="<?php echo getWhatsappLink(); ?>" target="_blank" class="fixed bottom-24 md:bottom-8 right-6 z-40 bg-green-500 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-slide-up" style="animation-delay: 0.3s;">
    <i class="fa-brands fa-whatsapp text-3xl"></i>
</a>

<?php include __DIR__ . '/includes/footer.php'; ?>
