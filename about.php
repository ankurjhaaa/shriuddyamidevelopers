<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us - ' . getSetting('store_name');
include __DIR__ . '/includes/header.php';
?>

<div class="bg-white min-h-screen pb-16">
    <!-- Hero Section -->
    <div class="bg-primary py-16 lg:py-20 px-6 text-center border-b border-primary relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), repeating-linear-gradient(45deg, #000 25%, #1E293B 25%, #1E293B 75%, #000 75%, #000); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">About Us</h1>
            <p class="text-gray-100 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">We are dedicated to revolutionizing the agriculture and heavy industrial sectors with premium machinery.</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-7xl mx-auto space-y-8">
        
        <div class="bg-white p-8 md:p-12 border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center rounded-md">
            <div>
                <span class="text-primary font-bold tracking-widest uppercase text-sm mb-2 block">Our Mission</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Empowering Growth</h2>
                <p class="text-gray-600 mb-6 leading-relaxed text-lg">
                    Our mission is to empower farmers, industrialists, and builders with state-of-the-art tools and machinery. We believe that the right tools can exponentially increase productivity, reduce manual labor, and foster sustainable development.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Since our inception, we have partnered with top manufacturers worldwide to bring premium quality equipment straight to your doorstep. We take pride in our robust supply chain and exceptional customer service.
                </p>
            </div>
            <div class="relative">
                <div class="aspect-square md:aspect-auto md:h-96 bg-gray-100 rounded-md overflow-hidden relative border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1592982537447-6f296d1931eb?auto=format&fit=crop&q=80&w=1000" alt="Agriculture field" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-8">
                        <div class="text-white font-bold text-xl uppercase tracking-wider">Premium Machinery</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-8 bg-white border border-gray-200 hover:border-primary/50 transition-colors group rounded-md">
                <div class="w-12 h-12 bg-blue-50 text-primary flex items-center justify-center mb-6 rounded-md">
                    <i class="fa-solid fa-medal text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Premium Quality</h3>
                <p class="text-gray-600 leading-relaxed text-sm">All our machines undergo strict quality checks to ensure they withstand the toughest environments.</p>
            </div>
            
            <div class="p-8 bg-white border border-gray-200 hover:border-primary/50 transition-colors group rounded-md">
                <div class="w-12 h-12 bg-blue-50 text-primary flex items-center justify-center mb-6 rounded-md">
                    <i class="fa-solid fa-headset text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">24/7 Support</h3>
                <p class="text-gray-600 leading-relaxed text-sm">Our expert support team is always available to assist you with technical queries and product guidance.</p>
            </div>
            
            <div class="p-8 bg-white border border-gray-200 hover:border-primary/50 transition-colors group rounded-md">
                <div class="w-12 h-12 bg-blue-50 text-primary flex items-center justify-center mb-6 rounded-md">
                    <i class="fa-solid fa-handshake-angle text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Trusted Partner</h3>
                <p class="text-gray-600 leading-relaxed text-sm">Thousands of farmers and business owners trust us for their daily heavy-duty operational needs.</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
