<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Terms of Service';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-primary rounded-b-lg text-center py-8 mb-10">
    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Terms of Service</h1>
    <p class="text-blue-100 text-xs">Last updated: <?php echo date('F d, Y'); ?></p>
</div>

<div class="px-4 sm:px-6 lg:px-8 pb-12 max-w-4xl mx-auto bg-white min-h-screen">

    <div class="prose prose-blue max-w-none text-gray-600">
        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">1. Acceptance of Terms</h2>
        <p class="mb-4 leading-relaxed">
            By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. In addition, when using these particular services, you shall be subject to any posted guidelines or rules applicable to such services.
        </p>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">2. Product Information and Pricing</h2>
        <p class="mb-4 leading-relaxed">
            While we strive to provide accurate product and pricing information, pricing or typographical errors may occur. In the event that an item is listed at an incorrect price or with incorrect information due to an error, we shall have the right, at our sole discretion, to refuse or cancel any orders placed for that item.
        </p>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">3. Warranties and Liability</h2>
        <p class="mb-4 leading-relaxed">
            The materials and products provided by this website are provided "as is" and without warranties of any kind, whether express or implied. We do not represent or warrant that the functions contained in the site will be uninterrupted or error-free.
        </p>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">4. Governing Law</h2>
        <p class="mb-4 leading-relaxed">
            Any claim relating to our web site shall be governed by the laws of the State without regard to its conflict of law provisions.
        </p>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
