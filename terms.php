<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Terms of Service';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Industrial Hero Section -->
    <div class="bg-secondary py-16 lg:py-24 px-6 text-center border-b-4 border-primary relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), repeating-linear-gradient(45deg, #000 25%, #1E293B 25%, #1E293B 75%, #000 75%, #000); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 uppercase tracking-tight">Terms of Service</h1>
            <div class="w-32 h-1.5 bg-accent mx-auto mb-8 shadow-[0_0_15px_rgba(250,204,21,0.5)]"></div>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">Last updated: <?php echo date('F d, Y'); ?></p>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-5xl mx-auto">
        <div class="bg-white p-8 md:p-12 shadow-lg border-t-4 border-primary rounded-sm prose prose-blue max-w-none text-gray-600">
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
