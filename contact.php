<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';

$settings = getAllSettings();
?>

<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Industrial Hero Section -->
    <div class="bg-secondary py-16 lg:py-24 px-6 text-center border-b-4 border-primary relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), repeating-linear-gradient(45deg, #000 25%, #1E293B 25%, #1E293B 75%, #000 75%, #000); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 uppercase tracking-tight">Contact Us</h1>
            <div class="w-32 h-1.5 bg-accent mx-auto mb-8 shadow-[0_0_15px_rgba(250,204,21,0.5)]"></div>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">We are here to help and answer any question you might have regarding our machinery.</p>
        </div>
    </div>

    <!-- Contact Cards -->
    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Call Card -->
        <a href="tel:<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="bg-white p-8 rounded-sm shadow-md border-t-4 border-primary hover:shadow-lg transition group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gray-50 transform rotate-45 translate-x-8 -translate-y-8 group-hover:bg-primary transition"></div>
            <div class="w-14 h-14 bg-secondary text-accent rounded-sm flex items-center justify-center text-2xl mb-6 shadow-inner">
                <i class="fa-solid fa-phone"></i>
            </div>
            <div>
                <h3 class="text-secondary font-black uppercase tracking-tight mb-2 text-xl">Call Us</h3>
                <p class="text-gray-600 font-medium text-lg"><?php echo htmlspecialchars($settings['phone'] ?? ''); ?></p>
            </div>
        </a>

        <!-- WhatsApp Card -->
        <a href="<?php echo getWhatsappLink(); ?>" target="_blank" class="bg-white p-8 rounded-sm shadow-md border-t-4 border-green-500 hover:shadow-lg transition group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gray-50 transform rotate-45 translate-x-8 -translate-y-8 group-hover:bg-green-500 transition"></div>
            <div class="w-14 h-14 bg-secondary text-green-400 rounded-sm flex items-center justify-center text-3xl mb-6 shadow-inner">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div>
                <h3 class="text-secondary font-black uppercase tracking-tight mb-2 text-xl">WhatsApp</h3>
                <p class="text-gray-600 font-medium text-lg"><?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?></p>
            </div>
        </a>

        <!-- Address Card -->
        <div class="bg-white p-8 rounded-sm shadow-md border-t-4 border-accent relative overflow-hidden">
            <div class="w-14 h-14 bg-secondary text-accent rounded-sm flex items-center justify-center text-2xl mb-6 shadow-inner">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h3 class="text-secondary font-black uppercase tracking-tight mb-2 text-xl">Our Location</h3>
                <p class="text-gray-600 font-medium leading-relaxed"><?php echo nl2br(htmlspecialchars($settings['address'] ?? '')); ?></p>
                <?php if (!empty($settings['gst'])): ?>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">GST: <span class="text-gray-900"><?php echo htmlspecialchars($settings['gst']); ?></span></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Helpful Links -->
    <div class="px-4 sm:px-6 lg:px-8 mt-12 max-w-7xl mx-auto">
        <h3 class="text-sm font-black text-secondary mb-6 px-1 uppercase tracking-widest flex items-center gap-2">
            <div class="w-4 h-4 bg-primary"></div> Helpful Links
        </h3>
        <div class="bg-white rounded-sm shadow-sm border border-gray-200 divide-y divide-gray-100 overflow-hidden max-w-3xl">
            <a href="/about.php" class="flex items-center justify-between p-6 hover:bg-gray-50 transition group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-sm bg-secondary text-accent flex items-center justify-center">
                        <i class="fa-solid fa-building text-sm"></i>
                    </div>
                    <span class="text-lg font-bold text-gray-800 uppercase tracking-tight group-hover:text-primary transition">About Us</span>
                </div>
                <i class="fa-solid fa-angle-right text-gray-400 text-lg group-hover:text-primary transition transform group-hover:translate-x-1"></i>
            </a>
            <a href="/faq.php" class="flex items-center justify-between p-6 hover:bg-gray-50 transition group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-sm bg-secondary text-accent flex items-center justify-center">
                        <i class="fa-solid fa-circle-question text-sm"></i>
                    </div>
                    <span class="text-lg font-bold text-gray-800 uppercase tracking-tight group-hover:text-primary transition">FAQs</span>
                </div>
                <i class="fa-solid fa-angle-right text-gray-400 text-lg group-hover:text-primary transition transform group-hover:translate-x-1"></i>
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
