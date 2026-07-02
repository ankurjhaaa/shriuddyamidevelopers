<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Privacy Policy';
include __DIR__ . '/includes/header.php';
?>

<div class="bg-primary rounded-b-lg text-center py-8 mb-10">
    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Privacy Policy</h1>
    <p class="text-blue-100 text-xs">Last updated: <?php echo date('F d, Y'); ?></p>
</div>

<div class="px-4 sm:px-6 lg:px-8 pb-12 max-w-4xl mx-auto bg-white min-h-screen">

    <div class="prose prose-blue max-w-none text-gray-600">
        <p class="mb-6 leading-relaxed text-lg">
            Your privacy is important to us. It is our policy to respect your privacy regarding any information we may collect from you across our website, and other sites we own and operate.
        </p>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">Information We Collect</h2>
        <p class="mb-4 leading-relaxed">
            We only ask for personal information when we truly need it to provide a service to you (like when you unlock a price or contact us via WhatsApp). We collect it by fair and lawful means, with your knowledge and consent.
        </p>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">How We Use Information</h2>
        <p class="mb-4 leading-relaxed">
            We use the information we collect in various ways, including to:
        </p>
        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li>Provide, operate, and maintain our website</li>
            <li>Improve, personalize, and expand our website</li>
            <li>Understand and analyze how you use our website</li>
            <li>Communicate with you, either directly or through one of our partners, including for customer service.</li>
        </ul>

        <h2 class="text-xl font-bold text-gray-900 mt-8 mb-4">Data Security</h2>
        <p class="mb-4 leading-relaxed">
            We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable.
        </p>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
