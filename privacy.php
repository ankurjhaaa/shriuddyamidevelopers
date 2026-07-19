<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Privacy Policy';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Premium Hero Section -->
    <div class="bg-slate-900 py-16 md:py-20 relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#f97316 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4 uppercase tracking-tight">Privacy Policy</h1>
            <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">Last updated: <?php echo date('F d, Y'); ?></p>
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
