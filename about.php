<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-white min-h-screen pb-10">
    <!-- Hero Section -->
    <div class="bg-primary pt-6 pb-14 px-6 text-center text-white rounded-b-lg relative z-10">
        <h1 class="text-2xl font-bold mb-1">About <?php echo htmlspecialchars(getSetting('store_name')); ?></h1>
        <p class="text-blue-100 text-xs">We are dedicated to revolutionizing the agriculture and industrial sectors</p>
    </div>

    <!-- Content Section -->
    <div class="px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 max-w-7xl mx-auto space-y-6">
        <div class="bg-white p-6 md:p-10 rounded-xl shadow-sm border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20 items-center">
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
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-medal text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Premium Quality</h3>
                <p class="text-gray-500 text-sm leading-relaxed">All our machines undergo strict quality checks to ensure they withstand the toughest environments.</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-headset text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">24/7 Support</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Our expert support team is always available to assist you with technical queries and product guidance.</p>
            </div>
            <div class="text-center p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-handshake-angle text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Trusted Partner</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Thousands of farmers and business owners trust us for their daily operational needs.</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
