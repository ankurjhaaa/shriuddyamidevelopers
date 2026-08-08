<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = getSetting('store_name') . ' - Company Profile';
$pageDescription = 'Learn more about ' . getSetting('store_name') . ', our products, and contact information.';
include __DIR__ . '/includes/header.php';

// Fetch some featured or all products for the company profile
$productsStmt = $pdo->query("SELECT p.*, c.name as category_name, 
    (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
    ORDER BY p.featured DESC, p.id DESC LIMIT 8");
$companyProducts = $productsStmt->fetchAll();
?>

<div class="bg-slate-50 min-h-screen pb-16 pt-4">
    <div class="max-w-[1440px] mx-auto px-4 md:px-8">
        
        <!-- Company Banner -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <!-- Cover/Top color bar -->
            <div class="h-32 md:h-48 bg-primary relative overflow-hidden flex items-start md:items-end justify-end p-4 md:p-6">
                <!-- Cover background pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(white 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
            </div>
            
            <div class="px-6 md:px-10 pb-8 relative">
                <div class="flex flex-col md:flex-row gap-6 md:items-end -mt-16 md:-mt-20 mb-6 relative z-10">
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-2xl border-4 border-white shadow-md flex items-center justify-center overflow-hidden flex-shrink-0 z-10">
                        <img src="/assets/images/logo.png" alt="Company Logo" class="max-w-full max-h-full object-contain p-2">
                    </div>
                    
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-1.5">
                            <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">Shri Uddyami Developers</h1>
                            <i class="fa-solid fa-circle-check text-blue-500 text-xl" title="Verified Seller"></i>
                        </div>
                        <p class="text-gray-600 text-sm flex items-start gap-2 mb-4 font-medium max-w-2xl bg-gray-50 border border-gray-100 p-2.5 rounded-lg">
                            <i class="fa-solid fa-location-dot text-primary mt-0.5"></i> 
                            <span><?php echo htmlspecialchars(getSetting('address')); ?></span>
                        </p>
                        <div class="flex flex-wrap gap-2 md:gap-3 mt-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 text-green-700 text-xs font-bold border border-green-200">
                                <i class="fa-solid fa-shield-check"></i> TrustSEAL Verified
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-primary text-xs font-bold border border-blue-200">
                                <i class="fa-solid fa-industry"></i> Manufacturer & Wholesaler
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex flex-col gap-3 w-full md:w-auto mt-4 md:mt-0">
                        <a href="<?php echo getWhatsappLink('I have a requirement'); ?>" target="_blank" class="bg-primary hover:bg-[#0a2c5a] text-white px-6 py-3.5 rounded-xl text-sm font-bold transition-all text-center flex items-center justify-center gap-2 shadow-[0_4px_12px_rgba(12,53,106,0.3)] hover:-translate-y-0.5">
                            <i class="fa-solid fa-paper-plane"></i> Contact Supplier
                        </a>
                        <button class="bg-white border border-gray-200 text-gray-800 px-6 py-3.5 rounded-xl text-sm font-bold transition-all text-center flex items-center justify-center gap-2 hover:border-primary hover:text-primary hover:bg-blue-50">
                            <i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars(getSetting('whatsapp')); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            
            <!-- Left Content Area -->
            <div class="w-full lg:col-span-2 flex flex-col gap-6 md:gap-8">
                <!-- About Box -->
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-white px-6 md:px-8 py-5 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <h2 class="font-black text-slate-800 text-xl tracking-tight">About Company</h2>
                    </div>
                    <div class="p-6 md:p-8 text-[15px] md:text-base text-slate-600 font-medium leading-loose space-y-5">
                        <p class="first-letter:text-5xl first-letter:font-black first-letter:text-primary first-letter:mr-1 first-letter:float-left">Welcome to <strong class="text-slate-900">Shri Uddyami Developers</strong>, a premium enterprise. We are a leading manufacturer, wholesaler, and trader of high-quality agricultural and industrial machinery based in Purnea, Bihar.</p>
                        
                        <p>With years of experience in the industry, we specialize in providing robust, durable, and highly efficient machines tailored to meet the dynamic needs of modern agriculture and industrial processing. Our extensive product range includes Commercial Atta Chakki, Domestic Flour Mills, Rice Mill Machines, Destoner Machines, and much more.</p>

                        <div class="bg-blue-50/50 border-l-4 border-blue-500 p-5 rounded-r-xl italic text-slate-700">
                            "Our commitment is to deliver technological excellence and unparalleled customer service. Every machine we offer undergoes stringent quality checks to ensure optimal performance, low maintenance, and long service life."
                        </div>
                    </div>
                </div>

                <!-- Company Highlights -->
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] rounded-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-white px-6 md:px-8 py-5 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <h2 class="font-black text-slate-800 text-xl tracking-tight">Factsheet</h2>
                    </div>
                    <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <!-- Fact Item -->
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-slate-200 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-sm flex-shrink-0">
                                <i class="fa-solid fa-industry"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nature of Business</p>
                                <p class="font-bold text-slate-800">Manufacturer & Wholesaler</p>
                            </div>
                        </div>
                        <!-- Fact Item -->
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-slate-200 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-sm flex-shrink-0">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Company CEO</p>
                                <p class="font-bold text-slate-800">Authorized Representative</p>
                            </div>
                        </div>
                        <!-- Fact Item -->
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-slate-200 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-sm flex-shrink-0">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Registered Address</p>
                                <p class="font-bold text-slate-800 text-sm"><?php echo htmlspecialchars(getSetting('address')); ?></p>
                            </div>
                        </div>
                        <!-- Fact Item -->
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-slate-200 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-sm flex-shrink-0">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">GST No.</p>
                                <p class="font-bold text-slate-800 flex items-center gap-2">
                                    <?php echo htmlspecialchars(getSetting('gst')); ?>
                                    <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full"><i class="fa-solid fa-check"></i> Verified</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6 md:space-y-8">
                <!-- Our Products Grid (Mini) -->
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] rounded-2xl overflow-hidden">
                    <div class="flex justify-between items-center bg-gradient-to-r from-slate-50 to-white px-6 py-5 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            <h2 class="font-black text-slate-800 text-lg">Top Products</h2>
                        </div>
                        <a href="/search.php" class="text-[11px] uppercase tracking-wider text-primary font-bold hover:underline bg-primary/5 px-2.5 py-1 rounded-md">View All</a>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-col gap-3">
                            <?php foreach(array_slice($companyProducts, 0, 4) as $cp): ?>
                                <a href="/product.php?slug=<?php echo urlencode($cp['slug']); ?>" class="flex gap-4 items-center group border border-slate-100 hover:border-primary/30 hover:bg-slate-50/50 p-3 rounded-xl transition-all">
                                    <div class="w-16 h-16 bg-white flex-shrink-0 flex items-center justify-center rounded-lg border border-slate-100 shadow-sm p-1.5 overflow-hidden">
                                        <?php if ($cp['primary_image']): ?>
                                            <img src="/<?php echo htmlspecialchars($cp['primary_image']); ?>" alt="Img" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                                        <?php else: ?>
                                            <i class="fa-solid fa-image text-slate-200 text-xl"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="text-sm font-bold text-slate-700 group-hover:text-primary transition-colors line-clamp-2 leading-snug mb-1.5"><?php echo htmlspecialchars($cp['name']); ?></h4>
                                        <span class="text-sm font-black text-slate-900"><?php echo $cp['price'] > 0 ? formatPrice($cp['price']) : 'Ask Price'; ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Box (Highlighted) -->
                <div class="bg-gradient-to-br from-primary to-[#c24100] rounded-2xl shadow-lg border border-primary/20 overflow-hidden relative text-white">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                    
                    <div class="px-6 py-5 border-b border-white/10 flex items-center gap-3 relative z-10">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <i class="fa-solid fa-headset text-white"></i>
                        </div>
                        <h2 class="font-black text-xl tracking-tight">Contact Us</h2>
                    </div>
                    
                    <div class="p-6 md:p-8 relative z-10">
                        <p class="font-black mb-6 text-xl tracking-wide opacity-90">Shri Uddyami Developers</p>
                        
                        <div class="flex items-start gap-4 text-sm mb-5 font-medium">
                            <div class="w-10 h-10 rounded-xl bg-black/20 flex items-center justify-center flex-shrink-0 shadow-inner">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <span class="pt-1.5 text-white/90 leading-relaxed"><?php echo htmlspecialchars(getSetting('address')); ?></span>
                        </div>
                        
                        <div class="flex items-center gap-4 text-sm mb-8 font-medium">
                            <div class="w-10 h-10 rounded-xl bg-black/20 flex items-center justify-center flex-shrink-0 shadow-inner">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <span class="font-black text-xl tracking-wide"><?php echo htmlspecialchars(getSetting('whatsapp')); ?></span>
                        </div>
                        
                        <a href="<?php echo getWhatsappLink('Hi, I want to know more about your company.'); ?>" target="_blank" class="w-full bg-white text-primary hover:bg-slate-50 py-4 rounded-xl text-base font-black transition-all flex justify-center items-center gap-2 shadow-[0_4px_15px_rgba(0,0,0,0.1)] hover:shadow-[0_6px_20px_rgba(0,0,0,0.15)] hover:-translate-y-0.5">
                            <i class="fa-brands fa-whatsapp text-green-500 text-xl"></i> Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
