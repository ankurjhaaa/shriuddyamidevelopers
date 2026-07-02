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
<div class="px-4 pt-4 pb-2">
    <div class="bg-primary rounded-xl p-6 text-white relative overflow-hidden shadow-sm">
        <div class="relative z-10 w-2/3">
            <h2 class="text-2xl font-bold mb-2 leading-tight">Premium Machines for Every Need</h2>
            <p class="text-blue-100 text-sm mb-4">Discover top quality agriculture and industrial tools.</p>
            <a href="/categories.php" class="inline-block bg-accent text-primary font-semibold px-4 py-2 rounded-lg text-sm shadow-sm transition hover:bg-yellow-400">Shop Now</a>
        </div>
        <!-- Decorative abstract shape -->
        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
    </div>
</div>

<!-- Categories Slider -->
<?php if (!empty($categories)): ?>
<div class="px-4 py-4">
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-lg font-bold text-gray-800">Categories</h3>
        <a href="/categories.php" class="text-primary text-sm font-medium">See All</a>
    </div>
    <div class="flex overflow-x-auto hide-scrollbar gap-4 pb-2">
        <?php foreach ($categories as $cat): ?>
            <a href="/categories.php?slug=<?php echo urlencode($cat['slug']); ?>" class="flex-shrink-0 w-20 flex flex-col items-center group">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-2 group-hover:bg-blue-100 transition shadow-sm overflow-hidden border border-blue-100">
                    <?php if($cat['image']): ?>
                        <img src="/<?php echo htmlspecialchars($cat['image']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fa-solid fa-layer-group text-primary text-xl"></i>
                    <?php endif; ?>
                </div>
                <span class="text-xs font-medium text-gray-600 text-center leading-tight"><?php echo htmlspecialchars($cat['name']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<div class="px-4 py-4 bg-white">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold text-gray-800">Featured Products</h3>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="bg-white border border-gray-100 rounded-xl p-3 shadow-sm hover:shadow-md transition group relative flex flex-col h-full">
                <!-- Image -->
                <a href="/product.php?slug=<?php echo urlencode($product['slug']); ?>" class="block relative aspect-square bg-gray-50 rounded-lg overflow-hidden mb-3">
                    <?php if($product['primary_image']): ?>
                        <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="fa-solid fa-image text-3xl"></i>
                        </div>
                    <?php endif; ?>
                </a>
                
                <!-- Content -->
                <div class="flex-grow flex flex-col">
                    <p class="text-xs text-primary font-medium mb-1 truncate"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    <a href="/product.php?slug=<?php echo urlencode($product['slug']); ?>">
                        <h4 class="text-sm font-semibold text-gray-800 leading-tight mb-2 line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                    </a>
                    
                    <div class="mt-auto flex items-center justify-between">
                        <!-- Price Logic -->
                        <div class="price-container" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price flex items-center gap-1 text-primary font-semibold text-sm bg-blue-50 px-2 py-1 rounded">
                                    <span>₹ *****</span>
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <span class="real-price hidden font-bold text-gray-900"></span>
                            <?php else: ?>
                                <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="text-xs text-primary font-medium">Ask Price</a>
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
<a href="<?php echo getWhatsappLink(); ?>" target="_blank" class="fixed bottom-20 right-4 z-40 bg-green-500 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 transition hover:scale-110">
    <i class="fa-brands fa-whatsapp text-3xl"></i>
</a>

<!-- Simple CSS for horizontal scrollbar hide -->
<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
