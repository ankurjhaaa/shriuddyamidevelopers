<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us - ' . getSetting('store_name');
include __DIR__ . '/includes/header.php';
?>

<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Industrial Hero Section -->
    <div class="bg-secondary py-16 lg:py-24 px-6 text-center border-b-4 border-primary relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), repeating-linear-gradient(45deg, #000 25%, #1E293B 25%, #1E293B 75%, #000 75%, #000); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 uppercase tracking-tight">About Us</h1>
            <div class="w-32 h-1.5 bg-accent mx-auto mb-8 shadow-[0_0_15px_rgba(250,204,21,0.5)]"></div>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">We are dedicated to revolutionizing the agriculture and heavy industrial sectors with premium machinery.</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-7xl mx-auto space-y-8">
        
        <div class="bg-white p-8 md:p-12 shadow-lg border-t-4 border-primary grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center rounded-sm">
            <div>
                <span class="text-accent font-bold tracking-widest uppercase text-sm mb-2 block">Our Mission</span>
                <h2 class="text-3xl md:text-4xl font-black text-secondary mb-6 uppercase tracking-tight">Empowering Growth</h2>
                <div class="w-16 h-1 bg-gray-300 mb-6"></div>
                <p class="text-gray-600 mb-6 leading-relaxed text-lg">
                    Our mission is to empower farmers, industrialists, and builders with state-of-the-art tools and machinery. We believe that the right tools can exponentially increase productivity, reduce manual labor, and foster sustainable development.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Since our inception, we have partnered with top manufacturers worldwide to bring premium quality equipment straight to your doorstep. We take pride in our robust supply chain and exceptional customer service.
                </p>
            </div>
            <div class="relative">
                <div class="aspect-square md:aspect-auto md:h-96 bg-secondary rounded-sm overflow-hidden relative shadow-md">
                    <img src="https://images.unsplash.com/photo-1592982537447-6f296d1931eb?auto=format&fit=crop&q=80&w=1000" alt="Agriculture field" class="w-full h-full object-cover mix-blend-overlay opacity-80">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-8">
                        <div class="w-10 h-1 bg-accent mb-3"></div>
                        <div class="text-white font-bold text-xl uppercase tracking-wider">Premium Machinery</div>
                    </div>
                </div>
                <!-- Industrial decorative accent -->
                <div class="absolute -bottom-4 -right-4 w-24 h-24 border-b-4 border-r-4 border-primary z-0"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 bg-white shadow-md border border-gray-200 hover:border-primary transition group rounded-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-gray-50 transform rotate-45 translate-x-8 -translate-y-8 group-hover:bg-primary transition"></div>
                <div class="w-14 h-14 bg-secondary text-accent flex items-center justify-center mb-6 rounded-sm shadow-inner">
                    <i class="fa-solid fa-medal text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-secondary mb-3 uppercase tracking-tight">Premium Quality</h3>
                <p class="text-gray-600 leading-relaxed">All our machines undergo strict quality checks to ensure they withstand the toughest environments.</p>
            </div>
            
            <div class="p-8 bg-white shadow-md border border-gray-200 hover:border-primary transition group rounded-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-gray-50 transform rotate-45 translate-x-8 -translate-y-8 group-hover:bg-primary transition"></div>
                <div class="w-14 h-14 bg-secondary text-accent flex items-center justify-center mb-6 rounded-sm shadow-inner">
                    <i class="fa-solid fa-headset text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-secondary mb-3 uppercase tracking-tight">24/7 Support</h3>
                <p class="text-gray-600 leading-relaxed">Our expert support team is always available to assist you with technical queries and product guidance.</p>
            </div>
            
            <div class="p-8 bg-white shadow-md border border-gray-200 hover:border-primary transition group rounded-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-gray-50 transform rotate-45 translate-x-8 -translate-y-8 group-hover:bg-primary transition"></div>
                <div class="w-14 h-14 bg-secondary text-accent flex items-center justify-center mb-6 rounded-sm shadow-inner">
                    <i class="fa-solid fa-handshake-angle text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-secondary mb-3 uppercase tracking-tight">Trusted Partner</h3>
                <p class="text-gray-600 leading-relaxed">Thousands of farmers and business owners trust us for their daily heavy-duty operational needs.</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
