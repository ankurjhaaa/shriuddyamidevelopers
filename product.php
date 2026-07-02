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

<div class="bg-white min-h-screen pb-24">
    <!-- Image Gallery (Swiper) -->
    <div class="bg-gray-50 border-b border-gray-100">
        <?php if (!empty($images)): ?>
            <div class="swiper product-gallery h-72">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $img): ?>
                        <div class="swiper-slide flex items-center justify-center p-4">
                            <img src="/<?php echo htmlspecialchars($img); ?>" class="max-h-full max-w-full object-contain rounded-lg">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <?php else: ?>
            <div class="h-72 flex items-center justify-center text-gray-400">
                <i class="fa-solid fa-image text-5xl"></i>
            </div>
        <?php endif; ?>
    </div>

    <div class="px-5 py-6">
        <p class="text-xs text-primary font-bold uppercase tracking-wider mb-2"><?php echo htmlspecialchars($product['category_name']); ?></p>
        <h1 class="text-2xl font-bold text-gray-900 leading-tight mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
        
        <!-- Price Block -->
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-center justify-between mb-6 shadow-sm">
            <div>
                <p class="text-xs text-gray-500 font-medium mb-1">Price</p>
                <div class="price-container text-xl" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                    <?php if ($product['price_visibility'] === 'public'): ?>
                        <span class="font-bold text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                    <?php elseif ($product['price_visibility'] === 'locked'): ?>
                        <button class="btn-unlock-price flex items-center gap-2 text-primary font-bold bg-blue-100 px-3 py-1.5 rounded-lg shadow-sm transition hover:bg-blue-200">
                            <span>Unlock Price</span>
                            <i class="fa-solid fa-lock text-sm"></i>
                        </button>
                    <?php else: ?>
                        <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="text-primary font-bold text-lg">Ask on WhatsApp</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <button class="text-gray-400 hover:text-red-500 transition w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                <i class="fa-regular fa-heart text-xl"></i>
            </button>
        </div>

        <?php if ($product['short_description']): ?>
            <p class="text-gray-600 text-sm leading-relaxed mb-6"><?php echo nl2br(htmlspecialchars($product['short_description'])); ?></p>
        <?php endif; ?>

        <!-- Tabs content (Simulated single scroll view for simplicity) -->
        <div class="space-y-6">
            <?php if ($product['description']): ?>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-file-lines text-primary"></i> Description
                    </h3>
                    <div class="text-sm text-gray-600 leading-relaxed border-l-2 border-blue-100 pl-3">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($product['specifications']): ?>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-primary"></i> Specifications
                    </h3>
                    <div class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-lg">
                        <?php echo nl2br(htmlspecialchars($product['specifications'])); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($product['applications']): ?>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-gears text-primary"></i> Applications
                    </h3>
                    <div class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-lg">
                        <?php echo nl2br(htmlspecialchars($product['applications'])); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Sticky Call to Action for Product Page -->
<div class="fixed bottom-0 w-full max-w-md mx-auto bg-white border-t border-gray-200 z-50 p-3 flex gap-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
    <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 rounded-lg flex items-center justify-center gap-2 transition hover:bg-gray-200">
        <i class="fa-solid fa-phone"></i> Call
    </a>
    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="flex-[2] bg-green-500 text-white font-semibold py-3 rounded-lg flex items-center justify-center gap-2 shadow-sm transition hover:bg-green-600">
        <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp Info
    </a>
</div>

<!-- Overwrite bottom nav spacing so it doesn't overlap the CTA on this page -->
<style>
    body { padding-bottom: 0; }
    nav.fixed.bottom-0 { display: none; }
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
