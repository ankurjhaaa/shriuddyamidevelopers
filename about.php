<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<div class="bg-primary rounded-b-lg text-center py-10 md:py-16 mb-10">
    <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">About <?php echo htmlspecialchars(getSetting('store_name')); ?></h1>
        <p class="text-blue-100 text-sm md:text-base leading-relaxed font-light">
            We are dedicated to revolutionizing the agriculture and industrial sectors by providing high-quality, durable, and advanced machinery to power your growth.
        </p>
    </div>
</div>

<!-- Content Section -->
<div class="px-4 sm:px-6 lg:px-8 pb-12 max-w-7xl mx-auto bg-white">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20 items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
            <p class="text-gray-600 mb-6 leading-relaxed">
                Our mission is to empower farmers, industrialists, and builders with state-of-the-art tools and machinery. We believe that the right tools can exponentially increase productivity, reduce manual labor, and foster sustainable development.
            </p>
            <p class="text-gray-600 leading-relaxed">
                Since our inception, we have partnered with top manufacturers worldwide to bring premium quality equipment straight to your doorstep. We take pride in our robust supply chain and exceptional customer service.
            </p>
        </div>
        <div class="relative">
            <div class="aspect-video bg-gray-50 rounded-lg overflow-hidden border border-gray-200 relative">
                <img src="https://images.unsplash.com/photo-1592982537447-6f296d1931eb?auto=format&fit=crop&q=80&w=1000" alt="Agriculture field" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <div class="absolute bottom-4 left-6 text-white font-semibold text-sm">Empowering Growth</div>
            </div>
        </div>
    </div>
    
    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-6 bg-gray-50 rounded-lg border border-gray-200">
            <div class="w-16 h-16 bg-blue-100 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-medal text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Premium Quality</h3>
            <p class="text-gray-500 text-sm leading-relaxed">All our machines undergo strict quality checks to ensure they withstand the toughest environments.</p>
        </div>
        <div class="text-center p-6 bg-gray-50 rounded-lg border border-gray-200">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-headset text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">24/7 Support</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Our expert support team is always available to assist you with technical queries and product guidance.</p>
        </div>
        <div class="text-center p-6 bg-gray-50 rounded-lg border border-gray-200">
            <div class="w-12 h-12 bg-yellow-100 text-accent rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-handshake-angle text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Trusted Partner</h3>
            <p class="text-gray-500 text-xs leading-relaxed">Thousands of farmers and business owners trust us for their daily operational needs.</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
