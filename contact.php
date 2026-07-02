<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contact Us';
include __DIR__ . '/includes/header.php';

$settings = getAllSettings();
?>

<div class="bg-gray-50 min-h-screen pb-10">
    <!-- Header Area -->
    <div class="bg-primary pt-8 pb-16 px-6 text-center text-white rounded-b-3xl shadow-sm relative z-10">
        <h1 class="text-3xl font-bold mb-2">Get in Touch</h1>
        <p class="text-blue-100 text-sm">We are here to help and answer any question you might have.</p>
    </div>

    <!-- Contact Cards -->
    <div class="px-5 -mt-10 relative z-20 space-y-4">
        
        <!-- Call Card -->
        <a href="tel:<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 group hover:shadow-md transition">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition">
                <i class="fa-solid fa-phone"></i>
            </div>
            <div>
                <h3 class="text-gray-900 font-bold mb-0.5">Call Us</h3>
                <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($settings['phone'] ?? ''); ?></p>
            </div>
        </a>

        <!-- WhatsApp Card -->
        <a href="<?php echo getWhatsappLink(); ?>" target="_blank" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 group hover:shadow-md transition">
            <div class="w-14 h-14 bg-green-50 text-green-600 rounded-full flex items-center justify-center text-3xl group-hover:bg-green-500 group-hover:text-white transition">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div>
                <h3 class="text-gray-900 font-bold mb-0.5">WhatsApp</h3>
                <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?></p>
            </div>
        </a>

        <!-- Address Card -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-5">
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <h3 class="text-gray-900 font-bold mb-1">Our Location</h3>
                <p class="text-gray-600 text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($settings['address'] ?? '')); ?></p>
                <?php if (!empty($settings['gst'])): ?>
                    <p class="text-xs text-gray-400 mt-2 font-medium">GST: <?php echo htmlspecialchars($settings['gst']); ?></p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
