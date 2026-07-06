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

// Programmatic SEO setup
$pageTitle = $product['name'] . ' in Purnea - Best Price';
$baseDesc = $product['short_description'] ? $product['short_description'] : 'Buy ' . $product['name'] . ' in Purnea, Bihar at best prices. High-quality ' . strtolower($product['category_name']) . ' from Purnea Machine Baazar.';
$pageDescription = $baseDesc . ' Contact us for latest price and specifications.';
$pageKeywords = strtolower($product['name']) . ', buy ' . strtolower($product['name']) . ' in Purnea, ' . strtolower($product['category_name']) . ' in Purnea, Purnea Machine Baazar, agriculture machinery Purnea';

// Product JSON-LD Schema
$schemaImage = !empty($images) ? 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . $images[0] : '';
$schemaProduct = [
    "@context" => "https://schema.org",
    "@type" => "Product",
    "name" => $product['name'],
    "image" => $schemaImage,
    "description" => $baseDesc,
    "sku" => "PRD-" . $product['id'],
    "brand" => [
        "@type" => "Brand",
        "name" => getSetting('store_name')
    ]
];

// Add Offers schema only if price is public
if ($product['price_visibility'] === 'public' && $product['price'] > 0) {
    $schemaProduct["offers"] = [
        "@type" => "Offer",
        "url" => 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        "priceCurrency" => "INR",
        "price" => $product['price'],
        "itemCondition" => "https://schema.org/NewCondition",
        "availability" => "https://schema.org/InStock"
    ];
}

$customSchema = '<script type="application/ld+json">' . json_encode($schemaProduct, JSON_UNESCAPED_SLASHES) . '</script>';

include __DIR__ . '/includes/header.php';
?>

<div class="bg-gray-50 min-h-screen pb-24 md:pb-12 pt-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="text-[11px] text-gray-500 mb-4 flex items-center gap-2">
            <a href="/" class="hover:text-primary transition">Home</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-gray-400"></i>
            <a href="/search.php" class="hover:text-primary transition">Categories</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-gray-400"></i>
            <span class="text-gray-800 truncate max-w-xs"><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>

        <div class="bg-white rounded-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row md:items-start relative shadow-sm">
            
            <!-- Left: Image Gallery -->
            <div class="w-full md:w-5/12 p-4 border-b border-gray-200 md:border-b-0 flex flex-col items-center justify-start relative md:h-[400px]">
                <?php if (!empty($images)): ?>
                    <div class="swiper product-gallery w-full aspect-square md:aspect-auto md:h-[350px]">
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
                    <div class="w-full aspect-square md:h-[350px] flex items-center justify-center text-gray-300 bg-gray-50 border border-gray-100">
                        <i class="fa-solid fa-image text-5xl"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Product Details -->
            <div class="w-full md:w-7/12 p-5 md:p-8 flex flex-col md:border-l border-gray-200 bg-white">
                <div class="mb-4">
                    <p class="text-xs text-primary font-medium mb-1"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    <h1 class="text-xl md:text-2xl font-semibold text-gray-900 leading-snug"><?php echo htmlspecialchars($product['name']); ?></h1>
                </div>
                
                <!-- Price Block -->
                <div class="bg-gray-50/50 p-4 rounded-sm border border-gray-100 flex items-center justify-between mb-6">
                    <div>
                        <div class="price-container text-xl md:text-2xl" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                                <span class="text-xs text-gray-500 font-normal ml-1">/ Piece</span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price flex items-center gap-2 text-primary font-bold bg-primary/10 hover:bg-primary hover:text-white transition px-3 py-1.5 rounded-sm text-sm border border-primary/20">
                                    <span>Unlock Best Price</span>
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                </button>
                                <span class="real-price hidden font-bold text-gray-900"></span>
                            <?php else: ?>
                                <span class="text-gray-500 text-sm font-medium">Get Latest Price on Request</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <button class="text-gray-400 w-10 h-10 rounded-sm hover:bg-gray-100 border border-transparent flex items-center justify-center wishlist-btn transition hover:text-red-500 hover:border-gray-200" data-id="<?php echo $product['id']; ?>" title="Add to Favorites">
                        <i class="fa-regular fa-heart text-lg"></i>
                    </button>
                </div>
                
                <!-- Desktop CTA Buttons -->
                <div class="hidden md:flex gap-3 mb-8">
                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="flex-[2] bg-primary hover:bg-secondary text-white font-semibold py-2.5 rounded-sm flex items-center justify-center gap-2 text-sm transition">
                        Get Latest Price
                    </a>
                    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex-1 bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-sm flex items-center justify-center gap-2 text-sm transition">
                        <i class="fa-solid fa-phone text-primary"></i> Call Now
                    </a>
                </div>

                <!-- Description & Details -->
                <div class="space-y-6 flex-grow">
                    <?php if ($product['short_description'] || $product['description']): ?>
                        <div class="text-sm text-gray-700 leading-relaxed border-t border-gray-100 pt-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Product Description</h3>
                            <?php 
                                if($product['short_description']) echo '<p class="mb-2">' . nl2br(htmlspecialchars($product['short_description'])) . '</p>';
                                if($product['description']) echo nl2br(htmlspecialchars($product['description'])); 
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['specifications']): ?>
                        <div class="text-sm text-gray-700 leading-relaxed border-t border-gray-100 pt-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Technical Specifications</h3>
                            <?php echo nl2br(htmlspecialchars($product['specifications'])); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['applications']): ?>
                        <div class="text-sm text-gray-700 leading-relaxed border-t border-gray-100 pt-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Applications</h3>
                            <?php echo nl2br(htmlspecialchars($product['applications'])); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-4">
        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Explore Related Products</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
            <?php foreach ($relatedProducts as $product): ?>
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
                                Get Latest Price
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
<div class="md:hidden fixed bottom-0 w-full bg-white border-t border-gray-200 z-50 p-3 flex gap-2 shadow-lg">
    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex-1 bg-white border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-sm flex items-center justify-center gap-2 text-sm shadow-sm">
        <i class="fa-solid fa-phone text-primary"></i> Call
    </a>
    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="flex-[2] bg-primary text-white font-semibold py-2.5 rounded-sm flex items-center justify-center gap-2 text-sm shadow-sm hover:bg-secondary transition">
        Get Latest Price
    </a>
</div>

<!-- Overwrite bottom nav spacing so it doesn't overlap the CTA on this page -->
<style>
    body { padding-bottom: 0 !important; }
    nav.fixed.bottom-0 { display: none !important; }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
