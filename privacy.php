<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Privacy Policy';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Industrial Hero Section -->
    <div class="bg-secondary py-16 lg:py-24 px-6 text-center border-b-4 border-primary relative overflow-hidden z-10">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, #000 25%, transparent 25%, transparent 75%, #000 75%, #000), repeating-linear-gradient(45deg, #000 25%, #1E293B 25%, #1E293B 75%, #000 75%, #000); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
        <div class="relative z-20 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 uppercase tracking-tight">Privacy Policy</h1>
            <div class="w-32 h-1.5 bg-accent mx-auto mb-8 shadow-[0_0_15px_rgba(250,204,21,0.5)]"></div>
            <p class="text-gray-300 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">Last updated: <?php echo date('F d, Y'); ?></p>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 max-w-5xl mx-auto">
        <div class="bg-white p-8 md:p-12 shadow-lg border-t-4 border-primary rounded-sm prose prose-blue max-w-none text-gray-600">
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
