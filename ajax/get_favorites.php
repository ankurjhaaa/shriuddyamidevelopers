<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$ids = $input['ids'] ?? [];

if (empty($ids)) {
    echo json_encode(['html' => '']);
    exit;
}

// Prepare placeholders for IN clause
$placeholders = str_repeat('?,', count($ids) - 1) . '?';

$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name, 
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active' AND p.id IN ($placeholders)
");
$stmt->execute($ids);
$products = $stmt->fetchAll();

$categories = [];
if (!empty($products)) {
    foreach ($products as $product) {
        $catName = $product['category_name'] ?: 'Uncategorized';
        $catSlug = $product['category_slug'] ?? '';
        if (!isset($categories[$catName])) {
            $categories[$catName] = ['slug' => $catSlug, 'products' => []];
        }
        $categories[$catName]['products'][] = $product;
    }
}

ob_start();
if (!empty($categories)) {
    foreach ($categories as $catName => $catData) {
        ?>
        <div class="bg-slate-50 w-full mb-8">
            <div class="flex justify-between items-center mb-6 border-b border-slate-200 pb-2">
                <h3 class="text-xl md:text-2xl font-black text-gray-900 truncate pr-4"><?php echo htmlspecialchars($catName); ?></h3>
                <?php if ($catData['slug']): ?>
                    <a href="/category/<?php echo urlencode($catData['slug']); ?>" class="text-primary text-sm font-bold hover:underline flex-shrink-0 flex items-center gap-1">View All <i class="fa-solid fa-arrow-right text-xs"></i></a>
                <?php endif; ?>
            </div>
            <div class="flex overflow-x-auto gap-4 md:gap-6 hide-scrollbar pb-4 snap-x relative">
                <?php foreach ($catData['products'] as $product): ?>
                    <div class="w-[240px] md:w-[260px] flex-shrink-0 snap-start bg-white border border-slate-200 hover:border-primary transition-all h-full group flex flex-col rounded-xl overflow-hidden wishlist-card relative"
                        data-product-id="<?php echo $product['id']; ?>">

                        <!-- Image -->
                        <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block relative w-full h-[200px] md:h-[220px] bg-slate-50 border-b border-slate-100 p-4 flex items-center justify-center">
                            <?php if ($product['primary_image']): ?>
                                <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-200">
                                    <i class="fa-solid fa-image text-4xl group-hover:scale-105 transition-transform duration-300"></i>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Content -->
                        <div class="flex-grow flex flex-col p-4 md:p-5">
                            <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block mb-3 w-full">
                                <h4 class="text-sm md:text-base font-bold text-gray-900 group-hover:text-primary transition-colors leading-snug truncate" title="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php echo htmlspecialchars($product['name']); ?></h4>
                            </a>

                            <div class="price-container mb-4" data-product-id="<?php echo $product['id']; ?>"
                                data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                                <?php if ($product['price_visibility'] === 'public'): ?>
                                    <span class="font-bold text-lg text-gray-900 tracking-tight"><?php echo formatPrice($product['price']); ?></span>
                                <?php elseif ($product['price_visibility'] === 'locked'): ?>
                                    <button class="btn-unlock-price text-primary font-bold text-xs hover:underline flex items-center gap-1">
                                        Unlock Price <i class="fa-solid fa-lock text-[10px]"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn-unlock-price text-gray-500 text-[11px] md:text-xs font-bold hover:underline flex items-center gap-1">
                                        Get Latest Price
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="mt-auto space-y-3">
                                <p class="text-[11px] md:text-xs text-gray-500 truncate flex items-center gap-1.5 font-medium">
                                    <i class="fa-solid fa-location-dot text-primary"></i> Purnea, Bihar</p>

                                <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" data-turbo="false"
                                    class="w-full flex items-center justify-center gap-2 bg-green-50 text-green-600 border border-green-200 font-bold text-xs md:text-sm py-2.5 rounded-lg hover:bg-green-600 hover:text-white hover:border-green-600 transition-colors">
                                    <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                        </div>

                        <button
                            class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-red-500 hover:text-gray-400 z-30 wishlist-btn shadow-sm transition-colors border border-slate-100"
                            data-id="<?php echo $product['id']; ?>">
                            <i class="fa-solid fa-heart text-sm"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
$html = ob_get_clean();

echo json_encode(['html' => $html]);
exit;
