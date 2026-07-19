<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';

$settings = getAllSettings();
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
                    <i class="fa-solid fa-headset"></i> Support & Sales
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">Get <br class="hidden md:block" /><span class="text-primary">In Touch</span></h1>
                <p class="text-gray-300 text-lg md:text-xl max-w-lg mx-auto md:mx-0 leading-relaxed font-medium">Reach out to us for product inquiries, bulk orders, dealership opportunities, or technical support.</p>
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
