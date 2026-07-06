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
        <div class="bg-white border border-gray-200 rounded-lg flex flex-row sm:flex-col relative shadow-sm h-full wishlist-card" data-product-id="<?php echo $product['id']; ?>">
            <button class="absolute top-2 right-2 w-7 h-7 bg-gray-50 sm:bg-white/80 sm:backdrop-blur rounded-full flex items-center justify-center text-red-500 z-10 wishlist-btn shadow-sm text-xs" data-id="<?php echo $product['id']; ?>">
                <i class="fa-solid fa-heart"></i>
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
        <?php
    }
}
$html = ob_get_clean();

echo json_encode(['html' => $html]);
exit;
