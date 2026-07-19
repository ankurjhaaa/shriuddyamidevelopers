<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Gallery - Our Work & Machinery';
include __DIR__ . '/includes/header.php';

// Fetch all product images for the gallery
$galleryImages = [];
try {
    $stmt = $pdo->query("
        SELECT pi.image_path, p.name as product_name, p.slug 
        FROM product_images pi
        JOIN products p ON pi.product_id = p.id
        WHERE p.status = 'active'
        ORDER BY pi.id DESC
    ");
    $galleryImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>

<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Section -->
    <div class="bg-slate-900 py-16 relative overflow-hidden z-10 mb-8">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-20 max-w-[1440px] mx-auto px-4 md:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight">Our <span class="text-primary">Gallery</span></h1>
            <p class="text-gray-300 max-w-2xl mx-auto text-sm md:text-base font-medium">Explore our extensive collection of high-quality agricultural and industrial machinery.</p>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 md:px-8">
        <?php if (empty($galleryImages)): ?>
            <div class="flex flex-col items-center justify-center py-24 bg-white rounded-xl border border-slate-200 shadow-sm p-8 text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center text-4xl mb-6 border border-slate-200">
                    <i class="fa-solid fa-image"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">No Images Yet</h2>
                <p class="text-gray-500 max-w-[300px] font-medium">Check back later for photos of our machinery.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                <?php foreach ($galleryImages as $image): ?>
                    <a href="/products/<?php echo urlencode($image['slug']); ?>" class="group block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:border-primary transition-all relative">
                        <div class="w-full h-48 md:h-64 bg-slate-50 flex items-center justify-center p-4">
                            <img src="/<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($image['product_name']); ?>" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-black/80 backdrop-blur-sm p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <p class="text-white text-xs font-bold text-center truncate"><?php echo htmlspecialchars($image['product_name']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
