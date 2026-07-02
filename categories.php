<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Categories';
include __DIR__ . '/includes/header.php';

// Fetch all categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="px-4 sm:px-6 lg:px-8 py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 animate-fade-in">All Categories</h2>
        
        <?php if (empty($categories)): ?>
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100 animate-slide-up">
                <i class="fa-solid fa-folder-open text-6xl text-gray-200 mb-4"></i>
                <p class="text-gray-500 font-medium">No categories found.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 animate-slide-up">
                <?php foreach ($categories as $cat): ?>
                    <a href="/search.php?category=<?php echo urlencode($cat['id']); ?>" class="bg-white border border-gray-100 rounded-2xl p-6 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-lg transition-all duration-300 group hover:-translate-y-1">
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4 shadow-sm overflow-hidden group-hover:scale-110 transition-transform duration-300 border border-gray-100">
                            <?php if($cat['image']): ?>
                                <img src="/<?php echo htmlspecialchars($cat['image']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <i class="fa-solid fa-layer-group text-primary text-3xl"></i>
                            <?php endif; ?>
                        </div>
                        <h3 class="font-bold text-gray-900 leading-tight group-hover:text-primary transition"><?php echo htmlspecialchars($cat['name']); ?></h3>
                        <?php 
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ? AND status = 'active'");
                            $stmt->execute([$cat['id']]);
                            $count = $stmt->fetchColumn();
                        ?>
                        <p class="text-xs text-gray-500 mt-2 font-medium bg-gray-50 px-3 py-1 rounded-full"><?php echo $count; ?> Products</p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
