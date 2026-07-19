<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';

$settings = getAllSettings();
?>

<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Section -->
    <div class="bg-slate-900 py-16 md:py-20 relative overflow-hidden z-10 mb-8">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">Contact <span class="text-primary">Us</span></h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto leading-relaxed">Reach out to us for product inquiries, bulk orders, or dealership opportunities.</p>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 md:px-8 -mt-16 relative z-20">
        
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Side: Contact Information Cards -->
            <div class="w-full lg:w-1/3 flex flex-col gap-6">
                
                <!-- Head Office Info -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-6 md:p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Head Office</h2>
                    
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-primary/5 text-primary flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-900 mb-1">Address</span>
                            <span class="block text-sm text-gray-600 leading-relaxed font-medium"><?php echo nl2br(htmlspecialchars($settings['address'] ?? '')); ?></span>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-primary/5 text-primary flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-900 mb-1">Sales & Support</span>
                            <a href="tel:<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="block text-sm text-primary font-medium hover:underline"><?php echo htmlspecialchars($settings['phone'] ?? ''); ?></a>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-gray-900 mb-1">WhatsApp</span>
                            <a href="<?php echo getWhatsappLink(); ?>" target="_blank" class="block text-sm text-green-600 font-medium hover:underline"><?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?></a>
                        </div>
                    </div>
                </div>

                <!-- Business Details -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-6 md:p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Business Information</h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <?php if (!empty($settings['gst'])): ?>
                        <div>
                            <span class="block text-xs text-gray-500 uppercase font-bold mb-1 tracking-wider">GSTIN</span>
                            <span class="block text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($settings['gst']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div>
                            <span class="block text-xs text-gray-500 uppercase font-bold mb-1 tracking-wider">Business Hours</span>
                            <span class="block text-sm text-gray-900 font-medium">9:00 AM - 6:00 PM</span>
                            <span class="block text-[11px] text-gray-400 mt-1 font-medium">Closed on Sundays</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-xl overflow-hidden">
                    <a href="/about.php" class="flex items-center justify-between p-5 border-b border-gray-100 hover:bg-slate-50 transition group">
                        <span class="text-sm font-bold text-gray-800 group-hover:text-primary transition-colors">About Our Company</span>
                        <i class="fa-solid fa-angle-right text-gray-400 group-hover:text-primary transition-colors"></i>
                    </a>
                    <a href="/faq.php" class="flex items-center justify-between p-5 hover:bg-slate-50 transition group">
                        <span class="text-sm font-bold text-gray-800 group-hover:text-primary transition-colors">Frequently Asked Questions</span>
                        <i class="fa-solid fa-angle-right text-gray-400 group-hover:text-primary transition-colors"></i>
                    </a>
                </div>

            </div>

            <!-- Right Side: Interactive Map -->
            <div class="w-full lg:w-2/3 bg-white border border-slate-200 shadow-sm rounded-xl flex flex-col p-6 md:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Locate Us</h2>
                <div class="flex-grow bg-slate-50 border border-slate-200 rounded-lg min-h-[400px] flex flex-col items-center justify-center">
                    <i class="fa-solid fa-map-location-dot text-5xl text-slate-300 mb-4"></i>
                    <span class="text-sm font-medium text-gray-500">Map Integration Area</span>
                    <span class="text-xs text-gray-400 mt-1">Embed Google Maps iframe here</span>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
