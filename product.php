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
    <div class="max-w-[1440px] mx-auto px-2 md:px-4">
        
        <!-- Breadcrumb -->
        <div class="text-[11px] text-gray-500 mb-4 hidden md:block">
            <a href="/" class="hover:text-primary">Home</a> &rsaquo; 
            <a href="/search.php?category=<?php echo htmlspecialchars($product['category_id']); ?>" class="hover:text-primary"><?php echo htmlspecialchars($product['category_name']); ?></a> &rsaquo; 
            <span class="text-gray-800 font-semibold"><?php echo htmlspecialchars($product['name']); ?></span>
        </div>

        <div class="bg-white rounded-sm border border-gray-200 overflow-hidden flex flex-col lg:flex-row lg:items-start relative shadow-sm">
            
            <!-- Left: Sticky Image Gallery -->
            <div class="w-full lg:w-[450px] xl:w-[500px] flex-shrink-0 p-4 border-b lg:border-b-0 lg:border-r border-gray-200 lg:sticky lg:top-[70px]">
                <?php if (!empty($images)): ?>
                    <div class="swiper product-gallery w-full aspect-square border border-gray-100 rounded-sm overflow-hidden mb-2">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $img): ?>
                                <div class="swiper-slide flex items-center justify-center bg-white p-2">
                                    <img src="/<?php echo htmlspecialchars($img); ?>" class="max-h-full max-w-full object-contain mix-blend-multiply">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                <?php else: ?>
                    <div class="w-full aspect-square border border-gray-100 rounded-sm flex items-center justify-center text-gray-300 bg-gray-50 mb-2">
                        <i class="fa-solid fa-image text-5xl"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Product Details & Specs -->
            <div class="w-full p-4 md:p-6 lg:p-8 flex-grow">
                <div class="mb-4">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 leading-snug mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <!-- Price Block -->
                    <div class="flex items-center gap-3">
                        <div class="price-container text-2xl md:text-3xl" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                                <span class="text-sm text-gray-500 font-normal ml-1">/ Piece</span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price flex items-center gap-2 text-primary font-bold hover:underline transition text-sm">
                                    <span>Unlock Best Price</span>
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                </button>
                                <span class="real-price hidden font-bold text-gray-900"></span>
                            <?php else: ?>
                                <span class="text-gray-500 text-sm font-medium">Get Latest Price on Request</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($product['price_visibility'] === 'public'): ?>
                        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-sm border border-gray-300 font-semibold transition">Get Latest Price</button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- CTA Action Box -->
                <div class="bg-gray-50 border border-gray-200 p-4 rounded-sm flex flex-col sm:flex-row gap-3 mb-8">
                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="flex-1 bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-sm flex items-center justify-center gap-2 text-sm md:text-base transition">
                        Contact Supplier
                    </a>
                    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex-1 bg-white hover:bg-gray-50 border border-primary text-primary font-bold py-3 px-4 rounded-sm flex items-center justify-center gap-2 text-sm md:text-base transition">
                        <i class="fa-solid fa-phone"></i> View Mobile Number
                    </a>
                </div>

                <!-- Product Specifications -->
                <div class="border border-gray-200 rounded-sm overflow-hidden mb-8">
                    <h2 class="bg-gray-100 px-4 py-2 border-b border-gray-200 font-bold text-gray-800 text-sm">Product Specifications</h2>
                    <?php if ($product['specifications']): 
                        $specs = json_decode($product['specifications'], true);
                    ?>
                        <?php if (is_array($specs)): ?>
                            <table class="w-full text-sm text-left border-collapse">
                                <tbody>
                                    <?php $isEven = false; foreach ($specs as $key => $value): ?>
                                        <tr class="border-b border-gray-100 <?php echo $isEven ? 'bg-gray-50' : 'bg-white'; ?>">
                                            <td class="py-3 px-4 text-gray-500 w-1/3 border-r border-gray-100"><?php echo htmlspecialchars($key); ?></td>
                                            <td class="py-3 px-4 text-gray-800 font-medium"><?php echo htmlspecialchars($value); ?></td>
                                        </tr>
                                    <?php $isEven = !$isEven; endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap font-medium">
                                <?php echo htmlspecialchars($product['specifications']); ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="p-4">
                            <p class="text-sm text-gray-500">No specifications provided.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Description -->
                <div class="border border-gray-200 rounded-sm overflow-hidden mb-8">
                    <h2 class="bg-gray-100 px-4 py-2 border-b border-gray-200 font-bold text-gray-800 text-sm">Product Description</h2>
                    <div class="p-4 text-sm text-gray-700 leading-relaxed space-y-4">
                        <?php 
                            if($product['short_description']) echo '<p class="font-medium">' . nl2br(htmlspecialchars($product['short_description'])) . '</p>';
                            if($product['description']) echo '<p>' . nl2br(htmlspecialchars($product['description'])) . '</p>'; 
                        ?>
                    </div>
                </div>

                <!-- Company Details Block (Mimicking IndiaMART) -->
                <div class="border border-gray-200 rounded-sm overflow-hidden">
                    <h2 class="bg-gray-100 px-4 py-2 border-b border-gray-200 font-bold text-gray-800 text-sm">Company Details</h2>
                    <div class="p-4 flex gap-4 items-start">
                        <div class="w-16 h-16 bg-gray-100 border border-gray-200 rounded-sm flex-shrink-0 flex items-center justify-center">
                            <i class="fa-solid fa-building text-gray-400 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-blue-700 text-lg hover:underline cursor-pointer mb-1"><?php echo htmlspecialchars(getSetting('store_name')); ?></h3>
                            <p class="text-xs text-gray-600 mb-1"><i class="fa-solid fa-location-dot text-gray-400 w-4"></i> <?php echo htmlspecialchars(getSetting('address')); ?></p>
                            <p class="text-xs text-gray-600 mb-2"><i class="fa-solid fa-shield-check text-green-600 w-4"></i> TrustSEAL Verified</p>
                            <a href="/contact.php" class="text-xs text-primary font-bold hover:underline">View Company Profile</a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="max-w-[1440px] mx-auto px-2 md:px-4 mt-8 mb-4">
        <div class="bg-white p-3 md:p-4 rounded-sm shadow-sm border border-gray-200">
            <div class="mb-4 flex justify-between items-center border-b border-gray-100 pb-2">
                <h3 class="text-lg md:text-xl font-bold text-gray-800">You may also be interested in</h3>
            </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
            <?php foreach ($relatedProducts as $product): ?>
                <div class="bg-white border border-gray-200 hover:shadow-lg transition-all h-full group flex flex-col rounded-sm overflow-hidden" data-product-id="<?php echo $product['id']; ?>">
                    
                    <!-- Image -->
                    <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block relative w-full aspect-square bg-white border-b border-gray-100 p-2">
                        <?php if($product['primary_image']): ?>
                            <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-contain mix-blend-multiply" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50">
                                <i class="fa-solid fa-image text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    
                    <!-- Content -->
                    <div class="flex-grow flex flex-col p-3">
                        <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block mb-2">
                            <h4 class="text-sm font-medium text-blue-700 hover:underline leading-snug line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                        </a>
                        
                        <div class="price-container mb-3" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-lg text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price text-accent font-semibold text-xs hover:underline flex items-center gap-1">
                                    Unlock Price <i class="fa-solid fa-lock text-[10px]"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-gray-500 text-xs font-semibold">Price on Request</span>
                            <?php endif; ?>
                        </div>

                        <div class="mt-auto">
                            <p class="text-[11px] text-gray-500 mb-3 truncate flex items-center gap-1"><i class="fa-solid fa-location-dot text-gray-400"></i> Purnea, Bihar</p>
                            
                            <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="w-full text-center bg-primary text-white hover:bg-secondary transition px-3 py-2 rounded-sm text-sm font-semibold flex justify-center items-center gap-2">
                                Contact Supplier
                            </a>
                        </div>
                    </div>
                    
                    <button class="absolute top-2 right-2 w-6 h-6 bg-white/90 rounded-full flex items-center justify-center text-red-500 hover:text-gray-400 z-10 wishlist-btn shadow-sm" data-id="<?php echo $product['id']; ?>">
                        <i class="fa-solid fa-heart text-xs"></i>
                    </button>
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
