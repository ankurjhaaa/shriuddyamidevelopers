<?php
// Fix for local PHP development server (php -S) when router.php is not specified
if (php_sapi_name() === 'cli-server' && !defined('ROUTER_LOADED')) {
    define('ROUTER_LOADED', true);
    $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($uri !== '/' && $uri !== '/index.php') {
        require __DIR__ . '/router.php';
        exit;
    }
}

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Home - Agriculture & Industrial Machines in Purnea';
$pageDescription = 'Purnea Machine Bazaar is the leading provider of agriculture, farming, and industrial machines near you in Purnea, Bihar. Get the best price on tractors, cultivators, and more across Bihar.';
$pageKeywords = 'purnea machine bazaar, agriculture machines near me, industrial machines bihar, tractors near me, farming equipment bihar, machinery supplier near me';

include __DIR__ . '/includes/header.php';



// Fetch products grouped by category for the horizontal strips
$categoriesWithProducts = $pdo->query("
    SELECT c.* FROM categories c
    WHERE EXISTS (SELECT 1 FROM products p WHERE p.category_id = c.id AND p.status = 'active')
    ORDER BY c.id ASC
")->fetchAll();

$categoryStrips = [];
foreach ($categoriesWithProducts as $cat) {
    $stmt = $pdo->prepare("
        SELECT p.*, ? as category_name, 
               (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
        FROM products p
        WHERE p.category_id = ? AND p.status = 'active'
        ORDER BY p.id DESC LIMIT 10
    ");
    $stmt->execute([$cat['name'], $cat['id']]);
    $prods = $stmt->fetchAll();

    if (!empty($prods)) {
        $categoryStrips[] = [
            'category' => $cat,
            'products' => $prods
        ];
    }
}
?>

<div class="bg-white min-h-screen pb-10">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- IndiaMART Style Full-Width Banner -->
    <div class="relative w-full z-10 bg-gray-100 border-b border-gray-200">
        <div class="swiper heroSwiper w-full h-[300px] md:h-[350px]">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide relative">
                    <img src="/assets/images/carousel/1.png" class="w-full h-full object-cover"
                        alt="Industrial Tractor">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 to-transparent z-10"></div>
                    <div class="absolute inset-0 z-20 flex flex-col justify-center px-12">
                        <h1 class="text-3xl font-bold text-white mb-2 shadow-sm">Heavy Machinery Deals</h1>
                        <p class="text-white/90 text-sm max-w-sm mb-4">Discover premium tractors and agriculture
                            equipment.</p>
                        <a href="/search.php"
                            class="bg-accent text-white px-5 py-2 rounded-sm font-medium w-fit shadow-sm text-sm hover:bg-orange-600 transition">Shop
                            Now</a>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide relative">
                    <img src="/assets/images/carousel/2.png" class="w-full h-full object-cover"
                        alt="Industrial Harvester">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/90 to-transparent z-10"></div>
                    <div class="absolute inset-0 z-20 flex flex-col justify-center px-12">
                        <h1 class="text-3xl font-bold text-white mb-2 shadow-sm">Advanced Harvesters</h1>
                        <p class="text-white/90 text-sm max-w-sm mb-4">Increase yield with state-of-the-art harvesters.
                        </p>
                        <a href="/search.php"
                            class="bg-accent text-white px-5 py-2 rounded-sm font-medium w-fit shadow-sm text-sm hover:bg-orange-600 transition">Shop
                            Now</a>
                    </div>
                </div>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('.heroSwiper', {
                loop: true,
                effect: 'fade',
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>



    <!-- IndiaMART Style Horizontal Product Strips (Category Wise) -->
    <?php foreach ($categoryStrips as $index => $strip): ?>
        <div class="bg-gray-100 <?php echo $index === 0 ? 'py-4 md:py-6' : 'pb-4 md:pb-6'; ?>">
            <div class="max-w-[1440px] mx-auto px-2 md:px-4">
                <div class="bg-white p-3 md:p-4 rounded-sm shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800">
                            <?php echo htmlspecialchars($strip['category']['name']); ?></h3>
                        <a href="/category/<?php echo urlencode($strip['category']['slug']); ?>"
                            class="text-primary text-sm font-semibold hover:underline">View All</a>
                    </div>

                    <div class="flex overflow-x-auto gap-3 md:gap-4 hide-scrollbar pb-2 snap-x">
                        <?php foreach ($strip['products'] as $product): ?>
                            <div class="w-[160px] md:w-[220px] flex-shrink-0 bg-white border border-gray-200 hover:shadow-lg transition-all h-full group flex flex-col rounded-sm overflow-hidden snap-start"
                                data-product-id="<?php echo $product['id']; ?>">

                                <!-- Image -->
                                <a href="/products/<?php echo urlencode($product['slug']); ?>"
                                    class="block relative w-full h-[180px] bg-white border-b border-gray-100 p-2 flex items-center justify-center">
                                    <?php if ($product['primary_image']): ?>
                                        <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>"
                                            class="w-full h-full object-cover mix-blend-multiply" loading="lazy">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50">
                                            <i class="fa-solid fa-image text-3xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                <!-- Content -->
                                <div class="flex-grow flex flex-col p-2 md:p-3">
                                    <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block mb-2">
                                        <h4
                                            class="text-xs md:text-sm font-medium text-blue-700 hover:underline leading-snug line-clamp-2">
                                            <?php echo htmlspecialchars($product['name']); ?></h4>
                                    </a>

                                    <div class="price-container mb-2" data-product-id="<?php echo $product['id']; ?>"
                                        data-price="<?php echo $product['price']; ?>"
                                        data-visibility="<?php echo $product['price_visibility']; ?>">
                                        <?php if ($product['price_visibility'] === 'public'): ?>
                                            <span
                                                class="font-bold text-base md:text-lg text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                                        <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                            <button
                                                class="btn-unlock-price text-accent font-semibold text-[10px] md:text-xs hover:underline flex items-center gap-1">
                                                Unlock Price <i class="fa-solid fa-lock text-[9px]"></i>
                                            </button>
                                        <?php else: ?>
                                            <button
                                                class="btn-unlock-price text-gray-500 text-[10px] md:text-xs font-semibold hover:underline flex items-center gap-1">
                                                Get Latest Price
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-auto">
                                        <p
                                            class="text-[10px] md:text-[11px] text-gray-500 mb-2 truncate flex items-center gap-1">
                                            <i class="fa-solid fa-location-dot text-gray-400"></i> Purnea, Bihar</p>

                                        <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank"
                                            class="w-full text-center bg-primary text-white hover:bg-secondary transition px-2 py-1.5 rounded-sm text-[11px] md:text-xs font-semibold flex justify-center items-center gap-1">
                                            Contact Supplier
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .hide-scrollbar {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }
</style>

<!-- Floating WhatsApp Button -->
<a href="<?php echo getWhatsappLink(); ?>" target="_blank"
    class="fixed bottom-24 md:bottom-8 right-6 z-40 bg-green-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg">
    <i class="fa-brands fa-whatsapp text-2xl"></i>
</a>

<?php include __DIR__ . '/includes/footer.php'; ?>