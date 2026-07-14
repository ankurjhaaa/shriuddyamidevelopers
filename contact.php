<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';

$settings = getAllSettings();
?>

<div class="bg-white min-h-screen pb-16">
    <!-- Clean Structural Header -->
    <div class="bg-white border-b border-gray-200 py-6 px-4 mb-8">
        <div class="max-w-[1440px] mx-auto">
            <nav class="text-xs text-gray-500 mb-2 font-medium">
                <a href="/" class="hover:text-primary">Home</a> &gt; 
                <span class="text-gray-800">Contact Us</span>
            </nav>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Contact Us</h1>
            <p class="text-sm text-gray-600">Reach out to us for product inquiries, bulk orders, or dealership opportunities.</p>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 md:px-4">
        
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Side: Contact Information Cards -->
            <div class="w-full lg:w-1/3 flex flex-col gap-4">
                
                <!-- Head Office Info -->
                <div class="bg-white border border-gray-200 rounded-md p-5">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Head Office</h2>
                    
                    <div class="flex items-start gap-3 mb-4">
                        <i class="fa-solid fa-location-dot text-gray-400 mt-1"></i>
                        <div>
                            <span class="block text-sm font-semibold text-gray-800 mb-1">Address</span>
                            <span class="block text-sm text-gray-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($settings['address'] ?? '')); ?></span>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3 mb-4">
                        <i class="fa-solid fa-phone text-gray-400 mt-1"></i>
                        <div>
                            <span class="block text-sm font-semibold text-gray-800 mb-1">Sales & Support</span>
                            <a href="tel:<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="block text-sm text-blue-600 hover:underline"><?php echo htmlspecialchars($settings['phone'] ?? ''); ?></a>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <i class="fa-brands fa-whatsapp text-gray-400 mt-1"></i>
                        <div>
                            <span class="block text-sm font-semibold text-gray-800 mb-1">WhatsApp</span>
                            <a href="<?php echo getWhatsappLink(); ?>" target="_blank" class="block text-sm text-green-600 hover:underline"><?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?></a>
                        </div>
                    </div>
                </div>

                <!-- Business Details -->
                <div class="bg-white border border-gray-200 rounded-md p-5">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Business Information</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <?php if (!empty($settings['gst'])): ?>
                        <div>
                            <span class="block text-xs text-gray-500 uppercase font-medium mb-1">GSTIN</span>
                            <span class="block text-sm text-gray-800 font-semibold"><?php echo htmlspecialchars($settings['gst']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div>
                            <span class="block text-xs text-gray-500 uppercase font-medium mb-1">Business Hours</span>
                            <span class="block text-sm text-gray-800 font-semibold">9:00 AM - 6:00 PM</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Closed on Sundays</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-100 hover:bg-gray-50 transition">
                        <a href="/about.php" class="text-sm font-semibold text-gray-800 flex-grow">About Our Company</a>
                        <i class="fa-solid fa-angle-right text-gray-400"></i>
                    </div>
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                        <a href="/faq.php" class="text-sm font-semibold text-gray-800 flex-grow">Frequently Asked Questions</a>
                        <i class="fa-solid fa-angle-right text-gray-400"></i>
                    </div>
                </div>

            </div>

            <!-- Right Side: Interactive Map -->
            <div class="w-full lg:w-2/3 bg-white border border-gray-200 rounded-md flex flex-col p-5">
                <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Locate Us</h2>
                <div class="flex-grow bg-gray-50 border border-gray-200 rounded-md min-h-[400px] flex flex-col items-center justify-center">
                    <i class="fa-solid fa-map-location-dot text-5xl text-gray-300 mb-3"></i>
                    <span class="text-sm text-gray-500">Map Integration Area</span>
                    <span class="text-xs text-gray-400 mt-1">Embed Google Maps iframe here</span>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
