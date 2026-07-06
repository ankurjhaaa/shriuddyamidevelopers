<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'FAQ';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Industrial Hero Section -->
    <div class="bg-secondary py-16 lg:py-24 px-6 text-center border-b-4 border-primary relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), repeating-linear-gradient(45deg, #000 25%, #1E293B 25%, #1E293B 75%, #000 75%, #000); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 uppercase tracking-tight">FAQ</h1>
            <div class="w-32 h-1.5 bg-accent mx-auto mb-8 shadow-[0_0_15px_rgba(250,204,21,0.5)]"></div>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">Find answers to the most common questions about our products, shipping, and heavy-duty services.</p>
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-4xl mx-auto space-y-4">
        <div class="bg-white p-8 rounded-sm shadow-lg border-t-4 border-primary space-y-6">
            <div class="space-y-6">
                
                <details class="group border border-gray-200 bg-white rounded-sm open:border-primary transition duration-300">
                    <summary class="flex justify-between items-center font-black uppercase tracking-tight cursor-pointer list-none p-6 text-secondary text-lg group-open:bg-gray-50 transition">
                        <span>How can I place an order for a machine?</span>
                        <span class="group-open:rotate-180 transition duration-300 text-accent bg-secondary w-8 h-8 flex items-center justify-center rounded-sm">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </summary>
                    <div class="text-gray-600 mt-0 p-6 pt-2 text-base leading-relaxed border-t border-gray-100 font-medium">
                        You can browse our catalog online. Once you find a product, click on the "Ask Price" or "Contact Us" button to connect directly with our sales team via WhatsApp. We will guide you through the purchasing process and arrange delivery.
                    </div>
                </details>

                <details class="group border border-gray-200 bg-white rounded-sm open:border-primary transition duration-300">
                    <summary class="flex justify-between items-center font-black uppercase tracking-tight cursor-pointer list-none p-6 text-secondary text-lg group-open:bg-gray-50 transition">
                        <span>Do you offer warranties on your equipment?</span>
                        <span class="group-open:rotate-180 transition duration-300 text-accent bg-secondary w-8 h-8 flex items-center justify-center rounded-sm">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </summary>
                    <div class="text-gray-600 mt-0 p-6 pt-2 text-base leading-relaxed border-t border-gray-100 font-medium">
                        Yes, all our major machineries and implements come with a standard manufacturer's warranty. The warranty period depends on the specific brand and product category. Please ask our sales representative for details regarding a specific machine.
                    </div>
                </details>

                <details class="group border border-gray-200 bg-white rounded-sm open:border-primary transition duration-300">
                    <summary class="flex justify-between items-center font-black uppercase tracking-tight cursor-pointer list-none p-6 text-secondary text-lg group-open:bg-gray-50 transition">
                        <span>Is transportation/shipping included in the price?</span>
                        <span class="group-open:rotate-180 transition duration-300 text-accent bg-secondary w-8 h-8 flex items-center justify-center rounded-sm">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </summary>
                    <div class="text-gray-600 mt-0 p-6 pt-2 text-base leading-relaxed border-t border-gray-100 font-medium">
                        Shipping and transportation costs vary depending on your location and the size of the equipment. Generally, transportation is calculated separately and quoted during the final purchase agreement.
                    </div>
                </details>

                <details class="group border border-gray-200 bg-white rounded-sm open:border-primary transition duration-300">
                    <summary class="flex justify-between items-center font-black uppercase tracking-tight cursor-pointer list-none p-6 text-secondary text-lg group-open:bg-gray-50 transition">
                        <span>Do you provide after-sales service and spare parts?</span>
                        <span class="group-open:rotate-180 transition duration-300 text-accent bg-secondary w-8 h-8 flex items-center justify-center rounded-sm">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </summary>
                    <div class="text-gray-600 mt-0 p-6 pt-2 text-base leading-relaxed border-t border-gray-100 font-medium">
                        Absolutely! We understand the importance of keeping your machinery running. We provide comprehensive after-sales service and stock genuine spare parts for all the brands we represent.
                    </div>
                </details>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
