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
                <!-- Fresh Flat Hero Section (No Gradients) -->
    <div class="relative w-full pt-16 md:pt-24 pb-24 md:pb-32 bg-slate-900 overflow-hidden z-10">
        <!-- Abstract geometric decoration (Solid Colors, No Gradients) -->
        <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-white opacity-5 rounded-full transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-10 left-10 w-32 h-32 bg-primary opacity-10 rounded-lg rotate-12 pointer-events-none"></div>
        
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-12">
            <!-- Text Content -->
            <div class="w-full md:w-1/2 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-none bg-primary text-white text-xs font-bold tracking-widest uppercase mb-6 shadow-md">
                    <i class="fa-solid fa-map-location-dot"></i> Local Delivery
                </div>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">Machinery in <br class="hidden md:block" /><span class="text-primary"><?php echo htmlspecialchars($currentPlace); ?></span></h1>
                <p class="text-gray-300 text-lg md:text-xl max-w-lg mx-auto md:mx-0 leading-relaxed font-medium">Get the best deals on heavy-duty equipment delivered directly to <?php echo htmlspecialchars($currentPlace); ?>. Contact our suppliers for the latest price.</p>
            </div>
            
            <!-- Image / Visual Side -->
            <div class="w-full md:w-1/2 relative hidden md:block">
                <!-- Solid blocks behind image -->
                <div class="absolute top-4 right-4 w-full h-full bg-primary rounded-2xl -z-10"></div>
                <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/10 rounded-none rotate-45 -z-10"></div>
                
                <div class="rounded-2xl overflow-hidden border-4 border-slate-900 shadow-2xl relative bg-slate-800 flex items-center justify-center">
                    <img src="/assets/images/desktop_banner.png" class="w-full h-72 object-fill opacity-95 hover:opacity-100 transition-opacity duration-300" alt="Showcase">
                </div>
            </div>
        </div>
        
        <!-- Flat Diagonal Cut Bottom -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] z-20 pointer-events-none">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-[40px] md:h-[80px] text-slate-50" fill="currentColor">
                <polygon points="0,120 1200,120 1200,0"></polygon>
            </svg>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 md:px-8">
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
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