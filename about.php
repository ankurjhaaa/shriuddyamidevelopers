<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us - ' . getSetting('store_name');
include __DIR__ . '/includes/header.php';
?>

<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Section -->
    <div class="bg-slate-900 py-16 md:py-20 relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4">About Us</h1>
            <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">We are dedicated to revolutionizing the agriculture and heavy industrial sectors with premium machinery.</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-7xl mx-auto space-y-8">
        
        <!-- Main Mission Card (Flat Design) -->
        <div class="bg-white p-6 md:p-10 border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-10 items-center rounded-xl">
            <div>
                <span class="text-primary font-bold tracking-widest uppercase text-xs mb-3 block"><i class="fa-solid fa-rocket mr-1"></i> Our Mission</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-5 leading-tight">Empowering Growth</h2>
                <p class="text-gray-600 mb-4 leading-relaxed font-medium">
                    Our mission is to empower farmers, industrialists, and builders with state-of-the-art tools and machinery. We believe that the right tools can exponentially increase productivity, reduce manual labor, and foster sustainable development.
                </p>
                <p class="text-gray-600 leading-relaxed font-medium">
                    Since our inception, we have partnered with top manufacturers worldwide to bring premium quality equipment straight to your doorstep. We take pride in our robust supply chain and exceptional customer service.
                </p>
            </div>
            <div class="relative h-full w-full">
                <div class="aspect-video md:aspect-square md:h-[400px] w-full bg-slate-100 rounded-lg overflow-hidden relative border border-slate-200">
                    <img src="https://images.unsplash.com/photo-1592982537447-6f296d1931eb?auto=format&fit=crop&q=80&w=1000" alt="Agriculture field" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6">
                        <div class="text-white font-bold text-xl uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-tractor text-primary"></i> Premium Machinery</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-8 bg-white border border-slate-200 shadow-sm hover:border-primary transition-colors group rounded-xl">
                <div class="w-12 h-12 bg-primary/5 text-primary border border-primary/20 flex items-center justify-center mb-5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fa-solid fa-medal text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Premium Quality</h3>
                <p class="text-gray-600 leading-relaxed text-sm font-medium">All our machines undergo strict quality checks to ensure they withstand the toughest environments.</p>
            </div>
            
            <div class="p-8 bg-white border border-slate-200 shadow-sm hover:border-primary transition-colors group rounded-xl">
                <div class="w-12 h-12 bg-primary/5 text-primary border border-primary/20 flex items-center justify-center mb-5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fa-solid fa-headset text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">24/7 Support</h3>
                <p class="text-gray-600 leading-relaxed text-sm font-medium">Our expert support team is always available to assist you with technical queries and product guidance.</p>
            </div>
            
            <div class="p-8 bg-white border border-slate-200 shadow-sm hover:border-primary transition-colors group rounded-xl">
                <div class="w-12 h-12 bg-primary/5 text-primary border border-primary/20 flex items-center justify-center mb-5 rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fa-solid fa-handshake-angle text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Trusted Partner</h3>
                <p class="text-gray-600 leading-relaxed text-sm font-medium">Thousands of farmers and business owners trust us for their daily heavy-duty operational needs.</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
