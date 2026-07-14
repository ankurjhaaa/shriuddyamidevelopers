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

ob_start();
if (!empty($products)) {
    foreach ($products as $product) {
        ?>
        <div class="bg-white border border-gray-200 hover:border-primary transition-all h-full group flex flex-col rounded-sm overflow-hidden wishlist-card"
            data-product-id="<?php echo $product['id']; ?>">

            <!-- Image -->
            <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block relative w-full h-[180px] bg-white border-b border-gray-100 p-2 flex items-center justify-center">
                <?php if ($product['primary_image']): ?>
                    <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-cover mix-blend-multiply" loading="lazy">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                <?php endif; ?>
            </a>

            <!-- Content -->
            <div class="flex-grow flex flex-col p-3">
                <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block mb-2">
                    <h4 class="text-sm font-medium text-blue-700 hover:underline leading-snug line-clamp-2">
                        <?php echo htmlspecialchars($product['name']); ?></h4>
                </a>

                <div class="price-container mb-3" data-product-id="<?php echo $product['id']; ?>"
                    data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                    <?php if ($product['price_visibility'] === 'public'): ?>
                        <span class="font-bold text-lg text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                    <?php elseif ($product['price_visibility'] === 'locked'): ?>
                        <button class="btn-unlock-price text-accent font-semibold text-xs hover:underline flex items-center gap-1">
                            Unlock Price <i class="fa-solid fa-lock text-[10px]"></i>
                        </button>
                    <?php else: ?>
                        <button
                            class="btn-unlock-price text-gray-500 text-xs font-semibold hover:underline flex items-center gap-1">
                            Get Latest Price
                        </button>
                    <?php endif; ?>
                </div>

                <div class="mt-auto">
                    <p class="text-[11px] text-gray-500 mb-3 truncate flex items-center gap-1"><i
                            class="fa-solid fa-location-dot text-gray-400"></i> Purnea, Bihar</p>

                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank"
                        class="w-full text-center bg-primary text-white hover:bg-secondary transition px-3 py-2 rounded-sm text-sm font-semibold flex justify-center items-center gap-2">
                        Contact Supplier
                    </a>
                </div>
            </div>

            <button
                class="absolute top-2 right-2 w-6 h-6 bg-white/90 rounded-sm flex items-center justify-center text-red-500 hover:text-gray-400 z-10 wishlist-btn shadow-sm"
                data-id="<?php echo $product['id']; ?>">
                <i class="fa-solid fa-heart text-xs"></i>
            </button>
        </div>
        <?php
    }
}
$html = ob_get_clean();

echo json_encode(['html' => $html]);
exit;
