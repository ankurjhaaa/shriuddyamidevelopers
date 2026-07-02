<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Categories';
include __DIR__ . '/includes/header.php';

// Fetch all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="px-4 py-6 bg-white min-h-screen">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">All Categories</h2>
    
    <?php if (empty($categories)): ?>
        <div class="text-center py-10">
            <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No categories found.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 gap-4">
            <?php foreach ($categories as $cat): ?>
                <a href="/search.php?category=<?php echo urlencode($cat['id']); ?>" class="bg-gray-50 border border-gray-100 rounded-xl p-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition group">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm overflow-hidden group-hover:scale-110 transition duration-300">
                        <?php if($cat['image']): ?>
                            <img src="/<?php echo htmlspecialchars($cat['image']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fa-solid fa-layer-group text-primary text-2xl"></i>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-semibold text-gray-800 leading-tight"><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <?php 
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ? AND status = 'active'");
                        $stmt->execute([$cat['id']]);
                        $count = $stmt->fetchColumn();
                    ?>
                    <p class="text-xs text-gray-500 mt-1"><?php echo $count; ?> Products</p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
