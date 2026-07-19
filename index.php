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

    <div class="relative w-full z-10 bg-slate-900 border-b border-gray-200 animate-fade-in-down ">
        <!-- Responsive heights: Auto on mobile (perfect square), Fixed smaller height on laptop -->
        <div class="swiper heroSwiper w-full md:h-[400px] lg:h-[450px] xl:h-[500px]">
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
                                    class="w-full h-auto md:w-full md:h-full md:object-fill"
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

        <!-- Reference Style Floating Banner Buttons (Outside Swiper) -->
        <div class="absolute bottom-6 md:bottom-12 left-4 md:left-12 z-30 flex flex-col gap-3 pointer-events-none">
            <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" data-turbo="false"
                class="bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-sm transition-colors w-fit pointer-events-auto text-sm">
                <i class="fa-solid fa-phone transform -scale-x-100"></i> Call me
            </a>
            <a href="<?php echo getWhatsappLink(); ?>" target="_blank" data-turbo="false"
                class="bg-green-500 hover:bg-green-600 text-white font-bold px-5 py-2.5 rounded-xl flex items-center gap-2 shadow-sm transition-colors w-fit pointer-events-auto text-sm">
                <i class="fa-brands fa-whatsapp text-lg"></i> Message me
            </a>
        </div>
    </div>

    <!-- Animated Trust Badges Section -->
    <div class="bg-white border-b border-slate-200 py-8 md:py-12 animate-fade-in-up">
        <div class="max-w-[1440px] mx-auto px-4 md:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                <div class="flex flex-col items-center p-6 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group cursor-pointer">
                    <div
                        class="w-16 h-16 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-105 transition-transform border border-blue-100 shadow-sm">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm md:text-base">100% Genuine</h4>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Direct from Top Brands</p>
                </div>
                <div class="flex flex-col items-center p-6 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group cursor-pointer">
                    <div
                        class="w-16 h-16 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-105 transition-transform border border-green-100 shadow-sm">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm md:text-base">Best Prices</h4>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Unbeatable Deals</p>
                </div>
                <div class="flex flex-col items-center p-6 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group cursor-pointer">
                    <div
                        class="w-16 h-16 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-105 transition-transform border border-orange-100 shadow-sm">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm md:text-base">Fast Delivery</h4>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Across Bihar</p>
                </div>
                <div class="flex flex-col items-center p-6 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-colors group cursor-pointer">
                    <div
                        class="w-16 h-16 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-105 transition-transform border border-purple-100 shadow-sm">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm md:text-base">24/7 Support</h4>
                    <p class="text-xs text-gray-500 mt-1 font-medium">We're Here to Help</p>
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
                    swiperEl.addEventListener('click', function(e) {
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



    <!-- Category Feature Blocks (New Tejoplast Style Layout) -->
    <?php foreach ($categoryStrips as $index => $strip):
        // Get the top product of this category to feature
        $topProduct = $strip['products'][0] ?? null;
        if (!$topProduct)
            continue;

        $isEven = $index % 2 === 0;
        ?>
        <div class="py-16 md:py-24 <?php echo $isEven ? 'bg-white' : 'bg-slate-50'; ?>">
            <div class="max-w-[1440px] mx-auto px-4 md:px-8">
                <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-center">

                    <!-- Image / Flip Slider Side -->
                    <div class="w-full lg:w-1/2 relative <?php echo $isEven ? 'lg:order-1' : 'lg:order-2'; ?>">
                        <?php $displayProducts = array_slice($strip['products'], 0, 5); // Up to 5 products ?>
                        
                        <div class="swiper categorySwiper w-full h-[300px] md:h-[450px] rounded-2xl border border-slate-200 shadow-sm cursor-pointer bg-white overflow-hidden relative group">
                            <div class="swiper-wrapper">
                                <?php foreach ($displayProducts as $prod): ?>
                                <div class="swiper-slide bg-white flex items-center justify-center p-8 md:p-12">
                                    <?php if ($prod['primary_image']): ?>
                                        <img src="/<?php echo htmlspecialchars($prod['primary_image']); ?>"
                                            class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500" alt="<?php echo htmlspecialchars($prod['name']); ?>" loading="lazy">
                                    <?php else: ?>
                                        <i class="fa-solid fa-image text-6xl text-slate-200"></i>
                                    <?php endif; ?>
                                    
                                    <!-- Product Name Overlay -->
                                    <div class="absolute bottom-6 left-0 right-0 flex justify-center z-10">
                                        <span class="bg-black/80 text-white text-xs md:text-sm font-bold px-4 py-2 rounded-lg backdrop-blur-sm shadow-sm max-w-[80%] truncate">
                                            <?php echo htmlspecialchars($prod['name']); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Floating Action Buttons on Image (Outside Swiper) -->
                        <div class="absolute top-6 left-6 z-30 flex flex-col gap-3 pointer-events-none">
                            <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" data-turbo="false"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-colors w-fit text-sm pointer-events-auto shadow-sm">
                                <i class="fa-solid fa-phone transform -scale-x-100"></i> Call
                            </a>
                            <a href="<?php echo getWhatsappLink('Hi, I am interested in ' . $strip['category']['name'] . ' products.'); ?>"
                                target="_blank" data-turbo="false"
                                class="bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition-colors w-fit text-sm pointer-events-auto shadow-sm">
                                <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Content Side -->
                    <div class="w-full lg:w-1/2 <?php echo $isEven ? 'lg:order-2' : 'lg:order-1'; ?>">
                        <h2 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-6 tracking-tight">
                            Best Deals in <span
                                class="text-primary"><?php echo htmlspecialchars($strip['category']['name']); ?></span>
                        </h2>

                        <p class="text-gray-600 leading-relaxed mb-8 text-base md:text-lg">
                            <?php echo htmlspecialchars($topProduct['short_description'] ?? 'At ' . getSetting('store_name') . ', we are one of the trusted suppliers of premium quality machinery. We provide complete after-sales support to our customers for training, maintenance and troubleshooting. Our team of experts is always ready to help you get your machines working easier and faster.'); ?>
                        </p>

                        <!-- Large Features -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                            <div class="flex gap-4 items-start p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-xl flex-shrink-0">
                                    <i class="fa-solid fa-gears"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Wide Range</h4>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">Reliable machinery and equipment for every industrial need.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 items-start p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                                <div
                                    class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-xl flex-shrink-0">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">24/7 Support</h4>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">Emergency or planned — we’re always ready to serve you.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Checkmarks List -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 mb-10 border-b border-gray-200 pb-10">
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div
                                    class="w-5 h-5 rounded-full bg-primary flex items-center justify-center text-white text-[10px]">
                                    <i class="fa-solid fa-check"></i></div>
                                Highly Maintained Machines
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div
                                    class="w-5 h-5 rounded-full bg-primary flex items-center justify-center text-white text-[10px]">
                                    <i class="fa-solid fa-check"></i></div>
                                Experienced Operators
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div
                                    class="w-5 h-5 rounded-full bg-primary flex items-center justify-center text-white text-[10px]">
                                    <i class="fa-solid fa-check"></i></div>
                                Affordable & Transparent Pricing
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-700 font-medium">
                                <div
                                    class="w-5 h-5 rounded-full bg-primary flex items-center justify-center text-white text-[10px]">
                                    <i class="fa-solid fa-check"></i></div>
                                Timely Project Delivery
                            </div>
                        </div>

                        <!-- Bottom Section: Founder or CTA -->
                        <?php if ($index === 0): ?>
                            <div class="flex items-center justify-between flex-wrap gap-4 mt-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl border-2 border-primary border-dashed p-1">
                                        <div
                                            class="w-full h-full bg-slate-100 rounded-lg overflow-hidden flex items-center justify-center">
                                            <i class="fa-solid fa-user-tie text-2xl text-slate-400"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-gray-900 text-lg tracking-tight">Shri Uddyami</h4>
                                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Founder,
                                            <?php echo htmlspecialchars(getSetting('store_name')); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center gap-4 mt-6">
                                <a href="/category/<?php echo urlencode($strip['category']['slug']); ?>" data-turbo="false"
                                    class="bg-white border border-slate-200 text-gray-900 hover:border-primary hover:text-primary font-bold px-8 py-3.5 rounded-xl flex items-center gap-2 shadow-sm transition-colors relative">
                                    Explore All <?php echo htmlspecialchars($strip['category']['name']); ?> <i
                                        class="fa-solid fa-arrow-right text-sm"></i>
                                </a>
                            </div>
                        <?php endif; ?>

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




<?php include __DIR__ . '/includes/footer.php'; ?>