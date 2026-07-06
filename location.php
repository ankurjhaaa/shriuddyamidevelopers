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

include __DIR__ . '/includes/header.php';
?>

<div class="bg-white min-h-screen pb-12">
    <!-- Dynamic Industrial Hero Section -->
    <div class="bg-secondary py-16 px-6 text-center border-b-4 border-primary relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-5" style="background-image: repeating-linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), repeating-linear-gradient(45deg, #000 25%, #1E293B 25%, #1E293B 75%, #000 75%, #000); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
        <div class="max-w-4xl mx-auto relative z-20 animate-fade-in">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 leading-tight uppercase tracking-tight">Machinery in <span class="text-accent"><?php echo htmlspecialchars($currentPlace); ?></span></h1>
            <div class="w-24 h-1 bg-primary mx-auto mb-6"></div>
            <p class="text-gray-300 md:text-lg mb-8 max-w-2xl mx-auto font-medium">Get the best deals on heavy-duty agriculture and industrial machinery delivered right here in <?php echo htmlspecialchars($currentPlace); ?>.</p>
            
            <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="inline-block bg-accent text-primary font-bold px-8 py-3 rounded-full shadow-lg hover:bg-yellow-400 hover:shadow-xl transition transform hover:-translate-y-1">
                <i class="fa-solid fa-phone mr-2"></i> Call Us Now
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        
        <div class="mb-8 flex items-end justify-between">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight mb-2">Available Equipment in <?php echo htmlspecialchars($currentPlace); ?></h2>
                <p class="text-gray-500">Explore our top-selling machinery and tools.</p>
            </div>
            <a href="/search.php" class="hidden sm:inline-flex items-center gap-2 text-primary font-bold hover:underline">View All <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($products as $product): ?>
                <div class="bg-white border border-gray-200 rounded-lg flex flex-row sm:flex-col relative shadow-sm h-full wishlist-card" data-product-id="<?php echo $product['id']; ?>">
                    <button class="absolute top-2 right-2 w-7 h-7 bg-gray-50 sm:bg-white/80 sm:backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 z-10 wishlist-btn shadow-sm text-xs" data-id="<?php echo $product['id']; ?>">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                    
                    <!-- Image -->
                    <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block relative w-2/5 sm:w-full aspect-square bg-white rounded-l-lg sm:rounded-t-lg sm:rounded-bl-none overflow-hidden border-r sm:border-r-0 sm:border-b border-gray-200 shrink-0">
                        <?php if($product['primary_image']): ?>
                            <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-cover" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    
                    <!-- Content -->
                    <div class="p-3 flex-grow flex flex-col justify-between w-3/5 sm:w-full">
                        <div>
                            <p class="text-[9px] sm:text-[10px] text-gray-500 font-medium mb-0.5 uppercase tracking-wider truncate pr-6"><?php echo htmlspecialchars($product['category_name']); ?></p>
                            <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block pr-6 sm:pr-0">
                                <h4 class="text-xs sm:text-sm font-semibold text-gray-900 leading-snug mb-1 line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                            </a>
                        </div>
                        
                        <div class="mt-2 pt-2 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5 sm:gap-0">
                            <!-- Price Logic -->
                            <div class="price-container" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                                <?php if ($product['price_visibility'] === 'public'): ?>
                                    <span class="font-bold text-sm sm:text-base text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                                <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                    <button class="btn-unlock-price flex items-center gap-1 text-primary font-medium text-[10px] sm:text-xs bg-blue-50 px-2 py-1 rounded w-fit">
                                        <span>₹ *****</span>
                                        <i class="fa-solid fa-lock text-[9px]"></i>
                                    </button>
                                    <span class="real-price hidden font-bold text-sm sm:text-base text-gray-900"></span>
                                <?php else: ?>
                                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="text-[10px] sm:text-xs text-primary font-medium">Ask Price</a>
                                <?php endif; ?>
                            </div>
                            
                            <a href="/products/<?php echo urlencode($product['slug']); ?>" class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] text-primary font-bold hover:underline w-fit">
                                View Details <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-6 text-center sm:hidden">
            <a href="/search.php" class="inline-block border border-gray-200 text-gray-700 font-bold px-6 py-2.5 rounded-lg w-full text-sm">View All Products</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
