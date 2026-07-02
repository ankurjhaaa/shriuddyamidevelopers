<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<div class="px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <div class="relative bg-gradient-to-br from-primary via-blue-800 to-blue-900 rounded-[2rem] overflow-hidden shadow-xl text-center py-16 md:py-24">
        <!-- Abstract Shapes -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-secondary opacity-20 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent opacity-20 rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
        
        <div class="relative z-10 max-w-3xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">About <?php echo htmlspecialchars(getSetting('store_name')); ?></h1>
            <p class="text-blue-100 text-lg md:text-xl leading-relaxed font-light">
                We are dedicated to revolutionizing the agriculture and industrial sectors by providing high-quality, durable, and advanced machinery to power your growth.
            </p>
        </div>
    </div>
</div>

<!-- Content Section -->
<div class="px-4 sm:px-6 lg:px-8 py-12 max-w-7xl mx-auto animate-slide-up bg-white">
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
            <div class="aspect-video bg-gray-50 rounded-2xl overflow-hidden shadow-lg border border-gray-100 relative">
                <img src="https://images.unsplash.com/photo-1592982537447-6f296d1931eb?auto=format&fit=crop&q=80&w=1000" alt="Agriculture field" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                <div class="absolute bottom-4 left-6 text-white font-semibold">Empowering Growth</div>
            </div>
            <!-- Decorative dots -->
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-accent opacity-10 rounded-full blur-xl z-[-1]"></div>
        </div>
    </div>
    
    <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-md transition">
            <div class="w-16 h-16 bg-blue-100 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-medal text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Premium Quality</h3>
            <p class="text-gray-500 text-sm leading-relaxed">All our machines undergo strict quality checks to ensure they withstand the toughest environments.</p>
        </div>
        <div class="text-center p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-md transition">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-headset text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Our expert support team is always available to assist you with technical queries and product guidance.</p>
        </div>
        <div class="text-center p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-md transition">
            <div class="w-16 h-16 bg-yellow-100 text-accent rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-handshake-angle text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">Trusted Partner</h3>
            <p class="text-gray-500 text-sm leading-relaxed">Thousands of farmers and business owners trust us for their daily operational needs.</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
