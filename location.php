<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$locations = require __DIR__ . '/includes/locations.php';
$placeSlug = $_GET['place'] ?? '';

// Find the original place name from the slug (case insensitive match)
$currentPlace = '';
foreach ($locations as $loc) {
    if (strtolower(str_replace(' ', '-', $loc)) === strtolower($placeSlug)) {
        $currentPlace = $loc;
        break;
    }
}

if (!$currentPlace) {
    header("Location: /404.php");
    exit;
}

// Fetch some popular/featured products for the landing page
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, 
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
    ORDER BY p.id DESC LIMIT 8
");
$stmt->execute();
$products = $stmt->fetchAll();

// Dynamic SEO setup for the specific location
$storeName = htmlspecialchars(getSetting('store_name'));
$pageTitle = "Agriculture & Industrial Machines in {$currentPlace} - {$storeName}";
$pageDescription = "Find the best agriculture and industrial machines in {$currentPlace}. Tractors, cultivators, and equipment available near you at {$storeName}.";
$pageKeywords = strtolower("machines in {$currentPlace}, {$currentPlace} tractor showroom, agriculture equipment {$currentPlace}, {$storeName} {$currentPlace}");

// Schema setup
$schemaLocalBusiness = [
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "name" => "{$storeName} - {$currentPlace}",
    "image" => 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/assets/images/logo.png',
    "description" => $pageDescription,
    "telephone" => htmlspecialchars(getSetting('phone')),
    "address" => [
        "@type" => "PostalAddress",
        "addressLocality" => $currentPlace,
        "addressRegion" => "Bihar",
        "addressCountry" => "IN"
    ]
];
$customSchema = '<script type="application/ld+json">' . json_encode($schemaLocalBusiness, JSON_UNESCAPED_SLASHES) . '</script>';

$canonicalUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/location/' . urlencode($placeSlug);

include __DIR__ . '/includes/header.php';
?>
<div class="bg-gray-100 min-h-screen pb-16">
    <!-- Clean Header -->
    <div class="bg-white border-b border-gray-200 py-6 px-4 mb-6">
        <div class="max-w-[1440px] mx-auto">
            <nav class="text-xs text-gray-500 mb-2 font-medium">
                <a href="/" class="hover:text-primary">Home</a> &gt; 
                <span class="text-gray-800">Available in <?php echo htmlspecialchars($currentPlace); ?></span>
            </nav>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Agriculture & Industrial Machinery in <?php echo htmlspecialchars($currentPlace); ?></h1>
            <p class="text-sm text-gray-600 max-w-3xl">Get the best deals on heavy-duty equipment delivered directly to <?php echo htmlspecialchars($currentPlace); ?>. Contact our suppliers for the latest price and catalog.</p>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-2 md:px-4">
        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
            <?php foreach ($products as $product): ?>
                <div class="bg-white border border-gray-200 hover:shadow-lg transition-all h-full group flex flex-col rounded-sm overflow-hidden" data-product-id="<?php echo $product['id']; ?>">
                    
                    <!-- Image -->
                    <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block relative w-full aspect-square bg-white border-b border-gray-100 p-2">
                        <?php if($product['primary_image']): ?>
                            <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-contain mix-blend-multiply" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    
                    <!-- Content -->
                    <div class="flex-grow flex flex-col p-2 md:p-3">
                        <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block mb-2">
                            <h4 class="text-xs md:text-sm font-medium text-blue-700 hover:underline leading-snug line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                        </a>
                        
                        <div class="price-container mb-2" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-base md:text-lg text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price text-accent font-semibold text-[10px] md:text-xs hover:underline flex items-center gap-1">
                                    Unlock Price <i class="fa-solid fa-lock text-[9px]"></i>
                                </button>
                            <?php else: ?>
                                <button class="btn-unlock-price text-gray-500 text-[10px] md:text-xs font-semibold hover:underline flex items-center gap-1">
                                    Get Latest Price
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="mt-auto">
                            <p class="text-[10px] md:text-[11px] text-gray-500 mb-2 truncate flex items-center gap-1"><i class="fa-solid fa-location-dot text-gray-400"></i> <?php echo htmlspecialchars($currentPlace); ?>, Bihar</p>
                            
                            <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="w-full text-center bg-primary text-white hover:bg-secondary transition px-2 py-1.5 rounded-sm text-[11px] md:text-xs font-semibold flex justify-center items-center gap-1">
                                Contact Supplier
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($products)): ?>
            <div class="flex flex-col items-center justify-center py-16 bg-white rounded-sm border border-gray-200">
                <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                <h3 class="text-base font-semibold text-gray-800 mb-1">No products found</h3>
                <p class="text-xs text-gray-500">Check back later for equipment in <?php echo htmlspecialchars($currentPlace); ?>.</p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($products)): ?>
        <div class="mt-8 text-center">
            <a href="/search.php" class="inline-block bg-white border border-gray-300 text-primary font-bold px-8 py-2.5 rounded-sm shadow-sm hover:bg-gray-50 transition text-sm">View All Products in Bihar</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
