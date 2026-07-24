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

<div class="bg-slate-50 min-h-screen pb-10">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <style>
        /* Easily change mobile & desktop carousel heights here */
        .hero-banner-container { height: 270px; } /* Mobile Height */
        @media (min-width: 768px) { .hero-banner-container { height: 450px; } } /* Tablet Height */
        @media (min-width: 1024px) { .hero-banner-container { height: 550px; } } /* Desktop Height */
    </style>

    <div class="relative w-full z-10 bg-slate-900 border-b border-gray-200 animate-fade-in-down">
        <!-- Hero Swiper Carousel -->
        <div class="swiper heroSwiper w-full hero-banner-container">
            <div class="swiper-wrapper">
                <?php
                $stmt = $pdo->query("SELECT * FROM carousel_banners ORDER BY order_index ASC");
                $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($banners) > 0):
                    foreach ($banners as $index => $banner):
                        ?>
                        <!-- Slide <?php echo $index + 1; ?> -->
                        <div class="swiper-slide relative">
                            <picture>
                                <source media="(min-width: 768px)"
                                    srcset="<?php echo htmlspecialchars($banner['desktop_image_url']); ?>">
                                <img src="<?php echo htmlspecialchars($banner['mobile_image_url']); ?>"
                                    class="w-full h-full object-fill"
                                    alt="Banner <?php echo $index + 1; ?>">
                            </picture>
                        </div>
                        <?php
                    endforeach;
                else:
                    ?>
                    <!-- Fallback Slide if database is empty -->
                    <div class="swiper-slide relative">
                        <picture>
                            <source media="(min-width: 768px)" srcset="/assets/images/desktop_banner.png">
                            <img src="/assets/images/mobile_banner.png"
                                class="w-full h-auto md:w-full md:h-full md:object-fill" alt="Default Banner">
                        </picture>
                    </div>
                <?php endif; ?>
            </div>
            <!-- Navigation Arrows -->
            <div
                class="swiper-button-next !text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)] bg-black/20 hover:bg-black/50 w-12 h-12 rounded-full backdrop-blur-sm transition-all">
            </div>
            <div
                class="swiper-button-prev !text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)] bg-black/20 hover:bg-black/50 w-12 h-12 rounded-full backdrop-blur-sm transition-all">
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination !bottom-4"></div>

        </div>

    </div>

    <!-- Animated Trust Badges Section -->
    <div class="bg-white border-b border-slate-200 py-4 md:py-12 animate-fade-in-up">
        <div class="max-w-[1440px] mx-auto px-2 md:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-6 text-center">
                <div class="flex flex-col items-center p-3 md:p-6 rounded-xl md:rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group cursor-pointer">
                    <div class="w-10 h-10 md:w-16 md:h-16 rounded-lg md:rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg md:text-2xl mb-2 md:mb-4 group-hover:scale-105 transition-transform border border-blue-100 shadow-sm">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xs md:text-base">100% Genuine</h4>
                    <p class="text-[10px] md:text-xs text-gray-500 mt-0.5 md:mt-1 font-medium">Direct from Top Brands</p>
                </div>
                <div class="flex flex-col items-center p-3 md:p-6 rounded-xl md:rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group cursor-pointer">
                    <div class="w-10 h-10 md:w-16 md:h-16 rounded-lg md:rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-lg md:text-2xl mb-2 md:mb-4 group-hover:scale-105 transition-transform border border-green-100 shadow-sm">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xs md:text-base">Best Prices</h4>
                    <p class="text-[10px] md:text-xs text-gray-500 mt-0.5 md:mt-1 font-medium">Unbeatable Deals</p>
                </div>
                <div class="flex flex-col items-center p-3 md:p-6 rounded-xl md:rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group cursor-pointer">
                    <div class="w-10 h-10 md:w-16 md:h-16 rounded-lg md:rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg md:text-2xl mb-2 md:mb-4 group-hover:scale-105 transition-transform border border-orange-100 shadow-sm">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xs md:text-base">Fast Delivery</h4>
                    <p class="text-[10px] md:text-xs text-gray-500 mt-0.5 md:mt-1 font-medium">Across Bihar</p>
                </div>
                <div class="flex flex-col items-center p-3 md:p-6 rounded-xl md:rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group cursor-pointer">
                    <div class="w-10 h-10 md:w-16 md:h-16 rounded-lg md:rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg md:text-2xl mb-2 md:mb-4 group-hover:scale-105 transition-transform border border-purple-100 shadow-sm">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xs md:text-base">24/7 Support</h4>
                    <p class="text-[10px] md:text-xs text-gray-500 mt-0.5 md:mt-1 font-medium">We're Here to Help</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        function initIndexSwipers() {
            // Main Hero Swiper
            const heroEl = document.querySelector('.heroSwiper');
            if (heroEl && !heroEl.swiper) {
                new Swiper('.heroSwiper', {
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
                    preventClicks: false,
                    preventClicksPropagation: false,
                });
            }

            // Category Product Flip Swipers
            const categorySwipers = document.querySelectorAll('.categorySwiper');
            categorySwipers.forEach(swiperEl => {
                if (!swiperEl.swiper) {
                    const sw = new Swiper(swiperEl, {
                        effect: 'flip',
                        flipEffect: {
                            slideShadows: false,
                        },
                        grabCursor: true,
                        loop: true,
                        autoplay: {
                            delay: 3500,
                            disableOnInteraction: false,
                        },
                        speed: 800,
                        preventClicks: false,
                        preventClicksPropagation: false,
                    });

                    // Allow clicking the container to change the slide
                    swiperEl.addEventListener('click', function (e) {
                        if (!e.target.closest('a') && !e.target.closest('button')) {
                            sw.slideNext();
                        }
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initIndexSwipers);
        document.addEventListener('turbo:load', initIndexSwipers);
    </script>



<!-- Full-Cover Sticky Stack Categories (Premium Flat Light Theme) -->
<?php if (!empty($categoryStrips)): ?>
    <div class="w-full relative bg-gray-50">
        <?php foreach ($categoryStrips as $index => $strip): 
            $imagePath = $strip['products'][0]['primary_image'] ?? null;
            // Alternating flat backgrounds
            $bgColor = ($index % 2 === 0) ? 'bg-white' : 'bg-gray-50';
            
            $mbClass = 'mb-0';
        ?>
            <!-- Unified Card: Exact viewport height -->
            <div class="sticky top-[72px] w-full h-[calc(100vh-72px)] flex flex-col md:flex-row overflow-hidden <?php echo $bgColor . ' ' . $mbClass; ?> border-t border-gray-200">
                 
                <!-- Image Side -->
                <div class="w-full md:w-1/2 h-[45%] md:h-full p-4 md:p-8 lg:p-12 flex items-center justify-center order-1 md:order-2">
                    <div class="w-full h-full rounded-lg overflow-hidden <?php echo ($index % 2 === 0) ? 'bg-gray-100 border border-gray-200' : 'bg-white border border-gray-100'; ?>">
                        <?php if ($imagePath): ?>
                            <img src="/<?php echo htmlspecialchars($imagePath); ?>" class="w-full h-full object-cover" alt="<?php echo htmlspecialchars($strip['category']['name']); ?>">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-image text-6xl text-slate-300"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Text Side -->
                <div class="w-full md:w-1/2 h-[55%] md:h-full flex flex-col order-2 md:order-1 relative z-10 overflow-y-auto hide-scrollbar">
                    
                    <!-- Text Content -->
                    <div class="p-6 md:p-12 lg:p-16 flex flex-col flex-1">
                        <div class="flex items-center gap-3 mb-4 md:mb-6 mt-auto md:mt-0 pt-2 md:pt-0">
                            <span class="w-8 h-[2px] bg-primary"></span>
                            <span class="text-primary font-bold text-xs tracking-[0.2em] uppercase">
                                0<?php echo $index + 1; ?>
                            </span>
                        </div>
                        
                        <h3 class="text-3xl md:text-5xl lg:text-6xl font-black text-slate-900 mb-4 leading-tight tracking-tight">
                            <?php echo htmlspecialchars($strip['category']['name']); ?>
                        </h3>
                        
                        <p class="text-slate-600 text-sm md:text-lg leading-relaxed mb-6 font-medium">
                            <?php echo htmlspecialchars($strip['products'][0]['short_description'] ?? 'Discover state-of-the-art ' . $strip['category']['name'] . ' engineered for maximum productivity, reliability, and superior performance.'); ?>
                        </p>
                    </div>

                    <!-- Button (BottomNav Style on Mobile) -->
                    <div class="sticky bottom-0 left-0 w-full p-4 pb-6 md:p-0 md:w-auto md:static md:px-12 lg:px-16 md:pb-12 lg:pb-16 mt-auto <?php echo $bgColor; ?> md:bg-transparent z-20">
                        <a href="/products?category=<?php echo urlencode($strip['category']['slug']); ?>" class="flex items-center justify-center gap-3 bg-primary hover:bg-[#e66f00] text-white font-bold h-[54px] md:h-auto md:py-4 md:px-8 rounded-md md:rounded-lg transition-colors w-full md:w-max group/btn">
                            <span class="tracking-widest uppercase text-sm">Explore Details</span>
                            <i class="fa-solid fa-arrow-right transition-transform group-hover/btn:translate-x-1"></i>
                        </a>
                    </div>

                </div>
                
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
