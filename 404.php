<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Page Not Found';
include __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col items-center justify-center min-h-[70vh] px-6 text-center">
    <div class="text-blue-900 mb-6">
        <i class="fa-solid fa-triangle-exclamation text-7xl"></i>
    </div>
    <h1 class="text-4xl font-bold text-gray-900 mb-2">404</h1>
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Page Not Found</h2>
    <p class="text-gray-500 mb-8 max-w-sm">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
    <a href="/" class="bg-primary text-white font-semibold py-3 px-8 rounded-lg shadow-sm hover:bg-blue-800 transition">Go to Home</a>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
    