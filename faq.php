<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'FAQ';
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
                    <i class="fa-solid fa-circle-question"></i> Help Center
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">Common <br class="hidden md:block" /><span class="text-primary">Questions</span></h1>
                <p class="text-gray-300 text-lg md:text-xl max-w-lg mx-auto md:mx-0 leading-relaxed font-medium">Find answers to the most common questions about our products, shipping, and heavy-duty services.</p>
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

    <!-- FAQ Content -->
    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-4xl mx-auto space-y-4">
        <div class="bg-white p-6 md:p-8 rounded-xl border border-slate-200 shadow-sm space-y-4">
            <div class="space-y-4">
                
                <details class="group border border-slate-200 bg-white rounded-lg open:border-primary transition duration-300">
                    <summary class="flex justify-between items-center font-bold tracking-tight cursor-pointer list-none p-5 text-gray-900 text-lg group-open:bg-slate-50 transition rounded-lg">
                        <span>How can I place an order for a machine?</span>
                        <span class="group-open:rotate-180 transition duration-300 text-primary bg-primary/10 w-8 h-8 flex items-center justify-center rounded">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </summary>
                    <div class="text-gray-600 mt-0 p-5 pt-2 text-base leading-relaxed border-t border-slate-100 font-medium">
                        You can browse our catalog online. Once you find a product, click on the "Ask Price" or "Contact Us" button to connect directly with our sales team via WhatsApp. We will guide you through the purchasing process and arrange delivery.
                    </div>
                </details>

                <details class="group border border-slate-200 bg-white rounded-lg open:border-primary transition duration-300">
                    <summary class="flex justify-between items-center font-bold tracking-tight cursor-pointer list-none p-5 text-gray-900 text-lg group-open:bg-slate-50 transition rounded-lg">
                        <span>Do you offer warranties on your equipment?</span>
                        <span class="group-open:rotate-180 transition duration-300 text-primary bg-primary/10 w-8 h-8 flex items-center justify-center rounded">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </summary>
                    <div class="text-gray-600 mt-0 p-5 pt-2 text-base leading-relaxed border-t border-slate-100 font-medium">
                        Yes, all our major machineries and implements come with a standard manufacturer's warranty. The warranty period depends on the specific brand and product category. Please ask our sales representative for details regarding a specific machine.
                    </div>
                </details>

                <details class="group border border-slate-200 bg-white rounded-lg open:border-primary transition duration-300">
                    <summary class="flex justify-between items-center font-bold tracking-tight cursor-pointer list-none p-5 text-gray-900 text-lg group-open:bg-slate-50 transition rounded-lg">
                        <span>Is transportation/shipping included in the price?</span>
                        <span class="group-open:rotate-180 transition duration-300 text-primary bg-primary/10 w-8 h-8 flex items-center justify-center rounded">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </summary>
                    <div class="text-gray-600 mt-0 p-5 pt-2 text-base leading-relaxed border-t border-slate-100 font-medium">
                        Shipping and transportation costs vary depending on your location and the size of the equipment. Generally, transportation is calculated separately and quoted during the final purchase agreement.
                    </div>
                </details>

                <details class="group border border-slate-200 bg-white rounded-lg open:border-primary transition duration-300">
                    <summary class="flex justify-between items-center font-bold tracking-tight cursor-pointer list-none p-5 text-gray-900 text-lg group-open:bg-slate-50 transition rounded-lg">
                        <span>Do you provide after-sales service and spare parts?</span>
                        <span class="group-open:rotate-180 transition duration-300 text-primary bg-primary/10 w-8 h-8 flex items-center justify-center rounded">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </summary>
                    <div class="text-gray-600 mt-0 p-5 pt-2 text-base leading-relaxed border-t border-slate-100 font-medium">
                        Absolutely! We understand the importance of keeping your machinery running. We provide comprehensive after-sales service and stock genuine spare parts for all the brands we represent.
                    </div>
                </details>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
