<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Terms of Service';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Section -->
    <div class="bg-slate-900 py-16 md:py-20 relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4 uppercase tracking-tight">Terms of Service</h1>
            <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">Last updated: <?php echo date('F d, Y'); ?></p>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-4xl mx-auto">
        <div class="bg-white p-6 md:p-10 border border-slate-200 shadow-sm rounded-xl prose prose-slate max-w-none text-gray-600">
            <h2 class="text-2xl font-black text-secondary mt-0 mb-4 uppercase tracking-tight">1. Acceptance of Terms</h2>
            <p class="mb-8 leading-relaxed font-medium">
                By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. In addition, when using these particular services, you shall be subject to any posted guidelines or rules applicable to such services.
            </p>

            <h2 class="text-2xl font-black text-secondary mt-8 mb-4 uppercase tracking-tight">2. Product Information and Pricing</h2>
            <p class="mb-8 leading-relaxed font-medium">
                While we strive to provide accurate product and pricing information, pricing or typographical errors may occur. In the event that an item is listed at an incorrect price or with incorrect information due to an error, we shall have the right, at our sole discretion, to refuse or cancel any orders placed for that item.
            </p>

            <h2 class="text-2xl font-black text-secondary mt-8 mb-4 uppercase tracking-tight">3. Warranties and Liability</h2>
            <p class="mb-8 leading-relaxed font-medium">
                The materials and products provided by this website are provided "as is" and without warranties of any kind, whether express or implied. We do not represent or warrant that the functions contained in the site will be uninterrupted or error-free.
            </p>

            <h2 class="text-2xl font-black text-secondary mt-8 mb-4 uppercase tracking-tight">4. Governing Law</h2>
            <p class="mb-4 leading-relaxed font-medium">
                Any claim relating to our web site shall be governed by the laws of the State without regard to its conflict of law provisions.
            </p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
