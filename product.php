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

$pageTitle = $product['name'];
include __DIR__ . '/includes/header.php';
?>

<div class="bg-gray-50 min-h-screen pb-24 md:pb-12 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2 animate-fade-in">
            <a href="/" class="hover:text-primary transition">Home</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <a href="/categories.php" class="hover:text-primary transition">Categories</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-gray-900 font-medium truncate max-w-xs"><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>

        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row animate-slide-up">
            
            <!-- Left: Image Gallery -->
            <div class="w-full md:w-1/2 p-6 md:p-10 border-b md:border-b-0 md:border-r border-gray-100 flex items-center justify-center bg-gray-50/50 relative">
                <?php if (!empty($images)): ?>
                    <div class="swiper product-gallery w-full aspect-square md:aspect-auto md:h-[500px]">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $img): ?>
                                <div class="swiper-slide flex items-center justify-center p-4">
                                    <img src="/<?php echo htmlspecialchars($img); ?>" class="max-h-full max-w-full object-contain drop-shadow-md hover:scale-105 transition-transform duration-500">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                <?php else: ?>
                    <div class="w-full aspect-square md:h-[500px] flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-7xl"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Product Details -->
            <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col">
                <p class="text-sm text-secondary font-bold uppercase tracking-widest mb-2"><?php echo htmlspecialchars($product['category_name']); ?></p>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <!-- Price Block -->
                <div class="bg-blue-50/50 p-5 rounded-xl border border-blue-100 flex items-center justify-between mb-8 shadow-sm">
                    <div>
                        <p class="text-sm text-gray-500 font-medium mb-1">Price</p>
                        <div class="price-container text-2xl" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                            <?php if ($product['price_visibility'] === 'public'): ?>
                                <span class="font-bold text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                            <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                <button class="btn-unlock-price flex items-center gap-2 text-primary font-bold bg-white px-4 py-2 rounded-lg shadow-sm border border-blue-100 transition hover:bg-blue-50">
                                    <span>Unlock Price</span>
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </button>
                                <span class="real-price hidden font-bold text-gray-900"></span>
                            <?php else: ?>
                                <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="text-primary font-bold text-xl hover:underline">Ask on WhatsApp</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <button class="text-gray-400 hover:text-red-500 transition w-12 h-12 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <i class="fa-regular fa-heart text-xl"></i>
                    </button>
                </div>
                
                <!-- Desktop CTA Buttons -->
                <div class="hidden md:flex gap-4 mb-8">
                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="flex-1 bg-green-500 text-white font-bold py-4 rounded-xl flex items-center justify-center gap-2 shadow-md transition hover:bg-green-600 hover:-translate-y-0.5">
                        <i class="fa-brands fa-whatsapp text-xl"></i> WhatsApp Info
                    </a>
                    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex-1 bg-white border-2 border-gray-200 text-gray-700 font-bold py-4 rounded-xl flex items-center justify-center gap-2 transition hover:bg-gray-50 hover:border-gray-300 hover:-translate-y-0.5">
                        <i class="fa-solid fa-phone"></i> Call Now
                    </a>
                </div>

                <!-- Description & Details -->
                <div class="space-y-8 flex-grow">
                    <?php if ($product['short_description'] || $product['description']): ?>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="fa-solid fa-file-lines text-primary"></i> Description
                            </h3>
                            <div class="text-gray-600 leading-relaxed text-sm md:text-base">
                                <?php 
                                    if($product['short_description']) echo '<p class="mb-3 font-medium text-gray-700">' . nl2br(htmlspecialchars($product['short_description'])) . '</p>';
                                    if($product['description']) echo nl2br(htmlspecialchars($product['description'])); 
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['specifications']): ?>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="fa-solid fa-list-check text-primary"></i> Specifications
                            </h3>
                            <div class="text-sm md:text-base text-gray-600 leading-relaxed bg-gray-50 p-5 rounded-xl border border-gray-100">
                                <?php echo nl2br(htmlspecialchars($product['specifications'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($product['applications']): ?>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="fa-solid fa-gears text-primary"></i> Applications
                            </h3>
                            <div class="text-sm md:text-base text-gray-600 leading-relaxed bg-gray-50 p-5 rounded-xl border border-gray-100">
                                <?php echo nl2br(htmlspecialchars($product['applications'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Sticky Call to Action for Product Page (Mobile Only) -->
<div class="md:hidden fixed bottom-0 w-full bg-white border-t border-gray-200 z-50 p-4 flex gap-3 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.1)]">
    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex-1 bg-gray-100 text-gray-800 font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 transition active:bg-gray-200">
        <i class="fa-solid fa-phone"></i> Call
    </a>
    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="flex-[2] bg-green-500 text-white font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 shadow-md transition active:bg-green-600">
        <i class="fa-brands fa-whatsapp text-xl"></i> WhatsApp
    </a>
</div>

<!-- Overwrite bottom nav spacing so it doesn't overlap the CTA on this page -->
<style>
    body { padding-bottom: 0 !important; }
    nav.fixed.bottom-0 { display: none !important; }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
