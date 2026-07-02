<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'FAQ';
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<div class="px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <div class="bg-gray-50 rounded-[2rem] p-10 md:p-16 text-center border border-gray-100 shadow-sm">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
        <p class="text-gray-500 max-w-xl mx-auto">Find answers to the most common questions about our products, shipping, and services.</p>
    </div>
</div>

<!-- FAQ Content -->
<div class="px-4 sm:px-6 lg:px-8 py-8 max-w-4xl mx-auto animate-slide-up bg-white min-h-[50vh]">
    <div class="space-y-4">
        
        <details class="group border border-gray-100 bg-white rounded-xl shadow-sm open:bg-gray-50 open:ring-1 open:ring-gray-200 transition-all duration-300">
            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-5 text-gray-900">
                <span>How can I place an order for a machine?</span>
                <span class="transition group-open:rotate-180">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </span>
            </summary>
            <div class="text-gray-600 mt-2 p-5 pt-0 text-sm leading-relaxed border-t border-gray-100">
                You can browse our catalog online. Once you find a product, click on the "Ask Price" or "Contact Us" button to connect directly with our sales team via WhatsApp. We will guide you through the purchasing process and arrange delivery.
            </div>
        </details>

        <details class="group border border-gray-100 bg-white rounded-xl shadow-sm open:bg-gray-50 open:ring-1 open:ring-gray-200 transition-all duration-300">
            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-5 text-gray-900">
                <span>Do you offer warranties on your equipment?</span>
                <span class="transition group-open:rotate-180">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </span>
            </summary>
            <div class="text-gray-600 mt-2 p-5 pt-0 text-sm leading-relaxed border-t border-gray-100">
                Yes, all our major machineries and implements come with a standard manufacturer's warranty. The warranty period depends on the specific brand and product category. Please ask our sales representative for details regarding a specific machine.
            </div>
        </details>

        <details class="group border border-gray-100 bg-white rounded-xl shadow-sm open:bg-gray-50 open:ring-1 open:ring-gray-200 transition-all duration-300">
            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-5 text-gray-900">
                <span>Is transportation/shipping included in the price?</span>
                <span class="transition group-open:rotate-180">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </span>
            </summary>
            <div class="text-gray-600 mt-2 p-5 pt-0 text-sm leading-relaxed border-t border-gray-100">
                Shipping and transportation costs vary depending on your location and the size of the equipment. Generally, transportation is calculated separately and quoted during the final purchase agreement.
            </div>
        </details>

        <details class="group border border-gray-100 bg-white rounded-xl shadow-sm open:bg-gray-50 open:ring-1 open:ring-gray-200 transition-all duration-300">
            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-5 text-gray-900">
                <span>Do you provide after-sales service and spare parts?</span>
                <span class="transition group-open:rotate-180">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </span>
            </summary>
            <div class="text-gray-600 mt-2 p-5 pt-0 text-sm leading-relaxed border-t border-gray-100">
                Absolutely! We understand the importance of keeping your machinery running. We provide comprehensive after-sales service and stock genuine spare parts for all the brands we represent.
            </div>
        </details>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
