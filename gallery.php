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
        <!-- Fresh Flat Hero Section (No Gradients) -->
    <div class="relative w-full pt-16 md:pt-24 pb-24 md:pb-32 bg-slate-900 overflow-hidden z-10">
        <!-- Abstract geometric decoration (Solid Colors, No Gradients) -->
        <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-white opacity-5 rounded-full transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-10 left-10 w-32 h-32 bg-primary opacity-10 rounded-lg rotate-12 pointer-events-none"></div>
        
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-12">
            <!-- Text Content -->
            <div class="w-full md:w-1/2 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-none bg-primary text-white text-xs font-bold tracking-widest uppercase mb-6 shadow-md">
                    <i class="fa-solid fa-camera-retro"></i> Media Showcase
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">Machine <br class="hidden md:block" /><span class="text-primary">Gallery</span></h1>
                <p class="text-gray-300 text-lg md:text-xl max-w-lg mx-auto md:mx-0 leading-relaxed font-medium">Explore our extensive collection of high-quality agricultural and industrial machinery in action.</p>
            </div>
            
            <!-- Image / Visual Side -->
            <div class="w-full md:w-1/2 relative hidden md:block">
                <!-- Solid blocks behind image -->
                <div class="absolute top-4 right-4 w-full h-full bg-primary rounded-2xl -z-10"></div>
                <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/10 rounded-none rotate-45 -z-10"></div>
                
                <div class="rounded-2xl overflow-hidden border-4 border-slate-900 shadow-2xl relative bg-slate-800 flex items-center justify-center">
                    <img src="/assets/images/desktop_banner.png" class="w-full h-72 object-fill opacity-95 hover:opacity-100 transition-opacity duration-300" alt="Showcase">
                </div>
            </div>
        </div>
        
        <!-- Flat Diagonal Cut Bottom -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] z-20 pointer-events-none">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-[40px] md:h-[80px] text-slate-50" fill="currentColor">
                <polygon points="0,120 1200,120 1200,0"></polygon>
            </svg>
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
