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

<div class="bg-white min-h-screen pb-16 pt-4">
    <div class="max-w-[1440px] mx-auto px-2 md:px-4">
        
        <!-- Company Banner -->
        <div class="bg-white rounded-md border border-gray-200 overflow-hidden mb-6">
            <!-- Cover/Top color bar -->
            <div class="h-32 md:h-48 bg-primary relative">
                <!-- Optional: Cover background pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 20px 20px;"></div>
            </div>
            
            <div class="px-6 md:px-10 pb-6 relative">
                <div class="flex flex-col md:flex-row gap-6 md:items-end -mt-16 md:-mt-24 mb-4 relative z-10">
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-md border-4 border-white shadow-md flex items-center justify-center overflow-hidden flex-shrink-0 z-10">
                        <img src="/assets/images/logo.png" alt="Company Logo" class="max-w-full max-h-full object-contain p-2">
                    </div>
                    
                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-1">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Shri Uddyami Developers</h1>
                            <i class="fa-solid fa-circle-check text-blue-500 text-xl" title="Verified Seller"></i>
                        </div>
                        <p class="text-gray-600 text-sm flex items-center gap-2 mb-2">
                            <i class="fa-solid fa-location-dot text-gray-400"></i> <?php echo htmlspecialchars(getSetting('address')); ?>
                        </p>
                        <div class="flex flex-wrap gap-2 md:gap-4 mt-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-green-50 text-green-700 text-xs font-bold border border-green-200">
                                <i class="fa-solid fa-shield-check"></i> TrustSEAL Verified
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100">
                                <i class="fa-solid fa-industry"></i> Manufacturer & Wholesaler
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex flex-col gap-3 w-full md:w-auto mt-4 md:mt-0">
                        <a href="<?php echo getWhatsappLink('I have a requirement'); ?>" target="_blank" class="bg-accent hover:bg-orange-600 text-white px-6 py-2.5 rounded text-sm font-bold shadow-sm transition text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Contact Supplier
                        </a>
                        <button class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 px-6 py-2.5 rounded text-sm font-bold shadow-sm transition text-center flex items-center justify-center gap-2">
                            <i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars(getSetting('whatsapp')); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Sidebar: About & Contact -->
            <div class="w-full lg:col-span-2 flex flex-col gap-6">
                <!-- About Box -->
                <div class="bg-white border border-gray-200 rounded-md">
                    <h2 class="bg-gray-100 px-5 py-3 border-b border-gray-200 font-bold text-gray-800 text-base rounded-t-md">About Company</h2>
                    <div class="p-5 text-sm text-gray-700 leading-relaxed space-y-4">
                        <p>Welcome to <strong>Shri Uddyami Developers</strong>, a premium enterprise. We are a leading manufacturer, wholesaler, and trader of high-quality agricultural and industrial machinery based in Purnea, Bihar.</p>
                        
                        <p>With years of experience in the industry, we specialize in providing robust, durable, and highly efficient machines tailored to meet the dynamic needs of modern agriculture and industrial processing. Our extensive product range includes Commercial Atta Chakki, Domestic Flour Mills, Rice Mill Machines, Destoner Machines, and much more.</p>

                        <p>Our commitment is to deliver technological excellence and unparalleled customer service. Every machine we offer undergoes stringent quality checks to ensure optimal performance, low maintenance, and long service life. We aim to empower farmers and small businesses with the best tools to enhance their productivity and profitability.</p>
                    </div>
                </div>

                <!-- Company Highlights -->
                <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
                    <h2 class="bg-gray-100 px-5 py-3 border-b border-gray-200 font-bold text-gray-800 text-base">Factsheet</h2>
                    <div class="p-5">
                        <table class="w-full text-sm text-left border-collapse">
                            <tbody>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-4 text-gray-500 w-1/3 bg-gray-50 border-r border-gray-100 font-medium">Nature of Business</td>
                                    <td class="py-3 px-4 text-gray-800 font-semibold">Manufacturer & Wholesaler</td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-4 text-gray-500 w-1/3 bg-gray-50 border-r border-gray-100 font-medium">Company CEO</td>
                                    <td class="py-3 px-4 text-gray-800 font-semibold">Authorized Representative</td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-4 text-gray-500 w-1/3 bg-gray-50 border-r border-gray-100 font-medium">Registered Address</td>
                                    <td class="py-3 px-4 text-gray-800 font-semibold"><?php echo htmlspecialchars(getSetting('address')); ?></td>
                                </tr>
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-4 text-gray-500 w-1/3 bg-gray-50 border-r border-gray-100 font-medium">Industry</td>
                                    <td class="py-3 px-4 text-gray-800 font-semibold">Agriculture & Industrial Machinery</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 w-1/3 bg-gray-50 border-r border-gray-100 font-medium">GST No.</td>
                                    <td class="py-3 px-4 text-gray-800 font-semibold">10AXXXX1234X1Z5 (Verified)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <!-- Our Products Grid (Mini) -->
                <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
                    <div class="flex justify-between items-center bg-gray-100 px-5 py-3 border-b border-gray-200">
                        <h2 class="font-bold text-gray-800 text-base">Top Products</h2>
                        <a href="/search.php" class="text-xs text-primary font-bold hover:underline">View All</a>
                    </div>
                    <div class="p-3">
                        <div class="flex flex-col gap-3">
                            <?php foreach(array_slice($companyProducts, 0, 4) as $cp): ?>
                                <a href="/product.php?slug=<?php echo urlencode($cp['slug']); ?>" class="flex gap-3 items-center group border border-transparent hover:border-gray-200 p-2 rounded transition">
                                    <div class="w-16 h-16 bg-gray-50 flex-shrink-0 flex items-center justify-center rounded border border-gray-200 p-1">
                                        <?php if ($cp['primary_image']): ?>
                                            <img src="/<?php echo htmlspecialchars($cp['primary_image']); ?>" alt="Img" class="max-w-full max-h-full object-contain mix-blend-multiply">
                                        <?php else: ?>
                                            <i class="fa-solid fa-image text-gray-300 text-xl"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="text-xs font-semibold text-blue-700 group-hover:underline line-clamp-2 leading-tight mb-1"><?php echo htmlspecialchars($cp['name']); ?></h4>
                                        <span class="text-xs font-bold text-gray-900"><?php echo $cp['price'] > 0 ? formatPrice($cp['price']) : 'Ask Price'; ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Box -->
                <div class="bg-white border border-gray-200 rounded-md">
                    <h2 class="bg-gray-100 px-5 py-3 border-b border-gray-200 font-bold text-gray-800 text-base rounded-t-md">Contact Us</h2>
                    <div class="p-5">
                        <p class="font-bold text-gray-800 mb-1">Shri Uddyami Developers</p>
                        <div class="flex items-start gap-2 text-sm text-gray-600 mb-4 mt-2">
                            <i class="fa-solid fa-location-dot text-gray-400 mt-1 w-4"></i>
                            <span><?php echo htmlspecialchars(getSetting('address')); ?></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                            <i class="fa-solid fa-phone text-gray-400 w-4"></i>
                            <span class="font-bold text-gray-800"><?php echo htmlspecialchars(getSetting('whatsapp')); ?></span>
                        </div>
                        
                        <a href="<?php echo getWhatsappLink('Hi, I want to know more about your company.'); ?>" target="_blank" class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded text-sm font-bold shadow transition flex justify-center items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-lg"></i> Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
