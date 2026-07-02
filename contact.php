<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';

$settings = getAllSettings();
?>

<div class="bg-white min-h-screen pb-10">
    <!-- Header Area -->
    <div class="bg-primary pt-6 pb-14 px-6 text-center text-white rounded-b-lg relative z-10">
        <h1 class="text-2xl font-bold mb-1">Get in Touch</h1>
        <p class="text-blue-100 text-xs">We are here to help and answer any question you might have.</p>
    </div>

    <!-- Contact Cards -->
    <div class="px-5 -mt-10 relative z-20 space-y-4">
        
        <!-- Call Card -->
        <a href="tel:<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="bg-white p-4 rounded-lg border border-gray-200 flex items-center gap-4 group">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl">
                <i class="fa-solid fa-phone"></i>
            </div>
            <div>
                <h3 class="text-gray-900 font-semibold mb-0.5 text-sm">Call Us</h3>
                <p class="text-gray-500 text-xs"><?php echo htmlspecialchars($settings['phone'] ?? ''); ?></p>
            </div>
        </a>

        <!-- WhatsApp Card -->
        <a href="<?php echo getWhatsappLink(); ?>" target="_blank" class="bg-white p-4 rounded-lg border border-gray-200 flex items-center gap-4 group">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center text-2xl">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div>
                <h3 class="text-gray-900 font-semibold mb-0.5 text-sm">WhatsApp</h3>
                <p class="text-gray-500 text-xs"><?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?></p>
            </div>
        </a>

        <!-- Address Card -->
        <div class="bg-white p-4 rounded-lg border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h3 class="text-gray-900 font-semibold mb-1 text-sm">Our Location</h3>
                <p class="text-gray-600 text-xs leading-relaxed"><?php echo nl2br(htmlspecialchars($settings['address'] ?? '')); ?></p>
                <?php if (!empty($settings['gst'])): ?>
                    <p class="text-[10px] text-gray-400 mt-1 font-medium">GST: <?php echo htmlspecialchars($settings['gst']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Helpful Links -->
        <div class="pt-6 pb-2">
            <h3 class="text-xs font-bold text-gray-500 mb-3 px-1 uppercase tracking-wider">Helpful Links</h3>
            <div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100 overflow-hidden shadow-sm">
                <a href="/about.php" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-building text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">About Us</span>
                    </div>
                    <i class="fa-solid fa-angle-right text-gray-400 text-xs"></i>
                </a>
                <a href="/faq.php" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-circle-question text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">FAQs</span>
                    </div>
                    <i class="fa-solid fa-angle-right text-gray-400 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Admin Login Link for Mobile -->
        <div class="mt-8 mb-4 text-center md:hidden">
            <a href="/admin_login.php" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-lock"></i> Admin Login
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
