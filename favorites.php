<?php
$pageTitle = 'Favorites';
include __DIR__ . '/includes/header.php';
?>

<div class="px-4 py-6 bg-white min-h-[80vh] flex flex-col items-center justify-center text-center">
    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-4xl mb-4">
        <i class="fa-regular fa-heart"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">No Favorites Yet</h2>
    <p class="text-gray-500 max-w-[250px] mb-6 text-sm">Tap the heart icon on any product to save it here for later.</p>
    <a href="/categories.php" class="bg-primary text-white font-semibold py-3 px-8 rounded-lg shadow-sm hover:bg-blue-800 transition">Browse Products</a>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
