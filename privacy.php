<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Privacy Policy';
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
                    <i class="fa-solid fa-shield-halved"></i> Data Security
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">Privacy <br class="hidden md:block" /><span class="text-primary">Policy</span></h1>
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
            <p class="mb-8 leading-relaxed font-medium text-lg text-secondary">
                Your privacy is important to us. It is our policy to respect your privacy regarding any information we may collect from you across our website, and other sites we own and operate.
            </p>

            <h2 class="text-2xl font-black text-secondary mt-8 mb-4 uppercase tracking-tight">Information We Collect</h2>
            <p class="mb-8 leading-relaxed font-medium">
                We only ask for personal information when we truly need it to provide a service to you (like when you unlock a price or contact us via WhatsApp). We collect it by fair and lawful means, with your knowledge and consent.
            </p>

            <h2 class="text-2xl font-black text-secondary mt-8 mb-4 uppercase tracking-tight">How We Use Information</h2>
            <p class="mb-4 leading-relaxed font-medium">
                We use the information we collect in various ways, including to:
            </p>
            <ul class="list-disc pl-6 mb-8 space-y-2 font-medium">
                <li>Provide, operate, and maintain our website</li>
                <li>Improve, personalize, and expand our website</li>
                <li>Understand and analyze how you use our website</li>
                <li>Communicate with you, either directly or through one of our partners, including for customer service.</li>
            </ul>

            <h2 class="text-2xl font-black text-secondary mt-8 mb-4 uppercase tracking-tight">Data Security</h2>
            <p class="mb-4 leading-relaxed font-medium">
                We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable.
            </p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
