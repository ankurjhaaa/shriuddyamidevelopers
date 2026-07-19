<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'FAQ';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Section -->
    <div class="bg-slate-900 py-16 md:py-20 relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4">FAQ</h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto leading-relaxed">Find answers to the most common questions about our products, shipping, and heavy-duty services.</p>
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
