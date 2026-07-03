<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'FAQ';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-white min-h-screen pb-10">
    <!-- Hero Section -->
    <div class="bg-gray-50 pt-10 pb-16 px-6 text-center border-b border-blue-50 relative z-10">
        <h1 class="text-2xl font-bold mb-1">Frequently Asked Questions</h1>
        <p class="text-gray-500 text-xs">Find answers to the most common questions about our products, shipping, and services.</p>
    </div>

    <!-- FAQ Content -->
    <div class="px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 max-w-4xl mx-auto space-y-4">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
    <div class="space-y-4">
        
        <details class="group border border-gray-200 bg-white rounded-lg open:bg-gray-50">
            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-4 text-gray-900 text-sm">
                <span>How can I place an order for a machine?</span>
                <span class="group-open:rotate-180">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </span>
            </summary>
            <div class="text-gray-600 mt-0 p-4 pt-0 text-xs leading-relaxed border-t border-gray-100">
                You can browse our catalog online. Once you find a product, click on the "Ask Price" or "Contact Us" button to connect directly with our sales team via WhatsApp. We will guide you through the purchasing process and arrange delivery.
            </div>
        </details>

        <details class="group border border-gray-200 bg-white rounded-lg open:bg-gray-50">
            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-4 text-gray-900 text-sm">
                <span>Do you offer warranties on your equipment?</span>
                <span class="group-open:rotate-180">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </span>
            </summary>
            <div class="text-gray-600 mt-0 p-4 pt-0 text-xs leading-relaxed border-t border-gray-100">
                Yes, all our major machineries and implements come with a standard manufacturer's warranty. The warranty period depends on the specific brand and product category. Please ask our sales representative for details regarding a specific machine.
            </div>
        </details>

        <details class="group border border-gray-200 bg-white rounded-lg open:bg-gray-50">
            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-4 text-gray-900 text-sm">
                <span>Is transportation/shipping included in the price?</span>
                <span class="group-open:rotate-180">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </span>
            </summary>
            <div class="text-gray-600 mt-0 p-4 pt-0 text-xs leading-relaxed border-t border-gray-100">
                Shipping and transportation costs vary depending on your location and the size of the equipment. Generally, transportation is calculated separately and quoted during the final purchase agreement.
            </div>
        </details>

        <details class="group border border-gray-200 bg-white rounded-lg open:bg-gray-50">
            <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-4 text-gray-900 text-sm">
                <span>Do you provide after-sales service and spare parts?</span>
                <span class="group-open:rotate-180">
                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                </span>
            </summary>
            <div class="text-gray-600 mt-0 p-4 pt-0 text-xs leading-relaxed border-t border-gray-100">
                Absolutely! We understand the importance of keeping your machinery running. We provide comprehensive after-sales service and stock genuine spare parts for all the brands we represent.
            </div>
        </details>

        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
