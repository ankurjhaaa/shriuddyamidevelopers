<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Terms of Service';
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
                    <i class="fa-solid fa-file-contract"></i> Legal Details
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">Terms of <br class="hidden md:block" /><span class="text-primary">Service</span></h1>
                <p class="text-gray-300 text-lg md:text-xl max-w-lg mx-auto md:mx-0 leading-relaxed font-medium">Last updated: <?php echo date('F d, Y'); ?></p>
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
