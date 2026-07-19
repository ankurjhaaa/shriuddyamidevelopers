<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us - ' . getSetting('store_name');
include __DIR__ . '/includes/header.php';
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
                    <i class="fa-solid fa-building"></i> Who We Are
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">About <br class="hidden md:block" /><span class="text-primary">Our Company</span></h1>
                <p class="text-gray-300 text-lg md:text-xl max-w-lg mx-auto md:mx-0 leading-relaxed font-medium">We are dedicated to revolutionizing the agriculture and heavy industrial sectors with premium machinery.</p>
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
