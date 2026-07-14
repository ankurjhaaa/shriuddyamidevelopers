<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Delete images
    $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
    $stmt->execute([$id]);
    $images = $stmt->fetchAll();
    foreach ($images as $img) {
        if (file_exists(__DIR__ . '/../' . $img['image_path'])) {
            unlink(__DIR__ . '/../' . $img['image_path']);
        }
    }
    // Delete from DB (CASCADE handles product_images)
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: /admin/products.php?msg=deleted");
    exit;
}

$products = $pdo->query("
    SELECT p.id, p.name, p.price, p.status, p.featured, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
")->fetchAll();

$pageTitle = 'Products';
include __DIR__ . '/includes/header.php';
?>

<div class="mb-6 animate-fade-in">
    <p class="text-gray-500 text-sm">Manage your product catalog.</p>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
        <span class="font-medium">Action completed successfully.</span>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead>
                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold">ID</th>
                    <th class="px-6 py-4 font-semibold">Name</th>
                    <th class="px-6 py-4 font-semibold">Category</th>
                    <th class="px-6 py-4 font-semibold">Price</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No products found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr class="hover:bg-gray-50/80 transition duration-150">
                            <td class="px-6 py-4 text-gray-500 font-medium">#<?php echo $p['id']; ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                <?php echo htmlspecialchars($p['name']); ?>
                                <?php if($p['featured']): ?>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wide">Featured</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700">
                                    <?php echo htmlspecialchars($p['category_name']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">₹ <?php echo number_format($p['price'], 2); ?></td>
                            <td class="px-6 py-4">
                                <?php if($p['status'] === 'active'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-200">Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/admin/product_edit.php?id=<?php echo $p['id']; ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 flex items-center justify-center transition shadow-sm border border-blue-100">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <a href="?delete=<?php echo $p['id']; ?>" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 flex items-center justify-center transition shadow-sm border border-red-100" onclick="return confirm('Delete this product?');">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Floating Action Button -->
<a href="/admin/product_edit.php" class="fixed bottom-24 md:bottom-10 right-6 bg-blue-600 text-white w-14 h-14 rounded-sm flex items-center justify-center hover:bg-blue-700 transition shadow-lg z-30 group hover:scale-105" title="Add Product">
    <i class="fa-solid fa-plus text-xl transition-transform"></i>
</a>

<?php include __DIR__ . '/includes/footer.php'; ?>
