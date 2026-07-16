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

    <div class="relative w-full z-10 bg-white border-b border-gray-200">
        <!-- Responsive heights: Auto on mobile (perfect square), Fixed smaller height on laptop -->
        <div class="swiper heroSwiper w-full md:h-[400px] lg:h-[450px] xl:h-[500px]">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide relative">
                    <picture>
                        <source media="(min-width: 768px)" srcset="/assets/images/carousel/desk_1.png">
                        <img src="/assets/images/carousel/mobile_1.png" class="w-full h-auto md:w-full md:h-full md:object-fill" alt="Banner 1">
                    </picture>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide relative">
                    <picture>
                        <source media="(min-width: 768px)" srcset="/assets/images/carousel/desk_2.png">
                        <img src="/assets/images/carousel/mobile_2.png" class="w-full h-auto md:w-full md:h-full md:object-fill" alt="Banner 2">
                    </picture>
                </div>
                <!-- Slide 3 -->
                <div class="swiper-slide relative">
                    <picture>
                        <source media="(min-width: 768px)" srcset="/assets/images/carousel/desk_3.png">
                        <img src="/assets/images/carousel/mobile3.png" class="w-full h-auto md:w-full md:h-full md:object-fill" alt="Banner 3">
                    </picture>
                </div>
            </div>
            <!-- Navigation Arrows -->
            <div class="swiper-button-next" style="color: #00a699;"></div>
            <div class="swiper-button-prev" style="color: #00a699;"></div>
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
                effect: 'slide',
                speed: 600,
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



    <!-- Horizontal Product Strips -->
    <?php foreach ($categoryStrips as $index => $strip): ?>
        <div class="bg-white <?php echo $index === 0 ? 'py-6 md:py-10' : 'pb-6 md:pb-10'; ?>">
            <div class="max-w-[1440px] mx-auto px-4 md:px-8">
                <div class="bg-white p-0">
                    <div class="flex justify-between items-center mb-4 md:mb-6">
                        <h3 class="text-base md:text-xl font-bold text-gray-800 truncate pr-4">
                            <?php echo htmlspecialchars($strip['category']['name']); ?></h3>
                        <a href="/category/<?php echo urlencode($strip['category']['slug']); ?>"
                            class="text-primary text-xs md:text-sm font-semibold hover:underline flex-shrink-0">View All</a>
                    </div>

                    <div class="flex overflow-x-auto gap-3 md:gap-4 hide-scrollbar pb-2 snap-x">
                        <?php foreach ($strip['products'] as $product): ?>
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
                                        <h4
                                            class="text-sm md:text-base font-semibold text-gray-800 hover:text-primary transition-colors leading-snug truncate">
                                            <?php echo htmlspecialchars($product['name']); ?></h4>
                                    </a>

                                    <div class="price-container mb-2" data-product-id="<?php echo $product['id']; ?>"
                                        data-price="<?php echo $product['price']; ?>"
                                        data-visibility="<?php echo $product['price_visibility']; ?>">
                                        <?php if ($product['price_visibility'] === 'public'): ?>
                                            <span
                                                class="font-bold text-lg md:text-xl text-gray-900"><?php echo formatPrice($product['price']); ?></span>
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
    class="fixed bottom-24 md:bottom-8 right-6 z-40 bg-green-500 text-white w-12 h-12 rounded-sm flex items-center justify-center shadow-lg">
    <i class="fa-brands fa-whatsapp text-2xl"></i>
</a>

<?php include __DIR__ . '/includes/footer.php'; ?>