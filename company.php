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
            <div class="h-32 md:h-48 bg-slate-900 relative">
                <!-- Cover background pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
            </div>
            
            <div class="px-6 md:px-10 pb-8 relative">
                <div class="flex flex-col md:flex-row gap-6 md:items-end -mt-16 md:-mt-20 mb-6 relative z-10">
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-xl border-4 border-white shadow-sm flex items-center justify-center overflow-hidden flex-shrink-0 z-10">
                        <img src="/assets/images/logo.png" alt="Company Logo" class="max-w-full max-h-full object-contain p-2">
                    </div>
                    
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">Shri Uddyami Developers</h1>
                            <i class="fa-solid fa-circle-check text-blue-500 text-xl" title="Verified Seller"></i>
                        </div>
                        <p class="text-gray-500 text-sm flex items-center gap-2 mb-3 font-medium">
                            <i class="fa-solid fa-location-dot text-primary"></i> <?php echo htmlspecialchars(getSetting('address')); ?>
                        </p>
                        <div class="flex flex-wrap gap-2 md:gap-4 mt-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 text-green-700 text-xs font-bold border border-green-200">
                                <i class="fa-solid fa-shield-check"></i> TrustSEAL Verified
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                                <i class="fa-solid fa-industry"></i> Manufacturer & Wholesaler
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex flex-col gap-3 w-full md:w-auto mt-4 md:mt-0">
                        <a href="<?php echo getWhatsappLink('I have a requirement'); ?>" target="_blank" class="bg-primary hover:bg-secondary text-white px-6 py-3 rounded-xl text-sm font-bold transition text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Contact Supplier
                        </a>
                        <button class="bg-white border border-slate-200 hover:border-primary hover:text-primary text-gray-800 px-6 py-3 rounded-xl text-sm font-bold transition text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars(getSetting('whatsapp')); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            
            <!-- Left Sidebar: About & Contact -->
            <div class="w-full lg:col-span-2 flex flex-col gap-6 md:gap-8">
                <!-- About Box -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-xl">
                    <h2 class="bg-slate-50 px-6 py-4 border-b border-slate-100 font-bold text-gray-900 text-lg rounded-t-xl">About Company</h2>
                    <div class="p-6 md:p-8 text-sm md:text-base text-gray-600 font-medium leading-relaxed space-y-4">
                        <p>Welcome to <strong class="text-gray-900">Shri Uddyami Developers</strong>, a premium enterprise. We are a leading manufacturer, wholesaler, and trader of high-quality agricultural and industrial machinery based in Purnea, Bihar.</p>
                        
                        <p>With years of experience in the industry, we specialize in providing robust, durable, and highly efficient machines tailored to meet the dynamic needs of modern agriculture and industrial processing. Our extensive product range includes Commercial Atta Chakki, Domestic Flour Mills, Rice Mill Machines, Destoner Machines, and much more.</p>

                        <p>Our commitment is to deliver technological excellence and unparalleled customer service. Every machine we offer undergoes stringent quality checks to ensure optimal performance, low maintenance, and long service life. We aim to empower farmers and small businesses with the best tools to enhance their productivity and profitability.</p>
                    </div>
                </div>

                <!-- Company Highlights -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-xl overflow-hidden">
                    <h2 class="bg-slate-50 px-6 py-4 border-b border-slate-100 font-bold text-gray-900 text-lg">Factsheet</h2>
                    <div class="p-0 md:p-4">
                        <table class="w-full text-sm md:text-base text-left border-collapse">
                            <tbody>
                                <tr class="border-b border-slate-100">
                                    <td class="py-4 px-6 text-gray-500 w-1/3 bg-slate-50 border-r border-slate-100 font-medium">Nature of Business</td>
                                    <td class="py-4 px-6 text-gray-900 font-bold">Manufacturer & Wholesaler</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-4 px-6 text-gray-500 w-1/3 bg-slate-50 border-r border-slate-100 font-medium">Company CEO</td>
                                    <td class="py-4 px-6 text-gray-900 font-bold">Authorized Representative</td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-4 px-6 text-gray-500 w-1/3 bg-slate-50 border-r border-slate-100 font-medium">Registered Address</td>
                                    <td class="py-4 px-6 text-gray-900 font-bold"><?php echo htmlspecialchars(getSetting('address')); ?></td>
                                </tr>
                                <tr class="border-b border-slate-100">
                                    <td class="py-4 px-6 text-gray-500 w-1/3 bg-slate-50 border-r border-slate-100 font-medium">Industry</td>
                                    <td class="py-4 px-6 text-gray-900 font-bold">Agriculture & Industrial Machinery</td>
                                </tr>
                                <tr>
                                    <td class="py-4 px-6 text-gray-500 w-1/3 bg-slate-50 border-r border-slate-100 font-medium">GST No.</td>
                                    <td class="py-4 px-6 text-gray-900 font-bold">10AXXXX1234X1Z5 <span class="text-green-600 text-xs ml-1"><i class="fa-solid fa-circle-check"></i> Verified</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6 md:space-y-8">
                <!-- Our Products Grid (Mini) -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-xl overflow-hidden">
                    <div class="flex justify-between items-center bg-slate-50 px-6 py-4 border-b border-slate-100">
                        <h2 class="font-bold text-gray-900 text-lg">Top Products</h2>
                        <a href="/search.php" class="text-xs text-primary font-bold hover:underline">View All</a>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-col gap-4">
                            <?php foreach(array_slice($companyProducts, 0, 4) as $cp): ?>
                                <a href="/product.php?slug=<?php echo urlencode($cp['slug']); ?>" class="flex gap-4 items-center group border border-slate-100 hover:border-primary p-3 rounded-lg transition-colors">
                                    <div class="w-16 h-16 bg-slate-50 flex-shrink-0 flex items-center justify-center rounded-lg border border-slate-100 p-2">
                                        <?php if ($cp['primary_image']): ?>
                                            <img src="/<?php echo htmlspecialchars($cp['primary_image']); ?>" alt="Img" class="max-w-full max-h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-300">
                                        <?php else: ?>
                                            <i class="fa-solid fa-image text-slate-300 text-xl"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-primary transition-colors line-clamp-2 leading-snug mb-1.5"><?php echo htmlspecialchars($cp['name']); ?></h4>
                                        <span class="text-sm font-black text-gray-900"><?php echo $cp['price'] > 0 ? formatPrice($cp['price']) : 'Ask Price'; ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Box -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-xl">
                    <h2 class="bg-slate-50 px-6 py-4 border-b border-slate-100 font-bold text-gray-900 text-lg rounded-t-xl">Contact Us</h2>
                    <div class="p-6 md:p-8">
                        <p class="font-black text-gray-900 mb-2 text-lg">Shri Uddyami Developers</p>
                        <div class="flex items-start gap-3 text-sm text-gray-600 mb-5 mt-4 font-medium">
                            <div class="w-8 h-8 rounded-lg bg-primary/5 text-primary flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <span class="pt-1"><?php echo htmlspecialchars(getSetting('address')); ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600 mb-6 font-medium">
                            <div class="w-8 h-8 rounded-lg bg-primary/5 text-primary flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <span class="font-bold text-gray-900 text-base"><?php echo htmlspecialchars(getSetting('whatsapp')); ?></span>
                        </div>
                        
                        <a href="<?php echo getWhatsappLink('Hi, I want to know more about your company.'); ?>" target="_blank" class="w-full bg-green-50 text-green-600 border border-green-200 hover:bg-green-600 hover:border-green-600 hover:text-white py-3 rounded-xl text-sm font-bold transition-colors flex justify-center items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-lg"></i> Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
