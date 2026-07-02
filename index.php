<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Home';
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

<!-- Modern Hero Banner -->
<div class="px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <div class="relative bg-gradient-to-br from-primary via-blue-800 to-blue-900 rounded-[2rem] overflow-hidden shadow-2xl">
        
        <!-- Abstract Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-secondary opacity-20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-accent opacity-20 rounded-full blur-3xl"></div>
            <!-- Glassmorphism decorative cards -->
            <div class="hidden lg:block absolute top-1/2 right-12 transform -translate-y-1/2 w-80 h-96 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-2xl -rotate-6 animate-float"></div>
            <div class="hidden lg:block absolute top-1/2 right-24 transform -translate-y-1/2 w-80 h-96 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 shadow-xl rotate-3 animate-float-delayed"></div>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between p-8 md:p-16 lg:p-20">
            <!-- Text Content -->
            <div class="w-full lg:w-3/5 text-center lg:text-left mb-12 lg:mb-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-xs font-bold text-white tracking-widest uppercase mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span> Premium Machinery
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight tracking-tight">
                    Welcome to <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-yellow-200 drop-shadow-sm">SRI UDYAMI</span> DEVELOPERS
                </h1>
                
                <p class="text-blue-100 text-lg md:text-xl mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                    सभी प्रकार के कृषि, उद्योग एवं घरेलू संबंधित मशीनों और उपकरणों के लिए संपर्क करें। <br><span class="text-sm opacity-80 mt-2 block">Your trusted partner for all agricultural, industrial, and domestic machinery.</span>
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                    <a href="/search.php" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-accent text-primary font-bold px-8 py-4 rounded-xl shadow-lg transition-all duration-300 hover:bg-yellow-400 hover:shadow-xl hover:-translate-y-1 text-lg">
                        Explore Catalog <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="/contact.php" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold px-8 py-4 rounded-xl shadow-lg transition-all duration-300 hover:bg-white/20 hover:shadow-xl text-lg">
                        Contact Us
                    </a>
                </div>
            </div>
            
            <!-- Right side / Featured Image area (Mobile hidden, Desktop block) -->
            <div class="w-full lg:w-2/5 hidden lg:flex justify-center relative z-20">
                <div class="relative w-72 h-72 xl:w-80 xl:h-80 bg-gradient-to-tr from-white/20 to-white/5 backdrop-blur-lg rounded-full border border-white/30 flex items-center justify-center shadow-[0_0_40px_rgba(56,189,248,0.2)] animate-float">
                    <i class="fa-solid fa-tractor text-8xl xl:text-9xl text-white drop-shadow-2xl opacity-90"></i>
                    
                    <!-- Floating stat badge -->
                    <div class="absolute -right-8 top-12 bg-white rounded-xl p-4 shadow-xl border border-gray-100 animate-float-delayed">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div>
                                <p class="text-gray-900 font-bold text-lg leading-none">4.9/5</p>
                                <p class="text-gray-500 text-xs">Customer Rating</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating stat badge 2 -->
                    <div class="absolute -left-12 bottom-12 bg-white rounded-xl p-4 shadow-xl border border-gray-100 animate-float">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-primary">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div>
                                <p class="text-gray-900 font-bold text-lg leading-none">Premium</p>
                                <p class="text-gray-500 text-xs">Quality Assured</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        <a href="/search.php" class="text-primary font-semibold hover:text-blue-800 transition flex items-center gap-1 text-sm">
            See All <i class="fa-solid fa-angle-right"></i>
        </a>
    </div>
    <div class="flex overflow-x-auto no-scrollbar gap-4 md:gap-6 pb-4">
        <?php foreach ($categories as $cat): ?>
            <a href="/search.php?category=<?php echo urlencode($cat['id']); ?>" class="flex-shrink-0 w-24 md:w-32 flex flex-col items-center group">
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
<div class="px-4 sm:px-6 lg:px-8 py-8 bg-white animate-slide-up" style="animation-delay: 0.1s;">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">All Products</h3>
        <p class="text-gray-500 text-sm mt-1">Explore our complete range of machines</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
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
