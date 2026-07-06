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
        <div class="bg-white border border-gray-200 hover:border-gray-300 rounded-sm flex flex-col relative hover:shadow-md transition-all h-full group p-2 pb-3 wishlist-card" data-product-id="<?php echo $product['id']; ?>">
            <button class="absolute top-2 right-2 w-6 h-6 bg-white/90 rounded-full flex items-center justify-center text-red-500 hover:text-gray-400 z-10 wishlist-btn shadow-sm" data-id="<?php echo $product['id']; ?>">
                <i class="fa-solid fa-heart text-xs"></i>
            </button>
            
            <!-- Image -->
            <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block relative w-full aspect-square bg-white mb-2">
                <?php if($product['primary_image']): ?>
                    <img src="/<?php echo htmlspecialchars($product['primary_image']); ?>" class="w-full h-full object-contain" loading="lazy">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-200 bg-gray-50 border border-gray-100">
                        <i class="fa-solid fa-image text-2xl"></i>
                    </div>
                <?php endif; ?>
            </a>
            
            <!-- Content -->
            <div class="flex-grow flex flex-col justify-between">
                <div>
                    <a href="/products/<?php echo urlencode($product['slug']); ?>" class="block">
                        <h4 class="text-xs font-medium text-blue-600 hover:underline leading-snug mb-1 line-clamp-2"><?php echo htmlspecialchars($product['name']); ?></h4>
                    </a>
                    <p class="text-[10px] text-gray-500 mb-1 truncate"><?php echo htmlspecialchars($product['category_name']); ?></p>
                </div>
                
                <div class="mt-1 flex flex-col gap-1.5">
                    <div class="price-container" data-product-id="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>" data-visibility="<?php echo $product['price_visibility']; ?>">
                        <?php if ($product['price_visibility'] === 'public'): ?>
                            <span class="font-bold text-sm text-gray-900"><?php echo formatPrice($product['price']); ?></span>
                        <?php elseif ($product['price_visibility'] === 'locked'): ?>
                            <button class="btn-unlock-price text-accent font-semibold text-[11px] hover:underline flex items-center gap-1">
                                Unlock Price <i class="fa-solid fa-lock text-[9px]"></i>
                            </button>
                        <?php else: ?>
                            <span class="text-gray-500 text-[11px]">Price on Request</span>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?php echo getWhatsappLink($product['name']); ?>" target="_blank" class="w-full text-center bg-primary/10 text-primary border border-primary/20 hover:bg-primary hover:text-white transition px-2 py-1.5 rounded-sm text-[11px] font-medium mt-1">
                        Contact Supplier
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
