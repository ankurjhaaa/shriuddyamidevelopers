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

// Build Story Playlist JSON
$storyPlaylist = [];
$storyPlaylist[] = [
    'id' => $product['id'],
    'name' => $product['name'],
    'slug' => $product['slug'],
    'price' => $product['price'],
    'price_visibility' => $product['price_visibility'],
    'images' => $images
];

foreach ($relatedProducts as $rp) {
    $rpImgStmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC");
    $rpImgStmt->execute([$rp['id']]);
    $rpImages = $rpImgStmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($rpImages) && $rp['primary_image']) {
        $rpImages = [$rp['primary_image']];
    }

    if (!empty($rpImages)) {
        $storyPlaylist[] = [
            'id' => $rp['id'],
            'name' => $rp['name'],
            'slug' => $rp['slug'],
            'price' => $rp['price'],
            'price_visibility' => $rp['price_visibility'],
            'images' => $rpImages
        ];
    }
}

// Programmatic SEO setup
$pageTitle = $product['name'] . ' in Purnea - Best Price';
$baseDesc = $product['short_description'] ? $product['short_description'] : 'Buy ' . $product['name'] . ' near you in Bihar at best prices. High-quality ' . strtolower($product['category_name']) . ' from Purnea Machine Bazaar.';
$pageDescription = $baseDesc . ' Contact us for latest price and specifications.';
$pageKeywords = strtolower($product['name']) . ' near me, buy ' . strtolower($product['name']) . ' bihar, ' . strtolower($product['category_name']) . ' near me, Purnea Machine Bazaar, agriculture machinery bihar';

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

$ogImage = $schemaImage;
$ogType = 'product';
$canonicalUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/products/' . urlencode($product['slug']);

include __DIR__ . '/includes/header.php';
?>

<div class="bg-white min-h-screen pb-24 md:pb-12 pt-0 md:pt-4">
    <div class="max-w-[1440px] mx-auto px-0 md:px-8">

        <!-- Breadcrumb -->
        <div class="text-[11px] text-gray-500 mb-4 hidden md:block mt-4">
            <a href="/" class="hover:text-primary">Home</a> &rsaquo;
            <a href="/search.php?category=<?php echo htmlspecialchars($product['category_id']); ?>"
                class="hover:text-primary"><?php echo htmlspecialchars($product['category_name']); ?></a> &rsaquo;
            <span class="text-gray-800 font-semibold"><?php echo htmlspecialchars($product['name']); ?></span>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 lg:items-start relative">

            <!-- Left: Sticky Image Gallery -->
            <div
                class="w-full lg:w-[450px] xl:w-[500px] flex-shrink-0 bg-white md:rounded-md border-b md:border md:border-gray-200 p-0 md:p-4 lg:sticky lg:top-[70px] relative">
                
                <!-- Wishlist Button -->
                <button
                    class="absolute top-6 right-6 w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 z-10 wishlist-btn shadow-sm transition-colors"
                    data-id="<?php echo $product['id']; ?>">
                    <i class="fa-regular fa-heart text-lg"></i>
                </button>
                <?php if (!empty($images)): ?>
                    <div
                        class="swiper product-gallery w-full aspect-square md:border md:border-gray-100 md:rounded-md overflow-hidden mb-2 bg-gray-50 md:bg-white">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $index => $img): ?>
                                <div class="swiper-slide flex items-center justify-center p-0 md:p-2">
                                    <img src="/<?php echo htmlspecialchars($img); ?>"
                                        class="max-h-full max-w-full object-contain mix-blend-multiply cursor-pointer lb-trigger"
                                        data-index="<?php echo $index; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <!-- Thumbnails -->
                    <?php if (count($images) > 1): ?>
                        <div class="flex gap-2 overflow-x-auto mt-3 pb-2">
                            <?php foreach ($images as $index => $img): ?>
                                <div class="w-16 h-16 border border-gray-200 rounded-md overflow-hidden flex-shrink-0 cursor-pointer hover:border-primary transition-colors thumbnail-item <?php echo $index === 0 ? 'border-primary border-2' : ''; ?>"
                                    data-index="<?php echo $index; ?>">
                                    <img src="/<?php echo htmlspecialchars($img); ?>" class="w-full h-full object-cover lb-trigger"
                                        data-index="<?php echo $index; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div
                        class="w-full aspect-square border border-gray-100 rounded-md flex items-center justify-center text-gray-300 bg-white mb-2">
                        <i class="fa-solid fa-image text-5xl"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Product Details & Specs -->
            <div class="w-full flex-grow bg-white md:rounded-md md:border md:border-gray-200 p-4 md:p-6 lg:p-8">
                <div class="mb-4">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 leading-snug mb-3">
                        <?php echo htmlspecialchars($product['name']); ?></h1>

                    <!-- Price Block -->
                    <div class="flex items-center gap-3">
                        <div class="price-container text-2xl md:text-3xl"
                            data-product-id="<?php echo $product['id']; ?>"
                            data-price="<?php echo $product['price']; ?>"
                            data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                                <span class="text-sm text-gray-500 font-normal ml-1">/ Piece</span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button
                                    class="btn-unlock-price flex items-center gap-2 text-primary font-bold hover:underline transition text-sm">
                                    <span>Unlock Best Price</span>
                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                </button>
                                <span class="real-price hidden font-bold text-gray-900"></span>
                            <?php else: ?>
                                <button
                                    class="btn-unlock-price flex items-center gap-2 text-gray-500 font-bold hover:underline transition text-sm">
                                    Get Latest Price
                                </button>
                            <?php endif; ?>
                        </div>

                        <?php if ($product['price_visibility'] === 'public'): ?>
                            <button
                                class="bg-white hover:bg-gray-50 text-gray-700 text-xs px-2 py-1 rounded-md border border-gray-300 font-semibold transition">Get
                                Latest Price</button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CTA Action Box -->
                <div class="hidden md:flex bg-white border border-gray-200 p-4 rounded-md flex-col sm:flex-row gap-3 mb-8">
                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank"
                        class="flex-1 bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-md flex items-center justify-center gap-2 text-sm md:text-base transition">
                        Contact Supplier
                    </a>
                    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>"
                        class="flex-1 bg-white hover:bg-gray-50 border border-primary text-primary font-bold py-3 px-4 rounded-md flex items-center justify-center gap-2 text-sm md:text-base transition">
                        <i class="fa-solid fa-phone"></i> View Mobile Number
                    </a>
                </div>

                <!-- Product Specifications -->
                <div class="border border-gray-200 rounded-md overflow-hidden mb-8">
                    <h2 class="bg-white px-4 py-2 border-b border-gray-200 font-bold text-gray-800 text-sm">Product
                        Specifications</h2>
                    <?php if ($product['specifications']):
                        $specs = json_decode($product['specifications'], true);
                        ?>
                        <?php if (is_array($specs)): ?>
                            <table class="w-full text-sm text-left border-collapse">
                                <tbody>
                                    <?php $isEven = false;
                                    foreach ($specs as $key => $value): ?>
                                        <tr class="border-b border-gray-100 <?php echo $isEven ? 'bg-white' : 'bg-white'; ?>">
                                            <td class="py-3 px-4 text-gray-500 w-1/3 border-r border-gray-100">
                                                <?php echo htmlspecialchars($key); ?></td>
                                            <td class="py-3 px-4 text-gray-800 font-medium"><?php echo htmlspecialchars($value); ?>
                                            </td>
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
                <div class="border border-gray-200 rounded-md overflow-hidden mb-8">
                    <h2 class="bg-white px-4 py-2 border-b border-gray-200 font-bold text-gray-800 text-sm">Product
                        Description</h2>
                    <div class="p-4 text-sm text-gray-700 leading-relaxed space-y-4">
                        <?php
                        if ($product['short_description'])
                            echo '<p class="font-medium">' . nl2br(htmlspecialchars($product['short_description'])) . '</p>';
                        if ($product['description'])
                            echo '<p>' . nl2br(htmlspecialchars($product['description'])) . '</p>';
                        ?>
                    </div>
                </div>

                <!-- Company Details Block (Mimicking IndiaMART) -->
                <div class="border border-gray-200 rounded-md overflow-hidden">
                    <h2 class="bg-white px-4 py-2 border-b border-gray-200 font-bold text-gray-800 text-sm">Company
                        Details</h2>
                    <a href="/company.php"
                        class="p-4 flex gap-4 items-start block hover:bg-white transition cursor-pointer group">
                        <div
                            class="w-16 h-16 bg-white border border-gray-200 rounded-md flex-shrink-0 flex items-center justify-center overflow-hidden">
                            <img src="/assets/images/logo.png" alt="Company Logo"
                                class="max-w-full max-h-full object-contain p-1">
                        </div>
                        <div>
                            <h3 class="font-bold text-blue-700 text-lg group-hover:underline mb-1">Shri Uddyami
                                Developers</h3>
                            <p class="text-xs text-gray-600 mb-1"><i
                                    class="fa-solid fa-location-dot text-gray-400 w-4"></i>
                                <?php echo htmlspecialchars(getSetting('address')); ?></p>
                            <p class="text-xs text-gray-600 mb-2"><i
                                    class="fa-solid fa-shield-check text-green-600 w-4"></i> TrustSEAL Verified</p>
                            <span class="text-xs text-primary font-bold group-hover:underline">View Company Profile <i
                                    class="fa-solid fa-chevron-right text-[10px] ml-1"></i></span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="max-w-[1440px] mx-auto px-4 md:px-8 mt-6 md:mt-10 mb-10">
        <?php if (!empty($relatedProducts)): ?>
            <div class="bg-white p-3 md:p-4 rounded-md shadow-sm border border-gray-200">
                <div class="mb-4 flex justify-between items-center border-b border-gray-100 pb-2">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800">You may also be interested in</h3>
                </div>
                <div class="flex overflow-x-auto gap-3 md:gap-4 hide-scrollbar pb-2 snap-x">
                    <?php foreach ($relatedProducts as $product): ?>
                        <div class="w-[220px] md:w-[240px] flex-shrink-0 bg-white border border-gray-200 hover:border-primary/50 transition-colors h-full group flex flex-col rounded-md overflow-hidden snap-start relative wishlist-card"
                            data-product-id="<?php echo $product['id']; ?>">

                            <!-- Image -->
                            <a href="/products/<?php echo urlencode($product['slug']); ?>"
                                class="block relative w-full h-[180px] md:h-[200px] bg-white border-b border-gray-100 p-3 flex items-center justify-center group-hover:bg-blue-50/30 transition-colors">
                                <?php if ($product['primary_image']): ?>
                                    <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>"
                                        class="w-full h-full object-cover mix-blend-multiply" loading="lazy">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50 rounded-t-md">
                                        <i class="fa-solid fa-image text-3xl"></i>
                                    </div>
                                <?php endif; ?>
                            </a>

                            <!-- Content -->
                            <div class="flex-grow flex flex-col p-3 md:p-4">
                                <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block mb-2 w-full">
                                    <h4 class="text-sm md:text-base font-semibold text-gray-800 hover:text-primary transition-colors leading-snug truncate">
                                        <?php echo htmlspecialchars($product['name']); ?></h4>
                                </a>

                                <div class="price-container mb-2" data-product-id="<?php echo $product['id']; ?>"
                                    data-price="<?php echo $product['price']; ?>"
                                    data-visibility="<?php echo $product['price_visibility']; ?>">
                                    <?php if ($product['price_visibility'] === 'public'): ?>
                                        <span class="font-bold text-lg md:text-xl text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                                    <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                        <button
                                            class="btn-unlock-price text-accent font-semibold text-xs hover:underline flex items-center gap-1">
                                            Unlock Price <i class="fa-solid fa-lock text-[10px]"></i>
                                        </button>
                                    <?php else: ?>
                                        <button
                                            class="btn-unlock-price text-gray-500 text-[10px] md:text-xs font-semibold hover:underline flex items-center gap-1">
                                            Get Latest Price
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-auto">
                                    <p class="text-[10px] md:text-[11px] text-gray-500 mb-2 truncate flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot text-gray-400"></i> Purnea, Bihar</p>

                                    <a href="<?php echo getWhatsappLink('Hi, I am interested in ' . $product['name']); ?>" target="_blank"
                                        class="w-full block text-center bg-primary text-white font-medium text-xs md:text-sm py-2 rounded-md hover:bg-secondary transition-colors">
                                        Contact Supplier
                                    </a>
                                </div>
                            </div>

                            <button
                                class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 z-10 wishlist-btn shadow-sm transition-colors"
                                data-id="<?php echo $product['id']; ?>">
                                <i class="fa-regular fa-heart text-sm"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Sticky Call to Action for Product Page (Mobile Only) -->
    <div class="md:hidden fixed bottom-0 w-full bg-white border-t border-gray-200 z-50 p-3 flex gap-2 shadow-lg">
        <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>"
            class="flex-1 bg-white border border-gray-300 text-gray-700 font-semibold py-2.5 rounded-md flex items-center justify-center gap-2 text-sm shadow-sm">
            <i class="fa-solid fa-phone text-primary"></i> Call
        </a>
        <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank"
            class="flex-[2] bg-primary text-white font-semibold py-2.5 rounded-md flex items-center justify-center gap-2 text-sm shadow-sm hover:bg-secondary transition">
            Get Latest Price
        </a>
    </div>


    <!-- Full Screen Image Lightbox -->
    <div id="productLightbox"
        class="fixed inset-0 z-[100] hidden flex-col md:flex-row bg-black/95 backdrop-blur-sm transition-opacity duration-300 opacity-0"
        style="display: none;">

        <!-- Left: Image Viewer Area -->
        <div class="flex-grow h-[50vh] md:h-full relative flex items-center justify-center p-4">
            <!-- Close Button -->
            <button id="closeLightbox"
                class="absolute top-4 left-4 md:left-6 z-10 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-md flex items-center justify-center text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <!-- Navigation Arrows -->
            <button id="lbPrevBtn"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white/10 hover:bg-white/30 rounded-md flex items-center justify-center text-white transition-colors">
                <i class="fa-solid fa-chevron-left text-xl"></i>
            </button>
            <button id="lbNextBtn"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white/10 hover:bg-white/30 rounded-md flex items-center justify-center text-white transition-colors">
                <i class="fa-solid fa-chevron-right text-xl"></i>
            </button>

            <!-- Main Image -->
            <img id="lbMainImage" src="" alt="Product Image"
                class="max-h-full max-w-full object-contain drop-shadow-2xl select-none">

            <!-- Top Progress Bars (Story Style) -->
            <div id="lbProgressContainer"
                class="absolute top-4 left-4 right-4 md:left-20 md:right-20 flex gap-1 z-20 h-1.5">
                <!-- Progress bars will be injected here via JS -->
            </div>
        </div>

        <!-- Right: Quick Requirement Form Panel -->
        <div class="w-full md:w-[350px] lg:w-[400px] h-[50vh] md:h-full bg-white flex-shrink-0 flex flex-col overflow-y-auto transform md:translate-x-full transition-transform duration-300"
            id="lbSidebar">
            <!-- Header -->
            <div
                class="bg-primary text-white py-4 px-5 text-center font-bold text-lg sticky top-0 z-10 shadow-sm border-b border-primary-dark">
                Quick Requirement Form
            </div>

            <div class="p-6 flex flex-col gap-6 flex-grow">
                <!-- Product Info -->
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-arrow-up-right-from-square text-blue-700 mt-1.5"></i>
                    <div>
                        <h3 id="lbProductName" class="font-semibold text-gray-900 text-lg leading-tight mb-2">
                            <?php echo htmlspecialchars($product['name']); ?></h3>
                        <div id="lbProductPrice" class="text-xl font-bold text-gray-800">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <?php echo formatPrice($product['price']); ?>
                            <?php else: ?>
                                <span class="text-sm font-semibold text-primary">Price on Request</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Company Info Box -->
                <div class="bg-white border border-gray-100 rounded-md p-5">
                    <h4 class="font-medium text-gray-800 mb-1">Shri Uddyami Developers</h4>
                    <p class="text-sm text-gray-500 mb-3"><i class="fa-solid fa-location-dot mr-1 text-gray-400"></i>
                        Purnea, Bihar</p>
                    <div
                        class="flex items-center gap-1.5 text-xs font-bold text-green-700 bg-green-100/50 px-2.5 py-1.5 rounded inline-flex">
                        <i class="fa-solid fa-shield-check"></i> TrustSEAL Verified
                    </div>
                </div>

                <!-- CTAs -->
                <div class="flex flex-col gap-3 mt-auto pt-4">
                    <a id="lbEnquireBtn"
                        href="<?php echo getWhatsappLink('Hi, I am interested in ' . $product['name'] . '. Can you send me a quote?'); ?>"
                        target="_blank"
                        class="w-full bg-primary hover:bg-secondary text-white py-3.5 rounded-md transition font-bold text-base flex justify-center items-center shadow-md">
                        Enquire Now
                    </a>
                    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>"
                        class="w-full bg-white border-2 border-primary text-primary hover:bg-primary/5 py-3 rounded-md transition font-bold text-base flex justify-center items-center shadow-sm gap-2">
                        <i class="fa-solid fa-phone"></i> Call Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Overwrite bottom nav spacing so it doesn't overlap the CTA on this page -->
    <style>
        body {
            padding-bottom: 0 !important;
        }

        nav.fixed.bottom-0 {
            display: none !important;
        }
    </style>

    <script>
        window.PRODUCT_DATA = {
            id: <?php echo $product['id']; ?>,
            name: <?php echo json_encode($product['name']); ?>
        };
        window.STORY_PLAYLIST = <?php echo json_encode($storyPlaylist); ?>;
    </script>

    <?php include __DIR__ . '/includes/footer.php'; ?>