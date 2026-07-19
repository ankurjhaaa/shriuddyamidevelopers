<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$locations = require __DIR__ . '/includes/locations.php';
$placeSlug = $_GET['place'] ?? '';

// Find the original place name from the slug (case insensitive match)
$currentPlace = '';
$currentLat = null;
$currentLng = null;
foreach ($locations as $locName => $coords) {
    if (strtolower(str_replace(' ', '-', $locName)) === strtolower($placeSlug)) {
        $currentPlace = $locName;
        $currentLat = $coords['lat'];
        $currentLng = $coords['lng'];
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
$pageDescription = "Find the best agriculture and industrial machines in {$currentPlace}, Bihar. Tractors, cultivators, and equipment available near you at {$storeName}.";
$pageKeywords = strtolower("machines in {$currentPlace}, {$currentPlace} tractor showroom, agriculture equipment {$currentPlace}, {$storeName} {$currentPlace}, machines near me, agriculture machinery bihar, industrial machinery near me");

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
    ],
    "geo" => [
        "@type" => "GeoCoordinates",
        "latitude" => $currentLat,
        "longitude" => $currentLng
    ]
];
$customSchema = '<script type="application/ld+json">' . json_encode($schemaLocalBusiness, JSON_UNESCAPED_SLASHES) . '</script>';

$canonicalUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/location/' . urlencode($placeSlug);

include __DIR__ . '/includes/header.php';
?>
<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Section -->
    <div class="bg-slate-900 py-12 md:py-16 relative overflow-hidden z-10 mb-8">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center px-4">
            <nav class="text-xs text-gray-400 mb-4 font-bold tracking-wider uppercase">
                <a href="/" class="hover:text-primary transition-colors">Home</a> <span class="mx-2">&gt;</span>
                <span class="text-white">Available in <?php echo htmlspecialchars($currentPlace); ?></span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight">Agriculture & Industrial Machinery in
                <span class="text-primary"><?php echo htmlspecialchars($currentPlace); ?></span></h1>
            <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto font-medium">Get the best deals on heavy-duty equipment delivered directly to
                <?php echo htmlspecialchars($currentPlace); ?>. Contact our suppliers for the latest price and catalog.
            </p>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 md:px-8">
        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-6">
            <?php foreach ($products as $product): ?>
                <div class="bg-white border border-slate-200 hover:border-primary transition-all h-full group flex flex-col rounded-xl overflow-hidden wishlist-card relative"
                    data-product-id="<?php echo $product['id']; ?>">

                    <!-- Image -->
                    <a href="/products/<?php echo urlencode($product['slug']); ?>"
                        class="block relative w-full h-[200px] md:h-[220px] bg-slate-50 border-b border-slate-100 p-4 flex items-center justify-center">
                        <?php if ($product['primary_image']): ?>
                            <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>"
                                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-200">
                                <i class="fa-solid fa-image text-4xl group-hover:scale-105 transition-transform duration-300"></i>
                            </div>
                        <?php endif; ?>
                    </a>

                    <!-- Content -->
                    <div class="flex-grow flex flex-col p-4 md:p-5">
                        <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block mb-3 w-full">
                            <h4
                                class="text-sm md:text-base font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug truncate" title="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php echo htmlspecialchars($product['name']); ?></h4>
                        </a>

                        <div class="price-container mb-4" data-product-id="<?php echo $product['id']; ?>"
                            data-price="<?php echo $product['price']; ?>"
                            data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span
                                    class="font-bold text-lg text-gray-900 tracking-tight"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button
                                    class="btn-unlock-price text-primary font-bold text-xs hover:underline flex items-center gap-1">
                                    Unlock Price <i class="fa-solid fa-lock text-[10px]"></i>
                                </button>
                            <?php else: ?>
                                <button
                                    class="btn-unlock-price text-gray-500 text-[11px] md:text-xs font-bold hover:underline flex items-center gap-1">
                                    Get Latest Price
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="mt-auto space-y-3">
                            <p class="text-[11px] md:text-xs text-gray-500 truncate flex items-center gap-1.5 font-medium"><i
                                    class="fa-solid fa-location-dot text-primary"></i>
                                <?php echo htmlspecialchars($currentPlace); ?>, Bihar</p>

                                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" data-turbo="false"
                                        class="w-full flex items-center justify-center gap-2 bg-green-50 text-green-600 border border-green-200 font-bold text-xs md:text-sm py-2.5 rounded-lg hover:bg-green-600 hover:text-white hover:border-green-600 transition-colors">
                                        <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                    </a>
                        </div>
                    </div>

                    <button
                        class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-red-500 hover:text-gray-400 z-30 wishlist-btn shadow-sm transition-colors border border-slate-100"
                        data-id="<?php echo $product['id']; ?>">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($products)): ?>
            <div class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-slate-200 shadow-sm mt-4">
                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-lg flex items-center justify-center text-3xl mb-4 border border-slate-200">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No products found</h3>
                <p class="text-sm text-gray-500 font-medium">Check back later for equipment in
                    <?php echo htmlspecialchars($currentPlace); ?>.</p>
            </div>
        <?php endif; ?>

        <?php if (!empty($products)): ?>
            <div class="mt-12 text-center">
                <a href="/search.php"
                    class="inline-flex items-center gap-2 bg-white border border-slate-200 text-gray-900 font-bold px-8 py-3 rounded-xl hover:border-primary hover:text-primary shadow-sm transition-all text-sm">
                    View All Products <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>