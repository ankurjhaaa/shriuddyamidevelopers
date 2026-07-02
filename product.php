<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header("Location: /404.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.slug = ? AND p.status = 'active'
");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: /404.php");
    exit;
}

$stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC");
$stmt->execute([$product['id']]);
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch related products from the same category
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, 
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.category_id = ? AND p.id != ? AND p.status = 'active'
    ORDER BY p.id DESC LIMIT 4
");
$stmt->execute([$product['category_id'], $product['id']]);
$relatedProducts = $stmt->fetchAll();

$pageTitle = $product['name'];
include __DIR__ . '/includes/header.php';
?>

<div class="bg-white min-h-screen pb-24 md:pb-12 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="text-xs text-gray-500 mb-4 flex items-center gap-2">
            <a href="/" class="hover:text-primary">Home</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <a href="/search.php" class="hover:text-primary">Shop</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-gray-900 font-medium truncate max-w-xs"><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden flex flex-col md:flex-row md:items-start">
            
            <!-- Left: Image Gallery -->
            <div class="w-full md:w-1/2 p-4 md:p-6 border-b md:border-b-0 flex flex-col items-center justify-start bg-white relative md:h-[500px]">
                <?php if (!empty($images)): ?>
                    <div class="swiper product-gallery w-full aspect-square md:aspect-auto md:h-[450px]">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $img): ?>
                                <div class="swiper-slide flex items-start justify-center p-2">
                                    <img src="/<?php echo htmlspecialchars($img); ?>" class="max-h-full max-w-full object-contain">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                <?php else: ?>
                    <div class="w-full aspect-square md:h-[450px] flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-7xl"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Product Details -->
            <div class="w-full md:w-1/2 p-4 md:p-6 flex flex-col md:border-l border-gray-200">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1"><?php echo htmlspecialchars($product['category_name']); ?></p>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <!-- Price Block -->
                <div class="bg-blue-50/30 p-4 rounded-lg border border-blue-100 flex items-center justify-between mb-6">
                    <div>
                        <p class="text-xs text-gray-500 font-medium mb-1">Price</p>
                        <div class="price-container text-xl" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price flex items-center gap-2 text-primary font-bold bg-white px-3 py-1.5 rounded-lg border border-blue-100 text-sm">
                                    <span>Unlock Price</span>
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </button>
                                <span class="real-price hidden font-bold text-gray-900"></span>
                            <?php else: ?>
                                <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="text-primary font-bold text-lg">Ask on WhatsApp</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <button class="text-gray-400 w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center wishlist-btn transition hover:text-red-500" data-id="<?php echo $product['id']; ?>">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </div>
                
                <!-- Desktop CTA Buttons -->
                <div class="hidden md:flex gap-3 mb-6">
                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="flex-1 bg-green-500 text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 text-sm">
                        <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp Info
                    </a>
                    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex-1 bg-white border border-gray-200 text-gray-700 font-bold py-3 rounded-lg flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-phone"></i> Call Now
                    </a>
                </div>

                <!-- Description & Details -->
                <div class="space-y-6 flex-grow">
                    <?php if ($product['short_description'] || $product['description']): ?>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="fa-solid fa-file-lines text-primary"></i> Description
                            </h3>
                            <div class="text-gray-600 leading-relaxed text-xs md:text-sm">
                                <?php 
                                    if($product['short_description']) echo '<p class="mb-2 font-medium text-gray-700">' . nl2br(htmlspecialchars($product['short_description'])) . '</p>';
                                    if($product['description']) echo nl2br(htmlspecialchars($product['description'])); 
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['specifications']): ?>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="fa-solid fa-list-check text-primary"></i> Specifications
                            </h3>
                            <div class="text-xs md:text-sm text-gray-600 leading-relaxed bg-white p-3 rounded-lg border border-gray-200">
                                <?php echo nl2br(htmlspecialchars($product['specifications'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['applications']): ?>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 mb-2 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="fa-solid fa-gears text-primary"></i> Applications
                            </h3>
                            <div class="text-xs md:text-sm text-gray-600 leading-relaxed bg-white p-3 rounded-lg border border-gray-200">
                                <?php echo nl2br(htmlspecialchars($product['applications'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-4">
        <div class="mb-4">
            <h3 class="text-xl font-bold text-gray-900 tracking-tight">Similar Products</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($relatedProducts as $relProduct): ?>
                <div class="bg-white border border-gray-200 rounded-lg flex flex-row sm:flex-col relative shadow-sm h-full wishlist-card" data-product-id="<?php echo $relProduct['id']; ?>">
                    <button class="absolute top-2 right-2 w-7 h-7 bg-gray-50 sm:bg-white/80 sm:backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 z-10 wishlist-btn shadow-sm text-xs" data-id="<?php echo $relProduct['id']; ?>">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                    
                    <!-- Image -->
                    <a href="/product.php?slug=<?php echo urlencode($relProduct['slug']); ?>" class="block relative w-2/5 sm:w-full aspect-square bg-white rounded-l-lg sm:rounded-t-lg sm:rounded-bl-none overflow-hidden border-r sm:border-r-0 sm:border-b border-gray-200 shrink-0">
                        <?php if($relProduct['primary_image']): ?>
                            <img src="/<?php echo htmlspecialchars($relProduct['primary_image']); ?>" class="w-full h-full object-cover" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    
                    <!-- Content -->
                    <div class="p-3 flex-grow flex flex-col justify-between w-3/5 sm:w-full">
                        <div>
                            <p class="text-[9px] sm:text-[10px] text-gray-500 font-medium mb-0.5 uppercase tracking-wider truncate pr-6"><?php echo htmlspecialchars($relProduct['category_name']); ?></p>
                            <a href="/product.php?slug=<?php echo urlencode($relProduct['slug']); ?>" class="block pr-6 sm:pr-0">
                                <h4 class="text-xs sm:text-sm font-semibold text-gray-900 leading-snug mb-1 line-clamp-2"><?php echo htmlspecialchars($relProduct['name']); ?></h4>
                            </a>
                        </div>
                        
                        <div class="mt-2 pt-2 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5 sm:gap-0">
                            <!-- Price Logic -->
                            <div class="price-container" data-product-id="<?php echo $relProduct['id']; ?>" data-price="<?php echo $relProduct['price']; ?>" data-visibility="<?php echo $relProduct['price_visibility']; ?>">
                                <?php if ($relProduct['price_visibility'] === 'public'): ?>
                                    <span class="font-bold text-sm sm:text-base text-gray-900"><?php echo formatPrice($relProduct['price']); ?></span>
                                <?php elseif ($relProduct['price_visibility'] === 'locked'): ?>
                                    <button class="btn-unlock-price flex items-center gap-1 text-primary font-medium text-[10px] sm:text-xs bg-blue-50 px-2 py-1 rounded w-fit">
                                        <span>₹ *****</span>
                                        <i class="fa-solid fa-lock text-[9px]"></i>
                                    </button>
                                    <span class="real-price hidden font-bold text-sm sm:text-base text-gray-900"></span>
                                <?php else: ?>
                                    <a href="<?php echo getWhatsappLink($relProduct['name']); ?>" target="_blank" class="text-[10px] sm:text-xs text-primary font-medium">Ask Price</a>
                                <?php endif; ?>
                            </div>
                            
                            <a href="/product.php?slug=<?php echo urlencode($relProduct['slug']); ?>" class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-primary font-bold hover:underline w-fit">
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

<!-- Sticky Call to Action for Product Page (Mobile Only) -->
<div class="md:hidden fixed bottom-0 w-full bg-white border-t border-gray-200 z-50 p-3 flex gap-2">
    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex-1 bg-white border border-gray-200 text-gray-800 font-bold py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm">
        <i class="fa-solid fa-phone"></i> Call
    </a>
    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="flex-[2] bg-green-500 text-white font-bold py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm">
        <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp
    </a>
</div>

<!-- Overwrite bottom nav spacing so it doesn't overlap the CTA on this page -->
<style>
    body { padding-bottom: 0 !important; }
    nav.fixed.bottom-0 { display: none !important; }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
